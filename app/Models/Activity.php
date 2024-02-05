<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Activity extends Model
{
    use HasFactory;

    protected $casts = [
        'meta_data' => 'array',
    ];

    const activityMap = [
        'LOGIN' => 'Login Success',
        'REGISTER' => 'User Registered'
    ];

    /**
     * Relations
     */
    public function actionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function assetable(): MorphTo
    {
        return $this->morphTo();
    }
}
