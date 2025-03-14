<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

   
    public function table(Table $table): Table
    {
        return $table
            ->query(OrderResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('Order ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('orderItems.product.name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('payment_status')
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'completed' => 'success',
                        'failed' => 'danger',
                        'refunded' => 'warning',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'pending' => 'heroicon-m-arrow-path',
                        'completed' => 'heroicon-m-check-badge',
                        'failed' => 'heroicon-m-x-circle',
                        'refunded' => 'heroicon-m-calendar',
                    }),
                TextColumn::make('cylinder_status')
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'completed' => 'success',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'pending' => 'heroicon-m-arrow-path',
                        'completed' => 'heroicon-m-check-badge',
                    }),
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
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'new' => 'heroicon-m-sparkles',
                        'processing' => 'heroicon-m-arrow-path',
                        'delivered' => 'heroicon-m-check-badge',
                        'cancelled' => 'heroicon-m-x-circle',
                        'rescheduled' => 'heroicon-m-calendar',
                    }),
                TextColumn::make('price')
                ,
                TextColumn::make('created_at')
                    ->label('Order Date')
                    ->dateTime(),
            ])

            ->actions([
                // Tables\Actions\EditAction::make(),
                Action::make(name: 'View Order')
                ->url(fn(Order $record):string=> OrderResource::getUrl('view',['record'=>$record]))
                ->color('info')
                ->icon('heroicon-o-eye'),
                // Tables\Actions\DeleteAction::make(),
            ]);
    }
}
