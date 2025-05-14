<?php

namespace YourVendor\CrmPackage\Filament\Resources\TagResource\Pages;

use YourVendor\CrmPackage\Filament\Resources\TagResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTag extends CreateRecord
{
    protected static string $resource = TagResource::class;
}

