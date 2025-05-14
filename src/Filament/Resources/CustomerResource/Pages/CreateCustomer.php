<?php

namespace YourVendor\CrmPackage\Filament\Resources\CustomerResource\Pages;

use YourVendor\CrmPackage\Filament\Resources\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;
}

