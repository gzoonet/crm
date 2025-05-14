<?php

namespace YourVendor\CrmPackage\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });

        static::updating(function ($tag) {
            if ($tag->isDirty('name') && empty($tag->getOriginal('slug'))) {
                 $tag->slug = Str::slug($tag->name);
            }
        });
    }

    // Define polymorphic relationships to taggable models (Customer, Contact, Task, Lead)
    public function customers()
    {
        return $this->morphedByMany(Customer::class, 'taggable');
    }

    public function contacts()
    {
        return $this->morphedByMany(Contact::class, 'taggable');
    }

    public function tasks()
    {
        return $this->morphedByMany(Task::class, 'taggable');
    }

    public function leads()
    {
        return $this->morphedByMany(Lead::class, 'taggable');
    }

    // If using a factory for seeding/testing
    // protected static function newFactory()
    // {
    //     return \YourVendor\CrmPackage\Database\Factories\TagFactory::new();
    // }
}

