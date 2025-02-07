<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'order';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('orderItems.product.name')
                ,
                
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
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Action::make(name: 'View Order')
                ->url(fn(Order $record):string=> OrderResource::getUrl('view',['record'=>$record]))
                ->color('info')
                ->icon('heroicon-o-eye'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
