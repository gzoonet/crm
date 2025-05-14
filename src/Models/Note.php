<?php

namespace Gzoonet\Crm\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'body',
        'notable_id',
        'notable_type',
        'user_id',
    ];

    /**
     * Get the parent notable model (customer, contact, lead, or task).
     */
    public function notable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who created the note.
     */
    public function user(): BelongsTo
    {
        // Assuming a User model exists in the main application
        return $this->belongsTo(\App\Models\User::class);
    }

    // If using a factory for seeding/testing
    // protected static function newFactory()
    // {
    //     return \Gzoonet\Crm\Database\Factories\NoteFactory::new();
    // }
}

