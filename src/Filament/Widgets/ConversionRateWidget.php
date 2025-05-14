<?php

namespace Gzoonet\Crm\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Gzoonet\Crm\Models\Lead;
use Carbon\Carbon;

class ConversionRateWidget extends BaseWidget
{
    protected static ?string $heading = "Lead Conversion Rate (Last 30 Days)";

    protected static ?int $sort = 5; // Order of the widget on the dashboard

    protected static ?string $pollingInterval = "60s";

    protected function getStats(): array
    {
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays(30);

        $wonLeadsCount = Lead::query()
            ->where("stage", "Won")
            ->whereBetween("updated_at", [$startDate, $endDate]) // Assuming stage update changes updated_at
            ->count();

        $totalLeadsClosedCount = Lead::query()
            ->whereIn("stage", ["Won", "Lost"])
            ->whereBetween("updated_at", [$startDate, $endDate])
            ->count();

        $conversionRate = 0;
        if ($totalLeadsClosedCount > 0) {
            $conversionRate = ($wonLeadsCount / $totalLeadsClosedCount) * 100;
        }

        $description = sprintf("%d leads won out of %d closed", $wonLeadsCount, $totalLeadsClosedCount);
        $descriptionIcon = "heroicon-m-check-circle";
        $color = "success";

        if ($totalLeadsClosedCount === 0) {
            $description = "No leads closed in the last 30 days.";
            $descriptionIcon = "heroicon-m-x-circle";
            $color = "warning";
        } elseif ($conversionRate < 50 && $conversionRate > 0) {
            $color = "warning";
        } elseif ($conversionRate == 0 && $totalLeadsClosedCount > 0) {
             $descriptionIcon = "heroicon-m-x-circle";
             $color = "danger";
        }


        return [
            Stat::make("Conversion Rate", sprintf("%.2f%%", $conversionRate))
                ->description($description)
                ->descriptionIcon($descriptionIcon)
                ->color($color)
                // Optionally, add a chart for conversion rate over time if data is available
                // ->chart($this->getConversionChartData($startDate, $endDate))
        ];
    }

    // Placeholder for a potential chart, would require more historical data points for stages
    // protected function getConversionChartData(Carbon $startDate, Carbon $endDate): array
    // {
    //     // Example: Fetch daily conversion rates for the last 30 days
    //     // This is a simplified example and might need a more complex query or data structure
    //     $data = [];
    //     for ($i = 0; $i < 30; $i++) {
    //         $date = $startDate->copy()->addDays($i);
    //         $won = Lead::where("stage", "Won")->whereDate("updated_at", $date)->count();
    //         $totalClosed = Lead::whereIn("stage", ["Won", "Lost"])->whereDate("updated_at", $date)->count();
    //         $data[] = $totalClosed > 0 ? ($won / $totalClosed) * 100 : 0;
    //     }
    //     return $data;
    // }
}

