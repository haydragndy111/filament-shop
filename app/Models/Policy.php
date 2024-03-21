<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory;

    // Policy Status
    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 2;

    protected $fillable = [
        'title',
        'status',
        'description',
    ];

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'policy_group', );
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_policy', );
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'policy_user', );
    }
}
