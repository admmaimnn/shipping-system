<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->decimal('cost', 10, 2)->nullable()->after('number_of_containers');
            $table->decimal('sales', 10, 2)->nullable()->after('cost');
            $table->decimal('profit', 10, 2)->nullable()->after('sales');
            $table->string('invoice_number')->nullable()->after('bill_of_lading_number');
            $table->string('attachment')->nullable()->after('profit');
        });
    }

    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn(['cost', 'sales', 'profit', 'invoice_number', 'attachment']);
        });
    }
};
