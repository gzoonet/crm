<?php

namespace YourVendor\CrmPackage\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $routePath = 'dashboard'; // Default, can be customized

    protected static ?int $navigationSort = -2; // To appear at the top

    public function getWidgets(): array
    {
        return [
            // Widgets will be added here later
        ];
    }

    public function getColumns(): int | string | array
    {
        return 2; // Default dashboard columns, can be adjusted
    }

    public function getTitle(): string
    {
        return static::$title ?? __('filament-panels::pages/dashboard.title');
    }
}

