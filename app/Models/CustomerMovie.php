<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerMovie extends Model
{
    use HasFactory;

    protected $fillable = [
        'due', 'extended'
    ];
}
