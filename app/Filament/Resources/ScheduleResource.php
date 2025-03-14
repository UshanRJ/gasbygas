<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduleResource\Pages;
use App\Filament\Resources\ScheduleResource\RelationManagers;
use App\Models\Schedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Group;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\SelectColumn;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Forms\Components\Section::make('Schedule Details')->schema([
                        Forms\Components\Select::make('outlet_id')
                            ->relationship('outlet', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('category_id')
                            ->relationship('gasCategory', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live(),
                        Forms\Components\Select::make('product_id')
                            ->relationship(
                                'product',
                                'name',
                                fn($query, $get) =>
                                $query->when(
                                    $get('category_id'),
                                    fn($query, $category_id) => $query->where('category_id', $category_id)
                                )
                            )
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Product')
                            ->placeholder(fn($get) => $get('category_id') ? 'Select a product' : 'First select a category'),
                    ])->columns(3),

                    Forms\Components\Section::make('Quantity & Date')->schema([
                        Forms\Components\TextInput::make('quantity')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->suffix('units'),
                        Forms\Components\DatePicker::make('scheduled_date')
                            ->required()
                            ->default(now()),
                    ])->columns(2),
                ])->columnSpan(2),

                Group::make()->schema([
                    Forms\Components\Section::make('Status Information')->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                            ])
                            ->required()
                            ->default('pending'),
                    ]),

                    Forms\Components\Section::make('Schedule Notes')->schema([
                        Forms\Components\MarkdownEditor::make('notes')
                            ->columnSpanFull()
                            ->placeholder('Add any notes about this schedule...')
                            ->fileAttachmentsDirectory('schedules'),
                    ])->collapsible(),

                    Forms\Components\Section::make('Scheduling Options')->schema([
                        Forms\Components\Toggle::make('notify_outlet')
                            ->label('Notify Outlet')
                            ->helperText('Send notification to outlet')
                            ->default(true),
                        Forms\Components\Toggle::make('requires_confirmation')
                            ->label('Require Confirmation')
                            ->helperText('Outlet must confirm receipt')
                            ->default(true),
                    ])->columns(1),
                ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('outlet.name')
                    ->label('Outlet')
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                SelectColumn::make('status')
                    ->label('Schedule Status')
                    ->options([
                        'pending' => '🟡 Pending',
                        'approved' => '🟢 Approved',
                        'cancelled' => '🔴 Cancelled',
                    ])
                    ->sortable()
                    ->searchable()
                    ->default('pending')
                    ->afterStateUpdated(function ($state, $record) {
                        // Save the record first
                        $record->save();

                        // We'll handle the status changed to approved through the ScheduleObserver
                        // This prevents double stock updates
                        if ($state === 'approved') {
                            // Just show a notification in the UI
                            \Filament\Notifications\Notification::make()
                                ->title('Status Updated')
                                ->body("Schedule has been approved")
                                ->success()
                                ->send();
                        }

                        // If status changed to cancelled from approved, deduct from stock
                        if ($state === 'cancelled' && $record->getOriginal('status') === 'approved') {
                            // Find the outlet product
                            $outletProduct = \App\Models\OutletProduct::where([
                                'outlet_id' => $record->outlet_id,
                                'product_id' => $record->product_id,
                            ])->first();

                            if ($outletProduct) {
                                // Deduct the quantity
                                $outletProduct->stock -= $record->quantity;

                                // Ensure stock doesn't go below zero
                                if ($outletProduct->stock < 0) {
                                    $outletProduct->stock = 0;
                                }

                                // Save the outlet product
                                $outletProduct->save();

                                // Show notification
                                \Filament\Notifications\Notification::make()
                                    ->title('Stock Updated')
                                    ->body("Deducted {$record->quantity} units from outlet stock")
                                    ->warning()
                                    ->send();
                            }
                        }
                    }),
                Tables\Columns\TextColumn::make('scheduled_date')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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
                        ->disabled(fn($record) => $record->status === 'approved')
                        ->tooltip(fn($record) => $record->status === 'approved' ? 'Approved schedules cannot be deleted' : null)
                        ->before(function ($record) {
                            // If schedule is in approved status, deduct from stock before deletion
                            if ($record->status === 'approved') {
                                // Find the outlet product
                                $outletProduct = \App\Models\OutletProduct::where([
                                    'outlet_id' => $record->outlet_id,
                                    'product_id' => $record->product_id,
                                ])->first();

                                if ($outletProduct) {
                                    // Deduct the quantity
                                    $outletProduct->stock -= $record->quantity;

                                    // Ensure stock doesn't go below zero
                                    if ($outletProduct->stock < 0) {
                                        $outletProduct->stock = 0;
                                    }

                                    // Save the outlet product
                                    $outletProduct->save();

                                    // Show notification
                                    \Filament\Notifications\Notification::make()
                                        ->title('Stock Updated')
                                        ->body("Deducted {$record->quantity} units from outlet stock")
                                        ->warning()
                                        ->send();
                                }
                            }
                        }),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records) {
                            // Process each record
                            $records->each(function ($record) {
                                // If schedule is in approved status, deduct from stock before deletion
                                if ($record->status === 'approved') {
                                    // Find the outlet product
                                    $outletProduct = \App\Models\OutletProduct::where([
                                        'outlet_id' => $record->outlet_id,
                                        'product_id' => $record->product_id,
                                    ])->first();

                                    if ($outletProduct) {
                                        // Deduct the quantity
                                        $outletProduct->stock -= $record->quantity;

                                        // Ensure stock doesn't go below zero
                                        if ($outletProduct->stock < 0) {
                                            $outletProduct->stock = 0;
                                        }

                                        // Save the outlet product
                                        $outletProduct->save();
                                    }
                                }
                            });

                            // Show a single notification for all updates
                            \Filament\Notifications\Notification::make()
                                ->title('Stock Updated')
                                ->body("Stock has been adjusted for deleted schedules")
                                ->warning()
                                ->send();
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}
