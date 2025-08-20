<?php

namespace App\Filament\Resources\TaskResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class HistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'histories';

    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->content(function (Collection $records) {
                return view('filament.history-timeline', [
                    'records' => $records,
                ]);
            })
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}
