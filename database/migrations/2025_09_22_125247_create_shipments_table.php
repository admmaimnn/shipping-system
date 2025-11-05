<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('vessel_name')->nullable();
            $table->string('booking_id')->nullable();
            $table->date('shipment_date')->nullable();
            $table->string('shipper_name')->nullable();
            $table->string('bill_of_lading_number')->nullable();
            $table->integer('number_of_containers')->nullable();
            $table->text('raw_text')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
