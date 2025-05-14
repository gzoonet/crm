<?php

namespace Gzoonet\Crm\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Gzoonet\Crm\Traits\HasNotes;
use Gzoonet\Crm\Traits\HasTags; // Import the HasTags trait

class Task extends Model
{
    use HasFactory, SoftDeletes, HasNotes, HasTags; // Use the HasTags trait

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'status',
        'assigned_to_user_id',
        'related_customer_id',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function assignedToUser(): BelongsTo
    {
        // Assuming a User model exists in the main application
        // If the User model is part of this package, adjust the namespace accordingly.
        return $this->belongsTo(\App\Models\User::class, 'assigned_to_user_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'related_customer_id');
    }

    // If using a factory for seeding/testing
    // protected static function newFactory()
    // {
    //     return \Gzoonet\Crm\Database\Factories\TaskFactory::new();
    // }
}

