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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('phone');
            $table->string('email');
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            if (DB::getDriverName() != 'sqlite') {
                $table->foreignId('supplier_id')->constrained()->onDelete('restrict')->onDelete('restrict');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (DB::getDriverName() != 'sqlite') {
                $table->dropForeign(['products_supplier_id_foreign']);
            }
            $table->dropColumn('supplier_id');
        });
        Schema::dropIfExists('suppliers');
    }
};