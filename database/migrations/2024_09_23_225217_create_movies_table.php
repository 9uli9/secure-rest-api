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
            $table->char('rating', 1); // Consider using a more appropriate type for ratings
            $table->string('year'); // Use an integer type if it's only a year
            $table->foreignId('director_id')->constrained()->onUpdate('cascade')->onDelete('cascade'); // Foreign key definition
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movies'); // This will drop the table along with its foreign keys
    }
};
