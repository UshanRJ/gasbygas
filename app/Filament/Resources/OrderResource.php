<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\AddressRelationManager;
use App\Models\Products;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\User;
use App\Models\DeliveryStock;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Pages\Actions\ButtonAction;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Number;
use PhpParser\Node\Stmt\Label;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    // protected static ?string $recordTitleAttribute = 'orderItems.product.name';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?int $navigationSort = 4;

    // Method to generate unique ID for each order item
    protected static function generateUniqueId()
    {
        // Generate a unique identifier, here we're using a combination of a timestamp and a random string.
        return Str::uuid()->toString();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Order Information')->schema([
                        Select::make('user_id')
                            ->Label('Customer')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->options(function () {
                                // Fetch users who do not have orders with status 'new', 'processing', or 'rescheduled'
                                return User::whereDoesntHave('order', function ($query) {
                                    $query->whereIn('status', ['new', 'processing', 'rescheduled']);
                                })->pluck('name', 'id');
                            }),
                        Select::make('district')
                            ->label('District')
                            ->required()
                            ->options([
                                'Ampara' => 'Ampara',
                                'Anuradhapura' => 'Anuradhapura',
                                'Badulla' => 'Badulla',
                                'Batticaloa' => 'Batticaloa',
                                'Colombo' => 'Colombo',
                                'Galle' => 'Galle',
                                'Gampaha' => 'Gampaha',
                                'Hambantota' => 'Hambantota',
                                'Jaffna' => 'Jaffna',
                                'Kalutara' => 'Kalutara',
                                'Kandy' => 'Kandy',
                                'Kegalle' => 'Kegalle',
                                'Kilinochchi' => 'Kilinochchi',
                                'Kurunegala' => 'Kurunegala',
                                'Mannar' => 'Mannar',
                                'Matale' => 'Matale',
                                'Matara' => 'Matara',
                                'Monaragala' => 'Monaragala',
                                'Mullaitivu' => 'Mullaitivu',
                                'Nuwara Eliya' => 'Nuwara Eliya',
                                'Polonnaruwa' => 'Polonnaruwa',
                                'Puttalam' => 'Puttalam',
                                'Ratnapura' => 'Ratnapura',
                                'Trincomalee' => 'Trincomalee',
                                'Vavuniya' => 'Vavuniya',
                            ])
                            ->searchable()
                            ->preload(),
                        Select::make('payment_method')
                            ->label('Payment Method')
                            ->options(
                                [
                                    'payAtStore' => 'Pay at Store'
                                ]
                            )
                            ->required(),
                        Select::make('payment_status')
                            ->label('Payment Status')
                            ->options(
                                [
                                    'pending' => 'Pending',
                                    'completed' => 'Completed',
                                    'failed' => 'Failed',
                                    'refunded' => 'Refunded',
                                ]
                            )
                            ->required()
                            ->default('pending'),


                        ToggleButtons::make('status')
                            ->inline()
                            ->default('new')
                            ->required()
                            ->colors([
                                'new' => 'info',
                                'processing' => 'warning',
                                'delivered' => 'success',
                                'cancelled' => 'danger',
                                'rescheduled' => 'warning',
                            ])->columnSpan('2')
                            ->icons([
                                'new' => 'heroicon-m-sparkles',
                                'processing' => 'heroicon-m-arrow-path',
                                'delivered' => 'heroicon-m-check-badge',
                                'cancelled' => 'heroicon-m-x-circle',
                                'rescheduled' => 'heroicon-m-calendar',
                            ])
                            ->options([
                                'new' => 'New',
                                'processing' => 'Processing',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                                'rescheduled' => 'Rescheduled',
                            ]),
                        Select::make('currency')
                            ->options(
                                [
                                    'lkr' => 'LKR',
                                ]
                            )
                            ->required()
                            ->default('lkr'),
                        Select::make('shipping_method')
                            ->options(
                                [
                                    'pickup' => 'Pick Up',
                                ]
                            )
                            ->required()
                            ->default('pickup'),
                        Textarea::make('notes')
                            ->columnSpanFull()
                    ])->columns(2),
                    Section::make('Order Items')->schema(
                        [
                            Repeater::make('orderItems')
                                ->relationship()
                                ->maxItems(1)
                                ->schema([
                                    Select::make('product_id')
                                        ->relationship('product', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->distinct()
                                        ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                        ->columnSpan(4)
                                        ->reactive()
                                        ->afterStateUpdated(fn($state, Set $set) => $set('unit_amount', Products::find($state)?->price ?? 0))
                                        ->afterStateUpdated(fn($state, Set $set) => $set('total_amount', Products::find($state)?->price ?? 0)),
                                    TextInput::make('quantity')
                                        ->numeric()
                                        ->required()
                                        ->default(1)
                                        ->minValue(1)
                                        ->maxValue(1)
                                        ->columnSpan(2)
                                        ->reactive()
                                        ->afterStateUpdated(fn($state, Set $set, Get $get) => $set('total_amount', $state * $get('unit_amount'))),
                                    // Add the scheduled_date field
                                    TextInput::make('scheduled_date')
                                        ->label('Scheduled Date')
                                        ->type('date')
                                        ->required()
                                        ->reactive()
                                        ->columnSpan(2)
                                        ->extraAttributes([
                                            'min' => Carbon::now()->format('Y-m-d'), // Today's date as the minimum
                                            'max' => Carbon::now()->addDays(14)->format('Y-m-d'), // 14 days from today as the maximum
                                        ])
                                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                            $minDate = Carbon::now()->format('Y-m-d'); // Today's date
                                            $maxDate = Carbon::now()->addDays(14)->format('Y-m-d'); // 14 days from today
                                
                                            // Validate selected date
                                            if ($state < $minDate || $state > $maxDate) {
                                                Notification::make()
                                                    ->title("Scheduled date must be within the next 14 days from today.")
                                                    ->danger()
                                                    ->send();
                                                $set('scheduled_date', null); // Reset invalid value
                                            }
                                        }),
                                    TextInput::make('unit_amount')
                                        ->numeric()
                                        ->required()
                                        ->disabled()
                                        ->dehydrated()
                                        ->columnSpan(2),
                                    TextInput::make('total_amount')
                                        ->numeric()
                                        ->required()
                                        ->dehydrated()
                                        ->columnSpan(2),
                                    // Add the unique ID field here
                                    Hidden::make('order_token')
                                        ->default(fn() => static::generateUniqueId())
                                        ->dehydrated(),
                                ])->columns(12),
                            Placeholder::make('grand_total')
                                ->label('Grand Total')
                                ->content(function (Get $get, Set $set) {
                                    $total = 0;
                                    if (!$repeaters = $get('orderItems')) {
                                        return $total;
                                    }
                                    foreach ($repeaters as $key => $repeater) {
                                        $total += $get("orderItems.{$key}.total_amount");
                                    }
                                    $set('price', $total);
                                    return Number::currency($total, 'LKR');
                                }),
                            Hidden::make('price')
                        ]
                    )
                ])->columnSpanFull()

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Customer Name')
                    ->searchable(),
                TextColumn::make('orderItems.product.name')
                    ->label('Ordered Item')
                    ->searchable(),
                TextColumn::make('price')
                    ->searchable()
                    ->money('LKR')
                    ->sortable(),
                TextColumn::make('payment_method')
                    ->label('Payment Method')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(
                        fn($state) => collect(explode(',', $state))
                            ->map(fn($value) => match ($value) {
                                'payAtStore' => '🏬 Pay at Store',
                                'creditCard' => '💳 Credit Card',
                                'bankTransfer' => '🏦 Bank Transfer',
                                'cashOnDelivery' => '💵 Cash on Delivery',
                                default => 'Unknown'
                            })->implode(', ')
                    )
                    ->sortable()
                    ->searchable(),

                SelectColumn::make('payment_status')
                    ->label('Payment Status')
                    ->options([
                        'pending' => '🟡 Pending',
                        'completed' => '🟢 Completed',
                        'failed' => '🔴 Failed',
                        'refunded' => '🔵 Refunded',
                    ])
                    ->sortable()
                    ->searchable()
                    ->default('pending') // Optional: Sets a default value
                    ->afterStateUpdated(fn($state, $record) => $record->save()),// Auto-save after change

                SelectColumn::make('cylinder_status')
                    ->label('Cylinder Status')
                    ->options([
                        'pending' => '🟡 Pending',
                        'completed' => '🟢 Completed',
                    ])
                    ->sortable()
                    ->searchable()
                    ->default('pending') // Optional: Sets a default value
                    ->afterStateUpdated(fn($state, $record) => $record->save()),// Auto-save after change

                TextColumn::make('shipping_method')
                    ->label('Shipping Method')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(
                        fn($state) => collect(explode(',', $state))
                            ->map(fn($value) => match ($value) {
                                'pickup' => '📦 Pick Up',
                                'delivery' => '🚚 Delivery',
                                'express' => '⚡ Express Delivery',
                                default => 'Unknown'
                            })->implode(', ')
                    )
                    ->sortable()
                    ->searchable(),
                SelectColumn::make('status')
                    ->label('Order Status')
                    ->options([
                        'new' => '🆕 New',
                        'processing' => '🔄 Processing',
                        'delivered' => '✅ Delivered',
                        'cancelled' => '❌ Cancelled',
                        'rescheduled' => '📅 Rescheduled',
                    ])
                    ->sortable()
                    ->searchable()
                    ->default('new') // Optional: Sets a default value
                    ->afterStateUpdated(fn($state, $record) => $record->save()), // Auto-save after change

                TextColumn::make('notes')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('orderItems.order_token')
                    ->label('Order Token')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make()
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AddressRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
    // Page refresh method
    protected function reloadPage()
    {
        return redirect()->route('filament.resources.orders.index'); // Adjust to your route
    }

    //display orders count
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereIn('status', ['rescheduled', 'pending', 'new'])->count();
    }



}
