<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Director extends Model
{

    public $timestamps = false;
    
    use HasFactory;

    protected $fillable = [
        'name', 'website'
    ];


    public function movies()
    {
        return $this->hasMany(Movie::class);
    }
}
