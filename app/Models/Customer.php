<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    const FILAMENT_MATCH_ATTRIBUTE = 'email';

    protected $fillable = [
        'name', 'email', 'phone', 'date_of_birth', 'address', 'zip_code', 'city'
    ];

}
