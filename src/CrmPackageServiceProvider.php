<?php

namespace Gzoonet\Crm;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use YourVendor\CrmPackage\Filament\Pages\Dashboard;
use YourVendor\CrmPackage\Filament\Resources\CustomerResource;
use YourVendor\CrmPackage\Filament\Resources\ContactResource;
use YourVendor\CrmPackage\Filament\Resources\LeadResource;
use YourVendor\CrmPackage\Filament\Resources\TaskResource;
use YourVendor\CrmPackage\Filament\Resources\TagResource;
use YourVendor\CrmPackage\Filament\Widgets\ActiveLeadsByStageWidget;
use YourVendor\CrmPackage\Filament\Widgets\TasksDueThisWeekWidget;
use YourVendor\CrmPackage\Filament\Widgets\RecentActivityWidget;
use YourVendor\CrmPackage\Filament\Widgets\NewCustomersThisMonthWidget;
use YourVendor\CrmPackage\Filament\Widgets\ConversionRateWidget;

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
        // Filament auto-discovers resources, pages, and widgets if they are in the correct namespace
        // and the service provider is registered. However, explicitly listing them here can be useful
        // for clarity or if auto-discovery needs a hint, though usually not required for Filament 3.

        // Ensure Filament is loaded before trying to access its services
        if (class_exists(\Filament\Filament::class)) {
            // This is usually handled by Filament auto-discovery based on your composer.json psr-4 autoloading
            // and the conventional directory structure (app/Filament/Resources, app/Filament/Pages, app/Filament/Widgets).
            // For a package, ensure your `FilamentServiceProvider` (if you have one, or this one) correctly registers them
            // or that Filament can find them via the PSR-4 namespace.

            // Example of how you might explicitly register if needed (usually not for Filament 3+ with standard structure)
            // \Filament\Facades\Filament::registerResources([
            //     CustomerResource::class,
            //     ContactResource::class,
            //     LeadResource::class,
            //     TaskResource::class,
            //     TagResource::class,
            // ]);
            // \Filament\Facades\Filament::registerPages([
            //     Dashboard::class,
            // ]);
            // \Filament\Facades\Filament::registerWidgets([
            //     ActiveLeadsByStageWidget::class,
            //     TasksDueThisWeekWidget::class,
            //     RecentActivityWidget::class,
            //     NewCustomersThisMonthWidget::class,
            //     ConversionRateWidget::class,
            // ]);
        }
    }
}

