<?php

namespace YourVendor\CrmPackage\Filament\Resources\ContactResource\Pages;

use YourVendor\CrmPackage\Filament\Resources\ContactResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateContact extends CreateRecord
{
    protected static string $resource = ContactResource::class;
}

