<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\DocumentAI\V1\Client\DocumentProcessorServiceClient;
use Google\Cloud\DocumentAI\V1\ProcessRequest;
use Google\Cloud\DocumentAI\V1\RawDocument;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Shipment;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;

class ShipmentController extends Controller
{
    private const FIELD_MAPPINGS = [
        'bill_of_lading_number' => ['Bill of Laden No', 'Bill of Lading No', 'B/L No', 'BL No', 'BOL Number', 'Bill of Laden No :'],
        'booking_id' => ['Booking Ref', 'Booking No', 'Booking Reference', 'Booking Number', 'Booking Ref :', 'Hooking Ref'],
        'port_origin' => ['Port of Origin', 'Port Origin', 'From Port', 'Origin Port', 'POL'],
        'port_destination' => ['Port of Destination', 'Port of Discharge', 'To Port', 'Destination Port', 'POD', 'Port of Discharge :'],
        'shipment_date' => ['Shipment Date', 'ETD', 'ETA/ETD', 'Date', 'Departure Date', 'Invoice Date', 'Invoice Date:'],
        'shipper_name' => ['Shipper', 'Prepared by', 'Exporter', 'Shipper Name', 'Bill To'],
        'vessel_name' => ['Vessel', 'Vessel Name', 'Ship Name', 'VESSEL'],
        'number_of_containers' => ['No of Containers', 'Containers', 'Container Qty', 'Container Count', 'Cargo Details'],
        'invoice_number' => ['Invoice No', 'Invoice Number', 'Invoice #', 'Invoice No:', 'Ref No', 'Ref No:', 'Reference No'],
        'sales' => ['Sales', 'Total Amount', 'Amount', 'TOTAL AMOUNT'], 
        'cost' => ['Cost', 'Total Cost'], 
    ];

    // Regex patterns for text extraction
    private const TEXT_PATTERNS = [
        'bill_of_lading_number' => [
            '/Bill\s+of\s+Laden\s+No\s*:?\s*([A-Z0-9]{10,})/i',
            '/B\/L\s*No\.?\s*:?\s*([A-Z0-9]{10,})/i',
            '/Bill\s+of\s+Lading\s+No\.?\s*:?\s*([A-Z0-9]{10,})/i',
            '/BOL\s*:?\s*([A-Z0-9]{10,})/i',
        ],
        'booking_id' => [
            '/Booking\s+Ref\.?\s*:?\s*([A-Z0-9\/\-]{8,})/i',
            '/Booking\s+No\.?\s*:?\s*([A-Z0-9\/\-]{8,})/i',
            '/Hooking\s+Ref\.?\s*:?\s*([A-Z0-9\/\-]{8,})/i', 
        ],
        'invoice_number' => [
            '/Invoice\s+No\.?\s*:?\s*([A-Z0-9\/\-]{5,})/i',
            '/Ref\.?\s+No\.?\s*:?\s*([A-Z0-9\/\-]{5,})/i',
            '/Reference\s+No\.?\s*:?\s*([A-Z0-9\/\-]{5,})/i',
        ],
        'port_origin' => [
            '/Port\s+of\s+Origin\s*:?\s*([A-Z\s]{3,20})/i',
            '/From\s+Port\s*:?\s*([A-Z\s]{3,20})/i',
        ],
        'port_destination' => [
            '/Port\s+of\s+Discharge\s*:?\s*([A-Z\s]{3,20})/i',
            '/Port\s+of\s+Destination\s*:?\s*([A-Z\s]{3,20})/i',
            '/To\s+Port\s*:?\s*([A-Z\s]{3,20})/i',
        ],
        'shipper_name' => [
            '/Prepared\s+by\s*:?\s*([A-Z\s]{3,30})/i',
            '/Shipper\s*:?\s*([A-Z\s]{3,30})/i',
            '/Bill\s+To\s*:?\s*([A-Z\s&]{5,50})/i',
        ],
        'vessel_name' => [
            '/VESSEL\s*:?\s*([A-Z0-9\s]{3,40}\s*\d{3,4}[NW])/i',
            '/Vessel\s*:?\s*([A-Z0-9\s]{3,40})/i',
            '/Vessel\s+Name\s*:?\s*([A-Z0-9\s]{3,40})/i',
        ],
        'number_of_containers' => [
            '/(\d+)\s*[Xx×]\s*\d+[\'"]?\s*(?:HQ|HC|GP|RF)/i',
            '/Cargo\s+Details\s*:?\s*([0-9X\'\s]+)/i',
            '/Container\s+No\.?\s*:?\s*.+?QTY\s*:?\s*(\d+)/is',
        ],
        'shipment_date' => [
            '/(?:ETA\/ETD|Invoice\s+Date|Date)\s*:?\s*(\d{1,2}(?:st|nd|rd|th)?[\.\/\-\s]+[A-Z]+[\.\/\-\s]+\d{4})/i',
            '/(?:ETA\/ETD|Date)\s*:?\s*(\d{1,2}[\.\/\-]\d{1,2}[\.\/\-]\d{2,4})/i',
        ],
        'sales' => [
            '/TOTAL\s+AMOUNT\s*RM\s*\$?\s*([\d,]+\.?\d*)/i',
            '/Total\s*:?\s*RM\s*\$?\s*([\d,]+\.?\d*)/i',
            '/Amount\s*:?\s*RM\s*\$?\s*([\d,]+\.?\d*)/i',
            '/Sales\s*(?:\(RM\))?\s*:?\s*RM?\s*\$?\s*([\d,]+\.?\d*)/i',
        ],
        'cost' => [
            '/Cost\s*:?\s*RM\s*([\d,]+\.?\d*)/i',
            '/Total\s+Cost\s*:?\s*RM\s*([\d,]+\.?\d*)/i',
        ],
    ];

