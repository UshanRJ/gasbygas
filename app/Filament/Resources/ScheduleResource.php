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
                    ])
                    ->sortable()
                    ->searchable()
                    ->default('pending')
                    ->afterStateUpdated(function ($state, $record) {
                        // Save the record first
                        $record->save();

                        // If status changed to approved, update outlet product stock
                        // if ($state === 'approved') {
                        //     // Find or create outlet product record
                        //     $outletProduct = \App\Models\OutletProduct::firstOrNew([
                        //         'outlet_id' => $record->outlet_id,
                        //         'product_id' => $record->product_id,
                        //     ]);

                        //     // If this is a new record, set initial stock to 0
                        //     if (!$outletProduct->exists) {
                        //         $outletProduct->stock = 0;
                        //     }

                        //     // Add schedule quantity to stock
                        //     $outletProduct->stock += $record->quantity;

                        //     // Save the outlet product
                        //     $outletProduct->save();

                        //     // Show notification
                        //     \Filament\Notifications\Notification::make()
                        //         ->title('Stock Updated')
                        //         ->body("Added {$record->quantity} units to outlet stock")
                        //         ->success()
                        //         ->send();
                        // }
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
