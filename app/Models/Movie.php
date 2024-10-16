<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'duration',
        'rating',
        'year',
    ];

    public function customers()
{
    return $this->belongsToMany(Customer::class, 'customer_movie')->withPivot('due', 'extended')->withTimestamps();
}


    
}
