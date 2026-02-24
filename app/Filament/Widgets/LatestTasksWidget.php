<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class LatestTasksWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $user = Auth::user();
        
        return $table
            ->query(
                Task::query()
                    ->latest()
                    ->when(
                        ! $user->hasRole(['super_admin', 'gm', 'manager']),
                        fn ($query) => $query->where('user_id', $user->id)
                    )
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Tugas')
                    ->searchable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'todo' => 'To Do',
                        'in_progress' => 'In Progress',
                        'done' => 'Done',
                    ])
                    ->selectablePlaceholder(false),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('PIC')
                    ->badge(),
                Tables\Columns\TextColumn::make('due_date')
                    ->label('Tenggat')
                    ->date(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn (Task $record): string => "/admin/tasks/{$record->id}")
                    ->icon('heroicon-m-eye'),
            ]);
    }

    public static function canView(): bool
    {
        // Manager dan GM bisa melihat monitoring semua staff
        return auth()->user()->hasRole(['super_admin', 'gm', 'manager', 'staff']); // Biarkan semua bisa lihat, tapi query dibatasi di table()
    }
}
