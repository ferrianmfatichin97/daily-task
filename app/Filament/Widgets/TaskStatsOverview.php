<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class TaskStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();
        $query = Task::query();

        // GM dan Manager melihat statistik global
        // Staff hanya melihat statistik tugas milik sendiri
        if (! $user->hasRole(['super_admin', 'gm', 'manager'])) {
            $query->where('user_id', $user->id);
        }

        $totalTasks = (clone $query)->count();
        $completedTasks = (clone $query)->where('status', 'done')->count();
        $pendingTasks = (clone $query)->whereIn('status', ['todo', 'in_progress'])->count();
        $overdueTasks = (clone $query)->whereIn('status', ['todo', 'in_progress'])
            ->where('due_date', '<', now())
            ->count();

        return [
            Stat::make('Total Tugas', $totalTasks)
                ->description('Semua tugas yang tercatat')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('info'),
            Stat::make('Tugas Selesai', $completedTasks)
                ->description('Sudah dikerjakan')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Tugas Pending', $pendingTasks)
                ->description('Dalam pengerjaan / To Do')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Terlambat (Overdue)', $overdueTasks)
                ->description('Melewati tenggat waktu')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('danger'),
        ];
    }
}
