<?php

namespace YourVendor\CrmPackage\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use YourVendor\CrmPackage\Models\Task;
use Carbon\Carbon;
use Filament\Tables;

class TasksDueThisWeekWidget extends BaseWidget
{
    protected static ?string $heading = 'Tasks Due This Week';

    protected int | string | array $columnSpan = 'full'; // Or '1' / '2' depending on layout preference

    protected static ?int $sort = 2; // Order of the widget on the dashboard

    protected function getTableQuery(): Builder
    {
        return Task::query()
            ->whereBetween('due_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->where('status', '!=', 'completed') // Exclude completed tasks
            ->orderBy('due_date', 'asc');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('title')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('due_date')
                ->date()
                ->sortable(),
            Tables\Columns\TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'open' => 'warning',
                    'in_progress' => 'info',
                    'cancelled' => 'danger',
                    default => 'gray',
                })
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('assignedToUser.name')
                ->label('Assigned To')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('customer.name')
                ->label('Related Customer')
                ->searchable()
                ->sortable(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('view')
                ->url(fn (Task $record): string => \YourVendor\CrmPackage\Filament\Resources\TaskResource::getUrl('view', ['record' => $record]))
                ->icon('heroicon-o-eye'),
        ];
    }

    // Optional: Disable pagination if you want to show all tasks due this week
    // protected function isTablePaginationEnabled(): bool
    // {
    //     return false;
    // }

    // Optional: Customize empty state
    // protected function getTableEmptyStateHeading(): ?string
    // {
    //    return 'No tasks due this week';
    // }
}

