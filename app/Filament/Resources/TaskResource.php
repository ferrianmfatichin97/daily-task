<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\TaskResource\RelationManagers\HistoriesRelationManager;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';
    protected static ?string $navigationLabel = 'Tugas Harian';
    protected static ?string $pluralLabel = 'Tugas Harian';
    protected static ?string $navigationGroup = 'Manajemen Tugas';

    # ============================
    # FORM
    # ============================
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->label('Judul Tugas')
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),

            Forms\Components\Textarea::make('description')
                ->label('Deskripsi')
                ->rows(4)
                ->columnSpanFull(),

            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'todo' => 'To Do',
                    'in_progress' => 'In Progress',
                    'done' => 'Done',
                ])
                ->default('todo')
                ->required()
                ->columnSpan(1),

            Forms\Components\DatePicker::make('due_date')
                ->label('Tenggat Waktu')
                ->required()
                ->columnSpan(1),

            Forms\Components\Select::make('user_id')
                ->label('Dibuat Oleh')
                ->relationship('user', 'name')
                ->searchable()
                ->preload()
                ->required()
                ->columnSpan(2),
        ])->columns(2);
    }

    # ============================
    # TABLE
    # ============================
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable()
                    ->colors([
                        'danger' => 'todo',         // merah
                        'warning' => 'in_progress', // kuning
                        'success' => 'done',        // hijau
                    ])
                    ->formatStateUsing(fn($state) => match ($state) {
                        'todo' => 'To Do',
                        'in_progress' => 'In Progress',
                        'done' => 'Done',
                        default => ucfirst($state),
                    }),


                Tables\Columns\TextColumn::make('due_date')
                    ->label('Tenggat')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Dibuat Oleh')
                    ->badge()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'todo' => 'To Do',
                        'in_progress' => 'In Progress',
                        'done' => 'Done',
                    ]),

                Tables\Filters\SelectFilter::make('user')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->preload()

            ], layout: Tables\Enums\FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn() => auth()->user()?->email === 'admin@bankdptaspen.co.id'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()?->email === 'admin@bankdptaspen.co.id'),
                ]),
            ])

            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Grid::make(3)
                    ->schema([
                        Infolists\Components\Group::make([
                            Infolists\Components\TextEntry::make('id')->label('Ticket ID'),
                            Infolists\Components\TextEntry::make('title')->label('Judul'),
                            // Infolists\Components\TextEntry::make('project')->label('Project'),
                        ])->columnSpan(1),

                        Infolists\Components\Group::make([
                            Infolists\Components\TextEntry::make('status')
                                ->badge()
                                ->color(fn($state) => match ($state) {
                                    'todo' => 'gray',
                                    'in_progress' => 'warning',
                                    'done' => 'success',
                                    default => 'secondary',
                                }),
                            Infolists\Components\TextEntry::make('user.name')->label('Assigned To')->badge(),
                            // Infolists\Components\TextEntry::make('creator.name')->label('Created By'),
                            Infolists\Components\TextEntry::make('due_date')->date(),
                        ])->columnSpan(1),

                        Infolists\Components\Group::make([
                            Infolists\Components\TextEntry::make('created_at')->dateTime(),
                            Infolists\Components\TextEntry::make('updated_at')->dateTime(),
                        ])->columnSpan(1),
                    ]),

                Infolists\Components\Section::make('Description')
                    ->schema([
                        Infolists\Components\TextEntry::make('description')
                            ->markdown()
                            ->placeholder('-'),
                    ]),



                Infolists\Components\Section::make('Status History')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('histories')
                            ->schema([
                                Infolists\Components\TextEntry::make('action')->badge(),
                                Infolists\Components\TextEntry::make('old_value')->label('Sebelumnya'),
                                Infolists\Components\TextEntry::make('new_value')->label('Sesudahnya'),
                                Infolists\Components\TextEntry::make('user.name')->label('Oleh')->badge(),
                                Infolists\Components\TextEntry::make('created_at')->since()->label('Waktu'),
                            ])
                            ->columns(5),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            HistoriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
