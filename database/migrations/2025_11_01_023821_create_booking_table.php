<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking', function (Blueprint $table) {
            $table->id();
            $table->string('booking_no')->unique();
            $table->string('shipper_name');
            $table->string('vessel_name')->nullable();
            $table->string('status')->default('Pending'); // Pending, Confirmed, Sailed, Delivered
            $table->string('booking_reference')->nullable();
            $table->integer('teu')->nullable(); // Twenty-foot Equivalent Unit
            $table->integer('volume')->nullable(); // in cbm
            $table->string('container_damage_assessment')->nullable();
            $table->date('vsl_date')->nullable(); // Vessel Sailing Date
            $table->string('port_of_discharge')->nullable(); // POD
            $table->integer('vol_40ft')->nullable();
            $table->integer('hq_20ft')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};