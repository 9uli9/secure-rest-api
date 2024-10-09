<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerMovieTable extends Migration
{
    public function up(): void
    {
        Schema::create('customer_movie', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('movie_id')->constrained()->onDelete('cascade');
            $table->dateTime('due');
            $table->boolean('extended'); 
            $table->timestamps();
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_movie');
    }
}
