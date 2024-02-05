<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Activity extends Model
{
    use HasFactory;

    const activityMap = [
        'LOGIN' => 'Login Success'
    ];

    /**
     * Relations
     */
    public function actionerable(): MorphTo
    {
        return $this->morphTo();
    }

    public function assetable(): MorphTo
    {
        return $this->morphTo();
    }
}
