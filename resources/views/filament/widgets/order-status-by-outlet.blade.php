{{-- resources/views/filament/widgets/order-status-by-outlet.blade.php --}}
<x-filament::widget>
    <x-filament::section>
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                Order Status
            </h2>
            
            <div class="w-40">
                <select
                    id="outlet-filter"
                    wire:model.live="outletId"
                    wire:change="filterByOutlet($event.target.value)"
                    class="block w-full py-1.5 px-3 text-sm border-gray-300 rounded-lg focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
                    <option value="all">All Outlets</option>
                    @foreach($this->getOutlets() as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <!-- Get all stats from widget -->
        @php
            $allStats = $this->getStats();
        @endphp
        
        <!-- First row -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
            <!-- First Card: New Orders -->
            @php
                $newOrderStat = null;
                foreach ($allStats as $s) {
                    if (strpos($s->getLabel(), 'New') !== false) {
                        $newOrderStat = $s;
                        break;
                    }
                }
            @endphp
            
            @if($newOrderStat)
            <div style="border-left: 4px solid #3b82f6;" class="bg-white dark:bg-gray-800 rounded-lg p-4 relative shadow-sm">
                <div>
                    <p style="color: #3b82f6;" class="text-sm font-medium">
                        {{ $newOrderStat->getLabel() }}
                    </p>
                
                    <div class="mt-2 mb-4">
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $newOrderStat->getValue() }}
                        </span>
                    </div>
                </div>
                
                @if($newOrderStat->getDescription())
                    <div style="position: absolute; bottom: 8px; right: 12px;">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            {{ $newOrderStat->getDescription() }}
                        </span>
                    </div>
                @endif
            </div>
            @endif
            
            <!-- Second Card: Processing -->
            @php
                $processingStat = null;
                foreach ($allStats as $s) {
                    if (strpos($s->getLabel(), 'Processing') !== false) {
                        $processingStat = $s;
                        break;
                    }
                }
            @endphp
            
            @if($processingStat)
            <div style="border-left: 4px solid #f59e0b;" class="bg-white dark:bg-gray-800 rounded-lg p-4 relative shadow-sm">
                <div>
                    <p style="color: #f59e0b;" class="text-sm font-medium">
                        {{ $processingStat->getLabel() }}
                    </p>
                
                    <div class="mt-2 mb-4">
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $processingStat->getValue() }}
                        </span>
                    </div>
                </div>
                
                @if($processingStat->getDescription())
                    <div style="position: absolute; bottom: 8px; right: 12px;">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            {{ $processingStat->getDescription() }}
                        </span>
                    </div>
                @endif
            </div>
            @endif
            
            <!-- Third Card: Cancelled -->
            @php
                $cancelledStat = null;
                foreach ($allStats as $s) {
                    if (strpos($s->getLabel(), 'Cancelled') !== false) {
                        $cancelledStat = $s;
                        break;
                    }
                }
            @endphp
            
            @if($cancelledStat)
            <div style="border-left: 4px solid #ef4444;" class="bg-white dark:bg-gray-800 rounded-lg p-4 relative shadow-sm">
                <div>
                    <p style="color: #ef4444;" class="text-sm font-medium">
                        {{ $cancelledStat->getLabel() }}
                    </p>
                
                    <div class="mt-2 mb-4">
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $cancelledStat->getValue() }}
                        </span>
                    </div>
                </div>
                
                @if($cancelledStat->getDescription())
                    <div style="position: absolute; bottom: 8px; right: 12px;">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            {{ $cancelledStat->getDescription() }}
                        </span>
                    </div>
                @endif
            </div>
            @endif
        </div>
        
        <!-- Second row -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Fourth Card: Delivered -->
            @php
                $deliveredStat = null;
                foreach ($allStats as $s) {
                    if (strpos($s->getLabel(), 'Delivered') !== false) {
                        $deliveredStat = $s;
                        break;
                    }
                }
            @endphp
            
            @if($deliveredStat)
            <div style="border-left: 4px solid #10b981;" class="bg-white dark:bg-gray-800 rounded-lg p-4 relative shadow-sm">
                <div>
                    <p style="color: #10b981;" class="text-sm font-medium">
                        {{ $deliveredStat->getLabel() }}
                    </p>
                
                    <div class="mt-2 mb-4">
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $deliveredStat->getValue() }}
                        </span>
                    </div>
                </div>
                
                @if($deliveredStat->getDescription())
                    <div style="position: absolute; bottom: 8px; right: 12px;">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            {{ $deliveredStat->getDescription() }}
                        </span>
                    </div>
                @endif
            </div>
            @endif
            
            <!-- Fifth Card: Rescheduled -->
            @php
                $rescheduledStat = null;
                foreach ($allStats as $s) {
                    if (strpos($s->getLabel(), 'Rescheduled') !== false) {
                        $rescheduledStat = $s;
                        break;
                    }
                }
            @endphp
            
            @if($rescheduledStat)
            <div style="border-left: 4px solid #8b5cf6;" class="bg-white dark:bg-gray-800 rounded-lg p-4 relative shadow-sm">
                <div>
                    <p style="color: #8b5cf6;" class="text-sm font-medium">
                        {{ $rescheduledStat->getLabel() }}
                    </p>
                
                    <div class="mt-2 mb-4">
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $rescheduledStat->getValue() }}
                        </span>
                    </div>
                </div>
                
                @if($rescheduledStat->getDescription())
                    <div style="position: absolute; bottom: 8px; right: 12px;">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            {{ $rescheduledStat->getDescription() }}
                        </span>
                    </div>
                @endif
            </div>
            @endif
            
            <!-- Sixth Card: Total Orders -->
            @php
                $totalStat = null;
                foreach ($allStats as $s) {
                    if (strpos($s->getLabel(), 'Total') !== false) {
                        $totalStat = $s;
                        break;
                    }
                }
            @endphp
            
            @if($totalStat)
            <div style="border-left: 4px solid #6b7280;" class="bg-white dark:bg-gray-800 rounded-lg p-4 relative shadow-sm">
                <div>
                    <p style="color: #6b7280;" class="text-sm font-medium">
                        {{ $totalStat->getLabel() }}
                    </p>
                
                    <div class="mt-2 mb-4">
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $totalStat->getValue() }}
                        </span>
                    </div>
                </div>
                
                @if($totalStat->getDescription())
                    <div style="position: absolute; bottom: 8px; right: 12px;">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            {{ $totalStat->getDescription() }}
                        </span>
                    </div>
                @endif
            </div>
            @endif
        </div>
    </x-filament::section>
</x-filament::widget>