    public function create(Request $request)
    {
        $parsedData = $this->getEmptyParsedData();

        if ($request->isMethod('post') && $request->hasFile('document')) {
            try {
                $this->validateDocumentUpload($request);
                $parsedData = $this->processDocument($request->file('document'));
            } catch (\Exception $e) {
                Log::error('Document processing error: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString()
                ]);
                
                return redirect()->route('shipments.create')
                    ->with('error', 'Failed to process document: ' . $e->getMessage());
            }
        }

        return view('shipments.create', ['parsedData' => $parsedData]);
    }

    private function getEmptyParsedData(): array
    {
        return [
            'vessel_name' => '',
            'booking_id' => '',
            'shipment_date' => '',
            'shipper_name' => '',
            'bill_of_lading_number' => '',
            'number_of_containers' => '',
            'invoice_number' => '',
            'port_origin' => '',
            'port_destination' => '',
            'cost' => '',
            'sales' => '',
            'raw_text' => 'Manual Entry',
            'form_fields' => []
        ];
    }

    private function validateDocumentUpload(Request $request): void
    {
        $request->validate([
            'document' => 'required|mimes:pdf,jpg,jpeg,png|max:8192'
        ]);
    }

    private function processDocument($file): array
    {
        $filePath = $file->getRealPath();
        $mimeType = $file->getMimeType();
        $content = file_get_contents($filePath);

        Log::info('Document uploaded:', [
            'name' => $file->getClientOriginalName(),
            'mime' => $mimeType,
            'size_kb' => round(filesize($filePath) / 1024, 2)
        ]);

        $processorName = env('GOOGLE_PROCESSOR_NAME');
        
        if (empty($processorName)) {
            throw new \Exception('GOOGLE_PROCESSOR_NAME not configured in environment');
        }

        Log::info('Using processor:', ['processor' => $processorName]);

        $credentialsPath = storage_path('app/keys/adamapi-477300-58a7204b9f3a.json');
        
        if (!file_exists($credentialsPath)) {
            throw new \Exception('Google Cloud credentials file not found');
        }

        $client = new DocumentProcessorServiceClient([
            'credentials' => $credentialsPath
        ]);

        try {
            $rawDocument = new RawDocument([
                'content' => $content,
                'mime_type' => $mimeType
            ]);

            $requestDoc = new ProcessRequest([
                'name' => $processorName,
                'raw_document' => $rawDocument
            ]);

            Log::info('Sending document to Google Document AI...');
            $response = $client->processDocument($requestDoc);
            $document = $response->getDocument();

            $jsonOutput = $response->serializeToJsonString();
            Storage::put('documentai_output.json', $jsonOutput);
            Log::info('Document AI response saved');

            $rawText = $document->getText() ?? '';
            Log::info('Extracted raw text length:', ['length' => strlen($rawText)]);

            $formFields = $this->extractFormFieldsFromDocument($document);
            Log::info('Extracted form fields count:', ['count' => count($formFields)]);

            $parsedData = $this->mapFieldsToShipmentData($formFields, $rawText);
            
            $parsedData = $this->enhanceWithTextParsing($parsedData, $rawText);
            
            Log::info('Final parsed data ready:', array_filter($parsedData, fn($v) => $v !== '' && $v !== 'Not Found'));

            return $parsedData;

        } finally {
            $client->close();
        }
    }

    protected function extractFormFieldsFromDocument($document): array
    {
        $result = [];

        if (method_exists($document, 'getPages')) {
            foreach ($document->getPages() as $page) {
                foreach ($page->getFormFields() as $field) {
                    $nameText = $field->getFieldName()?->getTextAnchor()?->getContent();
                    $valueText = $field->getFieldValue()?->getTextAnchor()?->getContent();

                    if (!empty($nameText)) {
                        $label = $this->normalizeFieldName($nameText);
                        $value = trim($valueText ?? '');
                        
                        $result[$label] = isset($result[$label])
                            ? $result[$label] . ', ' . $value
                            : $value;
                    }
                }
            }
        }

        if (empty($result) && method_exists($document, 'getEntities')) {
            foreach ($document->getEntities() as $entity) {
                $type = $entity->getType();
                $text = $entity->getMentionText() ?? '';
                
                if (!empty($type) && !empty($text)) {
                    $normalizedType = $this->normalizeFieldName($type);
                    $result[$normalizedType] = trim($text);
                }
            }
        }

        Log::info('Total extracted fields: ' . count($result));
        return $result;
    }

    private function normalizeFieldName(string $name): string
    {
        return trim(preg_replace('/\s+/', ' ', $name));
    }

    private function mapFieldsToShipmentData(array $formFields, string $rawText): array
    {
        $parsedData = $this->getEmptyParsedData();
        $parsedData['raw_text'] = $rawText;
        $parsedData['form_fields'] = $formFields;

        foreach (self::FIELD_MAPPINGS as $targetField => $possibleNames) {
            $parsedData[$targetField] = $this->findFieldValue($formFields, $possibleNames);
        }

        if ($parsedData['shipment_date'] !== 'Not Found') {
            $parsedData['shipment_date'] = $this->normalizeDate($parsedData['shipment_date']);
        }

        if ($parsedData['number_of_containers'] !== 'Not Found') {
            $parsedData['number_of_containers'] = $this->extractContainerCount($parsedData['number_of_containers']);
        }

        return $parsedData;
    }

    private function enhanceWithTextParsing(array $parsedData, string $rawText): array
    {
        Log::info('Enhancing data with text parsing for missing fields...');

        foreach (self::TEXT_PATTERNS as $field => $patterns) {
            if ($parsedData[$field] === 'Not Found' || $parsedData[$field] === '') {
                foreach ($patterns as $pattern) {
                    if (preg_match($pattern, $rawText, $matches)) {
                        $value = trim($matches[1]);
                        
                        $value = $this->postProcessExtractedValue($field, $value);
                        
                        if (!empty($value)) {
                            $parsedData[$field] = $value;
                            Log::info("✨ Text parsing found {$field}: {$value}");
                            break;
                        }
                    }
                }
            }
        }

        return $parsedData;
    }

    private function postProcessExtractedValue(string $field, string $value): string
    {
        $value = trim($value);

        switch ($field) {
            case 'shipment_date':
                return $this->normalizeDate($value);
                
            case 'number_of_containers':
                return $this->extractContainerCount($value);
                
            case 'port_origin':
            case 'port_destination':
                return strtoupper(trim($value));
                
            case 'shipper_name':
                return ucwords(strtolower(trim($value)));
                
            case 'vessel_name':
                $value = preg_replace('/\s+/', ' ', $value);
                return strtoupper(trim($value));
                
            case 'cost':
            case 'sales':
                return str_replace(',', '', $value);
                
            default:
                return $value;
        }
    }

    private function findFieldValue(array $formFields, array $possibleNames): string
    {
        foreach ($possibleNames as $name) {
            if (isset($formFields[$name]) && !empty($formFields[$name])) {
                return $formFields[$name];
            }
            
            foreach ($formFields as $key => $value) {
                if (strcasecmp($key, $name) === 0 && !empty($value)) {
                    return $value;
                }
            }
        }
        
        return 'Not Found';
    }

    private function normalizeDate(string $dateString): string
    {
        try {
            $dateString = preg_replace('/(\d+)(st|nd|rd|th)\.?\s+/i', '$1 ', $dateString);
            $dateString = str_replace('.', '-', $dateString);
            
            $date = Carbon::parse($dateString);
            return $date->format('Y-m-d');
        } catch (\Exception $e) {
            Log::warning('Failed to parse date: ' . $dateString);
            return $dateString;
        }
    }

    private function extractContainerCount(string $text): string
    {
        if (preg_match('/^(\d+)\s*[Xx×]/', $text, $matches)) {
            return $matches[1];
        }
        
        if (preg_match('/\d+/', $text, $matches)) {
            return $matches[0];
        }
        
        return $text;
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'vessel_name' => 'required|string|max:255',
            'booking_id' => 'nullable|string|max:255',
            'shipment_date' => 'required|date',
            'shipper_name' => 'required|string|max:255',
            'bill_of_lading_number' => 'required|string|max:255',
            'number_of_containers' => 'required|integer|min:1',
            'invoice_number' => 'nullable|string|max:255',
            'cost' => 'nullable|numeric|min:0',
            'sales' => 'nullable|numeric|min:0',
            'port_origin' => 'required|string|max:255',
            'port_destination' => 'required|string|max:255',
            'raw_text' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
            $validatedData['attachment'] = $path;
        }

        $validatedData['profit'] = ($validatedData['sales'] ?? 0) - ($validatedData['cost'] ?? 0);
        $validatedData['user_id'] = Auth::id();

        $shipment = Shipment::create($validatedData);

        Log::info('Shipment created:', ['id' => $shipment->id]);

        return redirect()->route('shipments.success')->with('shipment', $validatedData);
    }

    public function success(Request $request)
    {
        $shipment = $request->session()->get('shipment');

        if (!$shipment) {
            return redirect()->route('shipments.create')
                ->with('error', 'No shipment information found. Please try again.');
        }

        return view('shipments.success', compact('shipment'));
    }

    public function index()
    {
        $shipments = Shipment::orderBy('created_at', 'desc')->get();
        $totalShipments = Shipment::count();
        $completedShipments = Shipment::where('status', 'Completed')->count();
        $pendingShipments = Shipment::where('status', 'Pending')->count();

        $tasks = Task::with('user')->get();
        $users = User::all();
        $totalTasks = $tasks->count();

        return view('dashboard', compact(
            'shipments',
            'totalShipments',
            'completedShipments',
            'pendingShipments',
            'tasks',
            'users',
            'totalTasks'
        ));
    }

    public function edit($id)
    {
        $shipment = Shipment::findOrFail($id);
        return view('shipments.edit', compact('shipment'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'vessel_name' => 'required|string|max:255',
            'booking_id' => 'nullable|string|max:255',
            'shipment_date' => 'required|date',
            'shipper_name' => 'required|string|max:255',
            'bill_of_lading_number' => 'required|string|max:255',
            'number_of_containers' => 'required|integer|min:1',
            'invoice_number' => 'nullable|string|max:255',
            'cost' => 'nullable|numeric|min:0',
            'sales' => 'nullable|numeric|min:0',
            'port_origin' => 'required|string|max:255',
            'port_destination' => 'required|string|max:255',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'status' => 'nullable|string|max:50',
        ]);

        $shipment = Shipment::findOrFail($id);

        if ($request->hasFile('attachment')) {
            if ($shipment->attachment) {
                Storage::disk('public')->delete($shipment->attachment);
            }
            $path = $request->file('attachment')->store('attachments', 'public');
            $validatedData['attachment'] = $path;
        }

        $validatedData['profit'] = ($validatedData['sales'] ?? 0) - ($validatedData['cost'] ?? 0);
        $shipment->update($validatedData);

        return redirect()->route('dashboard')->with('success', 'Shipment updated successfully!');
    }

    public function destroy($id)
    {
        $shipment = Shipment::findOrFail($id);
        
        if ($shipment->attachment) {
            Storage::disk('public')->delete($shipment->attachment);
        }
        
        $shipment->delete();

        return redirect()->route('dashboard')->with('success', 'Shipment deleted successfully!');
    }
}