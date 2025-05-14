<?php

namespace YourVendor\CrmPackage\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use YourVendor\CrmPackage\Models\Lead;
use Illuminate\Support\Facades\DB;

class ActiveLeadsByStageWidget extends ChartWidget
{
    protected static ?string $heading = 'Active Leads by Stage';

    protected static ?string $pollingInterval = '10s'; // Optional: refresh data periodically

    protected static ?int $sort = 1; // Order of the widget on the dashboard

    protected function getData(): array
    {
        $leadStages = Lead::query()
            ->select('stage', DB::raw('count(*) as count'))
            ->groupBy('stage')
            ->orderByRaw("FIELD(stage, 'New Lead', 'Contacted', 'Qualified', 'Quoted', 'Won', 'Lost')") // Ensure consistent order
            ->pluck('count', 'stage')
            ->all();

        $labels = array_keys($leadStages);
        $data = array_values($leadStages);

        // Define colors for each stage for better visualization
        // These are example colors, can be customized to match brand or DashStack inspiration
        $stageColors = [
            'New Lead' => '#6B7280',    // Gray
            'Contacted' => '#3B82F6',   // Blue
            'Qualified' => '#F59E0B',   // Amber
            'Quoted' => '#8B5CF6',      // Violet
            'Won' => '#10B981',       // Emerald
            'Lost' => '#EF4444',      // Red
        ];

        $backgroundColors = array_map(function($stage) use ($stageColors) {
            return $stageColors[$stage] ?? '#CCCCCC'; // Default color if stage not in map
        }, $labels);

        return [
            'datasets' => [
                [
                    'label' => 'Leads',
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                    // 'borderColor' => '#fff', // Optional border color
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        // Filament's ChartWidget supports 'line', 'bar', 'pie', 'doughnut', 'radar', 'polarArea'
        // For a funnel chart, 'bar' or 'doughnut' (if simplified) might be the closest built-in.
        // A true funnel chart might require a custom component or a third-party library integration.
        // For now, we'll use 'bar' as a placeholder that can be styled or replaced.
        // Or, if you have a specific chart library integrated, you might return a custom type.
        return 'bar'; // Or 'doughnut' for a different representation
    }

    // Optional: Add a description to the widget
    // public function getDescription(): ?string
    // {
    //     return 'Shows the current distribution of active leads across different pipeline stages.';
    // }
}

