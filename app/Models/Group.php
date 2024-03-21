<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    const FILAMENT_MATCH_ATTRIBUTE = 'title';

    protected $fillable = [
        'title',
    ];

    public function policies()
    {
        return $this->belongsToMany(Policy::class, 'policy_group', );
    }

}
