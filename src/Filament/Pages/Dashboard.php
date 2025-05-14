<?php

namespace Gzoonet\Crm\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $routePath = 'dashboard';

    protected static ?int $navigationSort = -2;

    public function getWidgets(): array
    {
        return [
            \Gzoonet\Crm\Filament\Widgets\ActiveLeadsByStageWidget::class,
            \Gzoonet\Crm\Filament\Widgets\TasksDueThisWeekWidget::class,
            \Gzoonet\Crm\Filament\Widgets\RecentActivityWidget::class,
            \Gzoonet\Crm\Filament\Widgets\NewCustomersThisMonthWidget::class,
            \Gzoonet\Crm\Filament\Widgets\ConversionRateWidget::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return 2;
    }

    public function getTitle(): string
    {
        return static::$title ?? __('filament-panels::pages/dashboard.title');
    }
}
