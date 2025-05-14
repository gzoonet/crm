<?php

namespace YourVendor\CrmPackage\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use YourVendor\CrmPackage\Models\Note;

/**
 * Trait HasNotes
 * @package YourVendor\CrmPackage\Traits
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

