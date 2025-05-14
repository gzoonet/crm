<?php

namespace YourVendor\CrmPackage\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use YourVendor\CrmPackage\Models\Customer;
use Carbon\Carbon;

class NewCustomersThisMonthWidget extends BaseWidget
{
    protected static ?string $heading = "New Customers This Month";

    protected static ?int $sort = 4; // Order of the widget on the dashboard

    protected static ?string $pollingInterval = "30s";

    protected function getStats(): array
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $newCustomersThisMonth = Customer::query()
            ->whereBetween("created_at", [$startOfMonth, $endOfMonth])
            ->count();

        // Optionally, calculate percentage change from last month
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();
        $newCustomersLastMonth = Customer::query()
            ->whereBetween("created_at", [$startOfLastMonth, $endOfLastMonth])
            ->count();

        $description = "Number of new customers acquired this month.";
        $descriptionIcon = "heroicon-m-arrow-trending-up";
        $color = "success";

        if ($newCustomersLastMonth > 0) {
            $percentageChange = (($newCustomersThisMonth - $newCustomersLastMonth) / $newCustomersLastMonth) * 100;
            if ($percentageChange > 0) {
                $description = sprintf("%.2f%% increase from last month", $percentageChange);
            } elseif ($percentageChange < 0) {
                $description = sprintf("%.2f%% decrease from last month", abs($percentageChange));
                $descriptionIcon = "heroicon-m-arrow-trending-down";
                $color = "danger";
            } else {
                $description = "No change from last month";
                $descriptionIcon = "heroicon-m-minus";
                $color = "warning";
            }
        } elseif ($newCustomersThisMonth > 0) {
            $description = "No data for last month to compare.";
        }


        return [
            Stat::make("New Customers", $newCustomersThisMonth)
                ->description($description)
                ->descriptionIcon($descriptionIcon)
                ->color($color)
                ->chart($this->getChartData($startOfMonth, $endOfMonth)), // Optional: Add a small chart
        ];
    }

    protected function getChartData(Carbon $startDate, Carbon $endDate): array
    {
        $data = Customer::query()
            ->selectRaw("DATE(created_at) as date, COUNT(*) as count")
            ->whereBetween("created_at", [$startDate, $endDate])
            ->groupBy("date")
            ->orderBy("date")
            ->pluck("count", "date")
            ->toArray();

        // Fill in missing dates with 0 count for a continuous chart
        $period = Carbon::parse($startDate)->daysUntil($endDate);
        $chartData = [];
        foreach ($period as $date) {
            $chartData[] = $data[$date->toDateString()] ?? 0;
        }
        return $chartData;
    }
}

