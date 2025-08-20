<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\TaskHistory;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;

class TaskHistories extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Manajemen Tugas';
    protected static ?string $title = 'Task Histories';
    protected static string $view = 'filament.pages.task-histories';

    /**
     * Hanya admin yang bisa melihat page ini
     */
    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();

        return $user && $user->email === 'admin@bankdptaspen.co.id';
        
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(TaskHistory::query()->latest()->with(['task', 'user']))
            ->columns([
                Tables\Columns\TextColumn::make('action')
                    ->label('Action')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('old_value')
                    ->label('Old Value'),

                Tables\Columns\TextColumn::make('new_value')
                    ->label('New Value'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('By User')
                    ->badge(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('When')
                    ->since()
                    ->sortable(),
            ])
            ->groups([
                Tables\Grouping\Group::make('task.title')
                    ->label('Task')
                    ->collapsible(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50]);
    }
}
