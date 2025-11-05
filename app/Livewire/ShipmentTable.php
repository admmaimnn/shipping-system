<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Shipment;
use Livewire\WithPagination;

class ShipmentTable extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $shipments = Shipment::where('vessel_name', 'like', '%' . $this->search . '%')
                            ->orWhere('booking_id', 'like', '%' . $this->search . '%')
                            ->orderBy('shipment_date', 'desc')
                            ->paginate(10);

        return view('livewire.shipment-table', [
            'shipments' => $shipments,
        ]);
    }
}