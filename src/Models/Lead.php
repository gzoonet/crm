<?php

namespace YourVendor\CrmPackage\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use YourVendor\CrmPackage\Traits\HasNotes;
use YourVendor\CrmPackage\Traits\HasTags;

class Lead extends Model
{
    use HasFactory, SoftDeletes, HasNotes, HasTags;

    protected $fillable = [
        "company_name",
        "contact_person",
        "email",
        "phone",
        "stage",
        "value",
        "probability",
        "source",
        "notes",
        // "user_id", // Uncomment if you add this to migrations
        // "customer_id", // Uncomment if you add this to migrations
    ];

    protected $casts = [
        "value" => "decimal:2",
        "probability" => "integer",
    ];

    // Define relationships here if needed
    // For example, if a lead can be assigned to a user:
    // public function assignedUser()
    // {
    //     return $this->belongsTo(\App\Models\User::class, "user_id");
    // }

    // If a lead can be converted to a customer:
    // public function convertedCustomer()
    // {
    //     return $this->belongsTo(Customer::class, "customer_id");
    // }

    // If using a factory for seeding/testing
    // protected static function newFactory()
    // {
    //     return \YourVendor\CrmPackage\Database\Factories\LeadFactory::new();
    // }
}

