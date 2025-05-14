<?php

namespace YourVendor\CrmPackage\Filament\Resources\TaskResource\Pages;

use YourVendor\CrmPackage\Filament\Resources\TaskResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;
}

