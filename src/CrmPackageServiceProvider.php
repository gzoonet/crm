<?php

namespace Gzoonet\Crm;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Gzoonet\Crm\Filament\Pages\Dashboard;
use Gzoonet\Crm\Filament\Resources\CustomerResource;
use Gzoonet\Crm\Filament\Resources\ContactResource;
use Gzoonet\Crm\Filament\Resources\LeadResource;
use Gzoonet\Crm\Filament\Resources\TaskResource;
use Gzoonet\Crm\Filament\Resources\TagResource;
use Gzoonet\Crm\Filament\Widgets\ActiveLeadsByStageWidget;
use Gzoonet\Crm\Filament\Widgets\TasksDueThisWeekWidget;
use Gzoonet\Crm\Filament\Widgets\RecentActivityWidget;
use Gzoonet\Crm\Filament\Widgets\NewCustomersThisMonthWidget;
use Gzoonet\Crm\Filament\Widgets\ConversionRateWidget;

class CrmPackageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name("gzoonet-crm") // Match the composer.json name, but kebab-case
            ->hasConfigFile() // If you have a config file, e.g., config/crm-package.php
            // ->hasViews() // If you have views
            ->hasMigrations([
                "create_customers_table",
                "create_contacts_table",
                "create_tasks_table",
                "create_notes_table",
                "create_tags_table",
                "create_taggables_table",
                "create_leads_table",
            ]) // Add all your migration files here without the timestamp
            // ->hasCommand(YourCommand::class) // If you have Artisan commands
            ;
    }

    public function packageRegistered(): void
    {
        // You can bind things to the container here, if needed.
    }

public function packageBooted(): void
{
    if (class_exists(\Filament\Filament::class)) {
        \Filament\Facades\Filament::registerNavigationGroups([
            'CRM',
        ]);

        \Filament\Facades\Filament::registerResources([
            \Gzoonet\Crm\Filament\Resources\CustomerResource::class,
            \Gzoonet\Crm\Filament\Resources\ContactResource::class,
            \Gzoonet\Crm\Filament\Resources\LeadResource::class,
            \Gzoonet\Crm\Filament\Resources\TaskResource::class,
            \Gzoonet\Crm\Filament\Resources\TagResource::class,
        ]);

        \Filament\Facades\Filament::registerPages([
            \Gzoonet\Crm\Filament\Pages\Dashboard::class,
        ]);

        \Filament\Facades\Filament::registerWidgets([
            \Gzoonet\Crm\Filament\Widgets\ActiveLeadsByStageWidget::class,
            \Gzoonet\Crm\Filament\Widgets\TasksDueThisWeekWidget::class,
            \Gzoonet\Crm\Filament\Widgets\RecentActivityWidget::class,
            \Gzoonet\Crm\Filament\Widgets\NewCustomersThisMonthWidget::class,
            \Gzoonet\Crm\Filament\Widgets\ConversionRateWidget::class,
        ]);
    }
}

}

