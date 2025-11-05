<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            // Kita akan guna string untuk menyimpan status seperti 'Pending', 'Completed', 'In Transit'
            $table->string('status')->after('port_destination')->nullable(); 
            // ^--- Sila gantikan 'cargo_details' dengan nama lajur yang betul untuk kedudukan yang sesuai.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
