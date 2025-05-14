<?php

namespace YourVendor\CrmPackage\Filament\Resources\LeadResource\Pages;

use YourVendor\CrmPackage\Filament\Resources\LeadResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLead extends CreateRecord
{
    protected static string $resource = LeadResource::class;
}

