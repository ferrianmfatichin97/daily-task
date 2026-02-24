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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

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
            Forms\Components\Section::make('Informasi Utama')
                ->description('Detail tugas yang harus dikerjakan.')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Judul Tugas')
                        ->placeholder('Contoh: Laporan Mingguan Operasional')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('description')
                        ->label('Deskripsi Detail')
                        ->rows(4)
                        ->placeholder('Jelaskan rincian tugas di sini...')
                        ->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Atribut & Penugasan')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->label('Status Saat Ini')
                        ->options([
                            'todo' => 'To Do',
                            'in_progress' => 'In Progress',
                            'done' => 'Done',
                        ])
                        ->default('todo')
                        ->required()
                        ->native(false)
                        ->prefixIcon('heroicon-m-tag')
                        ->columnSpan(1),

                    Forms\Components\DatePicker::make('due_date')
                        ->label('Tenggat Waktu')
                        ->required()
                        ->prefixIcon('heroicon-m-calendar')
                        ->columnSpan(1),

                    Forms\Components\Select::make('user_id')
                        ->label('Ditugaskan Ke')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->prefixIcon('heroicon-m-user')
                        ->default(fn() => auth()->id())
                        ->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    # ============================
    # TABLE
    # ============================
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Tugas')
                    ->searchable()
                    ->sortable()
                    ->description(fn(Task $record) => \Illuminate\Support\Str::limit($record->description, 50))
                    ->wrap(),

                Tables\Columns\SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'todo' => 'To Do',
                        'in_progress' => 'In Progress',
                        'done' => 'Done',
                    ])
                    ->selectablePlaceholder(false)
                    ->sortable(),

                Tables\Columns\TextColumn::make('due_date')
                    ->label('Tenggat')
                    ->date('d M Y')
                    ->sortable()
                    ->color(fn(Task $record) => $record->due_date < now() && $record->status !== 'done' ? 'danger' : 'gray')
                    ->icon(fn(Task $record) => $record->due_date < now() && $record->status !== 'done' ? 'heroicon-m-exclamation-triangle' : null),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('PIC')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true)
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
                    ->label('Karyawan')
                    ->relationship('user', 'name')
                    ->preload()

            ], layout: Tables\Enums\FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn() => auth()->user()->hasRole(['super_admin', 'gm', 'manager'])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn() => auth()->user()->hasRole(['super_admin', 'gm', 'manager'])),
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

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        // GM dan Manager bisa melihat semua data divisi operasional
        // Super Admin selalu bisa melihat semua
        if ($user->hasRole(['super_admin', 'gm', 'manager'])) {
            return $query;
        }

        // Staff hanya bisa melihat data miliknya sendiri
        return $query->where('user_id', $user->id);
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
