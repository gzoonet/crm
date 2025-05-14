<?php

namespace YourVendor\CrmPackage\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use YourVendor\CrmPackage\Models\Customer;
use YourVendor\CrmPackage\Models\Contact;
use YourVendor\CrmPackage\Models\Lead;
use YourVendor\CrmPackage\Models\Task;
use YourVendor\CrmPackage\Models\Note;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class RecentActivityWidget extends BaseWidget
{
    protected static ?string $heading = "Recent Activity";

    protected int | string | array $columnSpan = "full";

    protected static ?int $sort = 3;

    protected static ?string $pollingInterval = "15s";

    protected function getTableQuery(): Builder
    {
        // This query is a bit complex as it tries to union different types of activities.
        // A more robust solution for very high-traffic CRMs might involve a dedicated activity log table.

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
            ->whereColumn("created_at", "!=", "updated_at"); // Only actual updates, not creation

        $tasks = Task::query()
            ->selectRaw("'Task created' as activity_type, title as subject, created_at, id, 'Task' as model_type")
            ->where("created_at", ">=", now()->subDays(7));
        
        $taskUpdates = Task::query()
            ->selectRaw("CONCAT('Task status: ', status) as activity_type, title as subject, updated_at as created_at, id, 'Task' as model_type")
            ->where("updated_at", ">=", now()->subDays(7))
            ->whereColumn("created_at", "!=", "updated_at");

        $notes = Note::query()
            ->with("noteable") // Eager load the related model
            ->selectRaw("'Note added' as activity_type, LEFT(content, 50) as subject, created_at, id, 'Note' as model_type, noteable_type, noteable_id")
            ->where("created_at", ">=", now()->subDays(7));

        // Union the queries
        // The order of selectRaw columns must be the same for union to work.
        return $leads
            ->union($leadUpdates)
            ->union($customers)
            ->union($contacts)
            ->union($tasks)
            ->union($taskUpdates)
            ->union($notes) // Notes query needs to match column structure or be handled differently
            ->orderBy("created_at", "desc");
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make("activity_type")
                ->label("Activity")
                ->html()
                ->formatStateUsing(function (Model $record) {
                    $subject = $record->subject;
                    $activity = $record->activity_type;
                    $url = "#"; // Default URL

                    switch ($record->model_type) {
                        case "Customer":
                            $url = \YourVendor\CrmPackage\Filament\Resources\CustomerResource::getUrl("view", ["record" => $record->id]);
                            break;
                        case "Contact":
                            $url = \YourVendor\CrmPackage\Filament\Resources\ContactResource::getUrl("view", ["record" => $record->id]);
                            break;
                        case "Lead":
                            $url = \YourVendor\CrmPackage\Filament\Resources\LeadResource::getUrl("view", ["record" => $record->id]);
                            break;
                        case "Task":
                            $url = \YourVendor\CrmPackage\Filament\Resources\TaskResource::getUrl("view", ["record" => $record->id]);
                            break;
                        case "Note":
                            // For notes, link to the parent record (Customer, Lead, etc.)
                            if ($record->noteable_type && $record->noteable_id) {
                                $noteableType = class_basename($record->noteable_type);
                                switch ($noteableType) {
                                    case "Customer":
                                        $url = \YourVendor\CrmPackage\Filament\Resources\CustomerResource::getUrl("view", ["record" => $record->noteable_id]);
                                        $subject = "Note on: " . ($record->noteable->name ?? 'N/A');
                                        break;
                                    case "Contact":
                                        $url = \YourVendor\CrmPackage\Filament\Resources\ContactResource::getUrl("view", ["record" => $record->noteable_id]);
                                        $subject = "Note on: " . ($record->noteable->first_name ?? 'N/A') . " " . ($record->noteable->last_name ?? '');
                                        break;
                                    case "Lead":
                                        $url = \YourVendor\CrmPackage\Filament\Resources\LeadResource::getUrl("view", ["record" => $record->noteable_id]);
                                        $subject = "Note on: " . ($record->noteable->company_name ?? 'N/A');
                                        break;
                                    case "Task":
                                        $url = \YourVendor\CrmPackage\Filament\Resources\TaskResource::getUrl("view", ["record" => $record->noteable_id]);
                                        $subject = "Note on: " . ($record->noteable->title ?? 'N/A');
                                        break;
                                }
                            }
                            $activity = "Note added to " . ($record->noteable->name ?? $record->noteable->company_name ?? $record->noteable->title ?? 'record');
                            $subject = new HtmlString(e(Str::limit($record->subject, 30)) . "... <em class='text-xs text-gray-500'>({$noteableType})</em>");
                            break;
                    }
                    return new HtmlString("<strong>" . e($activity) . "</strong>: <a href='" . e($url) . "' class='text-primary-600 hover:underline'>" . e($subject) . "</a>");
                }),
            Tables\Columns\TextColumn::make("created_at")
                ->label("Time")
                ->since()
                ->sortable(),
        ];
    }

    protected function isTablePaginationEnabled(): bool
    {
        return true; // Enable pagination for potentially long lists
    }

    protected function getDefaultTableRecordsPerPageSelectOption(): int
    {
        return 5; // Show fewer records by default
    }

    protected function getTableEmptyStateHeading(): ?string
    {
       return "No recent activity in the last 7 days";
    }
}

