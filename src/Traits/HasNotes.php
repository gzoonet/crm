<?php

namespace Gzoonet\Crm\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Gzoonet\Crm\Models\Note;

/**
 * Trait HasNotes
 * @package Gzoonet\Crm\Traits
 */
trait HasNotes
{
    /**
     * Get all of the model's notes.
     */
    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'notable');
    }
}

