<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use App\Models\Outlets;
use App\Models\GasCategory;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class GasOrdersByOutlet extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 3; // Position after LatestOrders

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Modified query to match your model structure
                Order::query()
                    ->whereHas('orderItems.product.gasCategory', function ($query) {
                        // Filter gas products using the gasCategory relationship
                        // You can adjust this query based on how you identify gas products
                        $query->whereNotNull('id');
                    })
                    ->with(['outlet', 'user', 'orderItems.product'])
            )
            ->defaultPaginationPageOption(10)
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('outlet.name')
                    ->label('Outlet')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('id')
                    ->label('Order ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('orderItems.product.name')
                    ->label('Gas Type')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('orderItems.product.gasCategory.name')
                    ->label('Gas Category')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('orderItems.scheduled_date')
                    ->label('Delivery Schedule')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->color(fn(string $state): string => match ($state) {
                        'new' => 'info',
                        'processing' => 'warning',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                        'rescheduled' => 'warning',
                        default => 'gray',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'new' => 'heroicon-m-sparkles',
                        'processing' => 'heroicon-m-arrow-path',
                        'delivered' => 'heroicon-m-check-badge',
                        'cancelled' => 'heroicon-m-x-circle',
                        'rescheduled' => 'heroicon-m-calendar',
                        default => 'heroicon-m-question-mark-circle',
                    }),
                TextColumn::make('cylinder_status')
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'completed' => 'success',
                        default => 'gray',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'pending' => 'heroicon-m-arrow-path',
                        'completed' => 'heroicon-m-check-badge',
                        default => 'heroicon-m-question-mark-circle',
                    }),
                TextColumn::make('payment_status')
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'completed' => 'success',
                        'failed' => 'danger',
                        'refunded' => 'warning',
                        default => 'gray',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'pending' => 'heroicon-m-arrow-path',
                        'completed' => 'heroicon-m-check-badge',
                        'failed' => 'heroicon-m-x-circle',
                        'refunded' => 'heroicon-m-calendar',
                        default => 'heroicon-m-question-mark-circle',
                    }),
                TextColumn::make('price')
                    ->label('Amount')
                    ->money('LKR')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('outlet')
                    ->relationship('outlet', 'name')
                    ->label('Filter by Outlet')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('gas_category')
                    ->label('Gas Category')
                    ->options(function() {
                        return GasCategory::pluck('name', 'id')->toArray();
                    })
                    ->query(function (Builder $query, array $data) {
                        return $query->when($data['value'], function ($query, $categoryId) {
                            return $query->whereHas('orderItems.product', function ($q) use ($categoryId) {
                                $q->where('category_id', $categoryId);
                            });
                        });
                    }),
                SelectFilter::make('status')
                    ->options([
                        'new' => 'New',
                        'processing' => 'Processing',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                        'rescheduled' => 'Rescheduled',
                    ])
                    ->label('Order Status'),
                SelectFilter::make('cylinder_status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                    ])
                    ->label('Cylinder Status'),
                SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ])
                    ->label('Payment Status'),
                SelectFilter::make('scheduled_date')
                    ->query(function (Builder $query, array $data) {
                        return $query->when($data['value'] === 'today', function ($query) {
                            return $query->whereHas('orderItems', function ($q) {
                                $q->whereDate('scheduled_date', now());
                            });
                        })->when($data['value'] === 'tomorrow', function ($query) {
                            return $query->whereHas('orderItems', function ($q) {
                                $q->whereDate('scheduled_date', now()->addDay());
                            });
                        })->when($data['value'] === 'this_week', function ($query) {
                            return $query->whereHas('orderItems', function ($q) {
                                $q->whereBetween('scheduled_date', [
                                    now()->startOfWeek(),
                                    now()->endOfWeek(),
                                ]);
                            });
                        });
                    })
                    ->options([
                        'today' => 'Today',
                        'tomorrow' => 'Tomorrow',
                        'this_week' => 'This Week',
                    ])
                    ->label('Delivery Schedule'),
            ])
            ->groups([
                Group::make('outlet.name')
                    ->label('Group by Outlet')
                    ->getTitleFromRecordUsing(fn (Order $record): string => $record->outlet?->name ?? 'No Outlet'),
                Group::make('delivery_date')
                    ->label('Group by Delivery Date')
                    ->getTitleFromRecordUsing(function (Order $record): string {
                        $scheduledDate = $record->orderItems->first()?->scheduled_date;
                        return $scheduledDate ? $scheduledDate->format('d M Y') : 'Not Scheduled';
                    }),
                Group::make('gas_category')
                    ->label('Group by Gas Category')
                    ->getTitleFromRecordUsing(function (Order $record): string {
                        return $record->orderItems->first()?->product?->gasCategory?->name ?? 'No Category';
                    }),
            ])
            ->actions([
                Action::make('Reschedule')
                    ->url(fn(Order $record): string => OrderResource::getUrl('edit', ['record' => $record]))
                    ->color('warning')
                    ->icon('heroicon-o-calendar'),
                ActionGroup::make([
                    Action::make('View Order')
                        ->url(fn(Order $record): string => OrderResource::getUrl('view', ['record' => $record]))
                        ->color('info')
                        ->icon('heroicon-o-eye'),
                    Tables\Actions\DeleteAction::make(),
                ])
                ->icon('heroicon-m-ellipsis-vertical')
                ->label('Actions'),
                
            ]);
    }
}