<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('services', 8, 2)->after('total')->default(0);
            $table->decimal('taxes', 8, 2)->default(0);
            $table->decimal('final_total', 8, 2);
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['services', 'taxes', 'final_total']);
        });
    }
};
