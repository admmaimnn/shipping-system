<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $table = 'booking';

    protected $fillable = [
        'booking_no',
        'shipper_name',
        'vessel_name',
        'status',
        'booking_reference',
        'teu',
        'volume',
        'container_damage_assessment',
        'vsl_date',
        'port_of_discharge',
        'vol_40ft',
        'hq_20ft',
        'remarks',
    ];

    protected $casts = [
        'vsl_date' => 'date',
    ];
}