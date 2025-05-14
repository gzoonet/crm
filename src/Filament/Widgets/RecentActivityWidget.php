<?php

namespace Gzoonet\Crm\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Gzoonet\Crm\Models\Customer;
use Gzoonet\Crm\Models\Contact;
use Gzoonet\Crm\Models\Lead;
use Gzoonet\Crm\Models\Task;
use Filament\Tables;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
class RecentActivityWidget extends BaseWidget
{
    protected static ?string $heading = 'Recent Activity';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 3;

    protected static ?string $pollingInterval = '15s';

    public function getTableQuery(): Builder
    {
        $customers = Customer::query()
            ->selectRaw("'Customer created' as activity_type, name as subject, created_at, id, 'Customer' as model_type")
            ->where("created_at", ">=", now()->subDays(7));

        $contacts = Contact::query()
            ->selectRaw("'Contact created' as activity_type, CONCAT(first_name, ' ', last_name) as subject, created_at, id, 'Contact' as model_type")
            ->where("created_at", ">=", now()->subDays(7));

        $leads = Lead::query()
            ->selectRaw("'Lead created' as activity_type, company_name as subject, created_at, id, 'Lead' as model_type")
            ->where("created_at", ">=", now()->subDays(7));

        $leadUpdates = Lead::query()
            ->selectRaw("CONCAT('Lead updated to ', stage) as activity_type, company_name as subject, updated_at as created_at, id, 'Lead' as model_type")
            ->where("updated_at", ">=", now()->subDays(7))
            ->whereColumn("created_at", "!=", "updated_at");

        $tasks = Task::query()
            ->selectRaw("'Task created' as activity_type, title as subject, created_at, id, 'Task' as model_type")
            ->where("created_at", ">=", now()->subDays(7));

        $taskUpdates = Task::query()
            ->selectRaw("CONCAT('Task status: ', status) as activity_type, title as subject, updated_at as created_at, id, 'Task' as model_type")
            ->where("updated_at", ">=", now()->subDays(7))
            ->whereColumn("created_at", "!=", "updated_at");

        // Build the union
        $union = $leads
            ->unionAll($leadUpdates)
            ->unionAll($customers)
            ->unionAll($contacts)
            ->unionAll($tasks)
            ->unionAll($taskUpdates);

        // Wrap in subquery so MySQL can sort it
        return DB::query()
            ->fromSub($union, 'recent_activity')
            ->orderBy('created_at', 'desc');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('activity_type')
                ->label('Activity')
                ->html()
                ->formatStateUsing(function (Model $record) {
                    $subject = $record->subject;
                    $activity = $record->activity_type;
                    $url = '#';

                    switch ($record->model_type) {
                        case 'Customer':
                            $url = \Gzoonet\Crm\Filament\Resources\CustomerResource::getUrl('view', ['record' => $record->id]);
                            break;
                        case 'Contact':
                            $url = \Gzoonet\Crm\Filament\Resources\ContactResource::getUrl('view', ['record' => $record->id]);
                            break;
                        case 'Lead':
                            $url = \Gzoonet\Crm\Filament\Resources\LeadResource::getUrl('view', ['record' => $record->id]);
                            break;
                        case 'Task':
                            $url = \Gzoonet\Crm\Filament\Resources\TaskResource::getUrl('view', ['record' => $record->id]);
                            break;
                    }

                    return new HtmlString("<strong>" . e($activity) . "</strong>: <a href='" . e($url) . "' class='text-primary-600 hover:underline'>" . e(Str::limit($subject, 30)) . "</a>");
                }),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Time')
                ->since()
                ->sortable(),
        ];
    }

    protected function isTablePaginationEnabled(): bool 
    {
        return true;
    }

    public function getDefaultTableRecordsPerPageSelectOption(): int
    {
        return 5;
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No recent activity in the last 7 days';
    }
}
