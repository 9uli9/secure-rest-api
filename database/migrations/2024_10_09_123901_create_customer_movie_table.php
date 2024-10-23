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
            $table->date('due');           
            $table->boolean('extended');        
            $table->unsignedBigInteger('movie_id'); 
            $table->unsignedBigInteger('customer_id'); 

            
            $table->foreign('movie_id')->references('id')->on('movies')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('customer_id')->references('id')->on('customers')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_movie');
    }
}

