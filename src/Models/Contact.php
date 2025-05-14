<?php

namespace YourVendor\CrmPackage\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use YourVendor\CrmPackage\Traits\HasNotes;
use YourVendor\CrmPackage\Traits\HasTags; // Import the HasTags trait

class Contact extends Model
{
    use HasFactory, SoftDeletes, HasNotes, HasTags; // Use the HasTags trait

    protected $fillable = [
        'customer_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'position',
        'notes',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    // If using a factory for seeding/testing
    // protected static function newFactory()
    // {
    //     return \YourVendor\CrmPackage\Database\Factories\ContactFactory::new();
    // }
}

