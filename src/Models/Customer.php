<?php

namespace Gzoonet\Crm\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Gzoonet\Crm\Traits\HasNotes;
use Gzoonet\Crm\Traits\HasTags; // Import the HasTags trait

class Customer extends Model
{
    use HasFactory, SoftDeletes, HasNotes, HasTags; // Use the HasTags trait

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'industry',
        'notes',
        'source',
        'status',
    ];

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'related_customer_id');
    }

    // If using a factory for seeding/testing
    // protected static function newFactory()
    // {
    //     return \Gzoonet\Crm\Database\Factories\CustomerFactory::new();
    // }
}

