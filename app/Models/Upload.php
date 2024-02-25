<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Upload extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'extension', 'path'
    ];

    protected $appends = ['updated_at_human', 'created_at_human'];

    /**
     * Relations
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Attributes
     */
    protected function updatedAtHuman(): Attribute
    {
        return new Attribute(
            get: fn () => Carbon::parse($this->updated_at)->setTimezone('Europe/London')->format('jS M Y \a\t H:i'),
        );
    }

    protected function createdAtHuman(): Attribute
    {
        return new Attribute(
            get: fn () => Carbon::parse($this->created_at)->setTimezone('Europe/London')->format('jS M Y \a\t H:i'),
        );
    }
}
