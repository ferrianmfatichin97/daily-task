<div class="space-y-6">
    @foreach ($records as $history)
        <div class="flex items-start space-x-3">
            <!-- Bulatan + Icon -->
            <div class="flex-shrink-0">
                @if($history->action === 'created')
                    <x-heroicon-o-plus-circle class="w-6 h-6 text-green-500"/>
                @elseif($history->action === 'status_changed')
                    <x-heroicon-o-arrow-path class="w-6 h-6 text-yellow-500"/>
                @elseif($history->action === 'commented')
                    <x-heroicon-o-chat-bubble-left-ellipsis class="w-6 h-6 text-blue-500"/>
                @else
                    <x-heroicon-o-information-circle class="w-6 h-6 text-gray-400"/>
                @endif
            </div>

            <!-- Isi timeline -->
            <div class="flex-1">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-3">
                    <p class="text-sm">
                        <span class="font-bold text-primary-600">{{ $history->user?->name ?? 'System' }}</span>
                        melakukan <span class="italic">{{ str_replace('_', ' ', $history->action) }}</span>
                    </p>

                    @if ($history->old_value || $history->new_value)
                        <p class="text-xs text-gray-600 mt-1">
                            <span class="line-through text-red-500">{{ $history->old_value ?? '-' }}</span>
                            →
                            <span class="text-green-600">{{ $history->new_value ?? '-' }}</span>
                        </p>
                    @endif

                    <p class="text-xs text-gray-400 mt-1">
                        {{ $history->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
        </div>
    @endforeach
</div>
