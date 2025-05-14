<?php

namespace Gzoonet\Crm;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Filament\Facades\Filament;
use Gzoonet\Crm\Filament\Resources\CustomerResource;
use Gzoonet\Crm\Filament\Resources\ContactResource;
use Gzoonet\Crm\Filament\Resources\LeadResource;
use Gzoonet\Crm\Filament\Resources\TaskResource;
use Gzoonet\Crm\Filament\Resources\TagResource;
use Gzoonet\Crm\Filament\Pages\Dashboard;
use Gzoonet\Crm\Filament\Widgets\ActiveLeadsByStageWidget;
use Gzoonet\Crm\Filament\Widgets\TasksDueThisWeekWidget;
use Gzoonet\Crm\Filament\Widgets\RecentActivityWidget;
use Gzoonet\Crm\Filament\Widgets\NewCustomersThisMonthWidget;
use Gzoonet\Crm\Filament\Widgets\ConversionRateWidget;

class CrmPackageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name("gzoonet-crm")
            ->hasConfigFile()
            ->hasMigrations([
                "create_customers_table",
                "create_contacts_table",
                "create_tasks_table",
                "create_notes_table",
                "create_tags_table",
                "create_taggables_table",
                "create_leads_table",
            ]);
    }

    public function packageRegistered(): void
    {
        // You can bind things to the container here, if needed.
    }

    public function packageBooted(): void
    {
        if (class_exists(Filament::class)) {
            Filament::registerNavigationGroups([
                'CRM',
            ]);

            Filament::registerResources([
                CustomerResource::class,
                ContactResource::class,
                LeadResource::class,
                TaskResource::class,
                TagResource::class,
            ]);

            Filament::registerPages([
                Dashboard::class,
            ]);

            Filament::registerWidgets([
                ActiveLeadsByStageWidget::class,
                TasksDueThisWeekWidget::class,
                RecentActivityWidget::class,
                NewCustomersThisMonthWidget::class,
                ConversionRateWidget::class,
            ]);
        }
    }
}
