<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Outlets;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class OrderStatusByOutlet extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';
    
    // Property to store the selected outlet
    public ?string $outletId = null;
    
    // Method to handle outlet selection
    public function filterByOutlet($outletId): void
    {
        $this->outletId = $outletId ?: null;
    }
    
    // Override the getHeading method to include the dropdown directly in the widget
    protected function getHeading(): string
    {
        return 'Order Status by Outlet';
    }
    
    // Add extra headers - this is the Filament 3 way to add components to headers
    protected function getExtraHeaderComponents(): array
    {
        $outlets = Outlets::orderBy('name')->pluck('name', 'id')->toArray();
        
        return [
            view('filament.widgets.outlet-dropdown', [
                'outlets' => $outlets,
                'outletId' => $this->outletId,
            ]),
        ];
    }
    
    protected function getStats(): array
    {
        // Base query
        $query = Order::query();

        // Apply outlet filter if selected
        if ($this->outletId) {
            $query->where('outlet_id', $this->outletId);
        }

        // Get counts by status
        $statusCounts = $query->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Define all possible statuses with their display settings
        $allStatuses = [
            'new' => [
                'label' => 'New Orders',
                'icon' => 'heroicon-o-sparkles',
                'color' => 'info',
            ],
            'processing' => [
                'label' => 'Processing',
                'icon' => 'heroicon-o-arrow-path',
                'color' => 'warning',
            ],
            'delivered' => [
                'label' => 'Delivered',
                'icon' => 'heroicon-o-check-badge',
                'color' => 'success',
            ],
            'cancelled' => [
                'label' => 'Cancelled',
                'icon' => 'heroicon-o-x-circle',
                'color' => 'danger',
            ],
            'rescheduled' => [
                'label' => 'Rescheduled',
                'icon' => 'heroicon-o-calendar',
                'color' => 'warning',
            ],
        ];

        // Get outlet name if selected
        $outletName = $this->outletId
            ? Outlets::find($this->outletId)?->name
            : 'All Outlets';

        // Create stat objects for each status
        $stats = [];
        foreach ($allStatuses as $status => $config) {
            $count = $statusCounts[$status] ?? 0;
            
            $stats[] = Stat::make($config['label'], $count)
                ->description("$outletName")
                ->icon($config['icon'])
                ->color($config['color']);
        }

        // Add total orders stat
        $totalOrders = array_sum($statusCounts);
        $stats[] = Stat::make('Total Orders', $totalOrders)
            ->description("$outletName")
            ->icon('heroicon-o-shopping-bag')
            ->color('gray');

        return $stats;
    }
}