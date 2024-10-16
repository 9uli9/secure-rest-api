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
            $table->dateTime('due');
            $table->boolean('extended'); 
            $table->unsignedBigInteger('movies_id'); 
            $table->unsignedBigInteger('customer_id'); 

            $table->foreign('movies_id')->references('id')->on('cars')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('customer_id')->references('id')->on('races')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_movie');
    }
}
