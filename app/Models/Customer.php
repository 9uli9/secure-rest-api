<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'address', 'dob', 'phone'
    ];

    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'customer_movie');
    }
}
