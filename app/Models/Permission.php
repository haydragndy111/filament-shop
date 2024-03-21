<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    const FILAMENT_MATCH_ATTRIBUTE = 'name';

    protected $fillable = [
        'title',
        'model_type',
        'full_access',
        'model_id',
        'action',
        'status',
    ];

    // Models
    public const MODEL_PRODUCT = 1;
    public const MODEL_CATEGORY = 2;
    public const MODEL_BRAND = 3;
    public const MODEL_CUSTOMER = 4;
    public const MODEL_ORDER = 5;

    protected function matchModel()
    {
        return [
            self::MODEL_PRODUCT => 'Product',
            self::MODEL_CATEGORY => 'Category',
            self::MODEL_BRAND => 'Brand',
            self::MODEL_CUSTOMER => 'Customer',
            self::MODEL_ORDER => 'Order',
        ];
    }

    // Permission Type
    public const TYPE_NORMAL = 1;
    public const TYPE_ADVANCED = 2;

    // Permission Status
    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 2;

    // Action Constants
    public const ACTION_VIEW = 1;
    public const ACTION_CREATE = 2;
    public const ACTION_UPDATE = 3;
    public const ACTION_DELETE = 4;

    public function policies()
    {
        return $this->belongsToMany(Policy::class, 'permission_policy', );
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'permission_user', );
    }
}
