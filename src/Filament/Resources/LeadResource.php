<?php

namespace Gzoonet\Crm\Filament\Resources;

use Gzoonet\Crm\Filament\Resources\LeadResource\Pages;
use Gzoonet\Crm\Filament\Resources\RelationManagers\NotesRelationManager;
use Gzoonet\Crm\Models\Lead;
use Gzoonet\Crm\Models\Tag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TagsInput;
use Filament\Tables\Filters\SelectFilter;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static ?string $navigationIcon = 'heroicon-o-funnel';

    protected static ?string $navigationGroup = 'CRM';

    protected static ?int $navigationSort = 3; // Adjust as needed

    public static function getLeadStages(): array
    {
        // In a real application, these might come from a config file or a database table
        return [
            'New Lead' => 'New Lead',
            'Contacted' => 'Contacted',
            'Qualified' => 'Qualified',
            'Quoted' => 'Quoted',
            'Won' => 'Won',
            'Lost' => 'Lost',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('company_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('contact_person')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\Select::make('stage')
                    ->options(self::getLeadStages())
                    ->required()
                    ->default('New Lead')
                    ->searchable(),
                Forms\Components\TextInput::make('value')
                    ->numeric()
                    ->prefix('$') // Or your currency symbol
                    ->maxValue(42949672.95),
                Forms\Components\TextInput::make('probability')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->suffix('%'),
                Forms\Components\TextInput::make('source')
                    ->maxLength(255),
                Forms\Components\Textarea::make('notes') // Main notes for the lead
                    ->columnSpanFull(),
Forms\Components\CheckboxList::make('tags')
    ->label('Tags')
    ->relationship('tags', 'name')
    ->columns(2)
    ->searchable()
    ->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('contact_person')
                    ->searchable(),
                Tables\Columns\TextColumn::make('stage')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'New Lead' => 'gray',
                        'Contacted' => 'info',
                        'Qualified' => 'warning',
                        'Quoted' => 'primary',
                        'Won' => 'success',
                        'Lost' => 'danger',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->money('usd') // Or your currency
                    ->sortable(),
                Tables\Columns\TextColumn::make('probability')
                    ->formatStateUsing(fn (?string $state): string => $state ? "{$state}%" : '-')
                    ->sortable(),
                Tables\Columns\TextColumn::make('source')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tags.name')
                    ->label('Tags')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                SelectFilter::make('stage')
                    ->options(self::getLeadStages())
                    ->multiple()
                    ->label('Stage'),
                SelectFilter::make('tags')
                    ->relationship('tags', 'name')
                    ->multiple()
                    ->preload()
                    ->label('Tags'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            NotesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLead::route('/create'),
            'view' => Pages\ViewLead::route('/{record}'),
            'edit' => Pages\EditLead::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}

