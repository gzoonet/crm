<?php

namespace YourVendor\CrmPackage\Filament\Resources\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use YourVendor\CrmPackage\Models\Note;

class NotesRelationManager extends RelationManager
{
    protected static string $relationship = 'notes';

    protected static ?string $recordTitleAttribute = 'created_at'; // Or another suitable attribute

    public static function getRecordTitle(Model $record): ?string
    {
        return 'Note by ' . ($record->user?->name ?? 'System') . ' on ' . $record->created_at->format('Y-m-d H:i');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\RichEditor::make('body')
                    ->label('Note Content')
                    ->required()
                    ->columnSpanFull(),
                // user_id will be set automatically based on the logged-in user
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('body')
                    ->label('Note')
                    ->limit(100)
                    ->html(),
                Tables\Columns\TextColumn::make('user.name') // Assumes User model has a 'name' attribute
                    ->label('Created By')
                    ->default('System') // Fallback if user is not available
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created On')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                 Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();
                        return $data;
                    }),
            ]);
    }
}

