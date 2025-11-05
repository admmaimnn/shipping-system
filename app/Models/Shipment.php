<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'vessel_name',
        'booking_id',
        'shipment_date',
        'shipper_name',
        'bill_of_lading_number',
        'number_of_containers',
        'invoice_number',
        'cost',
        'sales',
        'profit',
        'port_origin',
        'port_destination',
        'attachment',
        'raw_text',
    ];
}
