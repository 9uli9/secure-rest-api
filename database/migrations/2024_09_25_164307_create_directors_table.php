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
        Schema::create('directors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 128);
            $table->string('website', 256);
            $table->timestamps();
        });

        // Schema::table('directors', function (Blueprint $table) {
        //     $table->foreignId('movie_id')->constrained()->onUpdate('restrict')->onDelete('restrict');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('directors', function (Blueprint $table) {
        //     $table->dropConstrainedForeignId('movie_id');
        // });
        Schema::dropIfExists('directors');
    }
};
