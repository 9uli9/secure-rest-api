<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('duration');
            $table->char('rating', 1);
            $table->string('year');
            $table->timestamps();
             
        });

        Schema::table('movies', function (Blueprint $table) {
            $table->foreignId('director_id')->constrained()->onUpdate('restrict')->onDelete('restrict');
        });
    }
    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropConstrainedForeignId('director_id');
        });
        Schema::dropIfExists('movies');
    }
};