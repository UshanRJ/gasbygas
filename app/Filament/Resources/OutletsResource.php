<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OutletsResource\Pages;
use App\Filament\Resources\OutletsResource\RelationManagers;
use App\Models\Outlets;
use App\Models\OutletProduct;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OutletsResource extends Resource
{
    protected static ?string $model = Outlets::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\Grid::make()
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn(string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', \Str::slug($state)) : null),
                            Forms\Components\TextInput::make('slug')
                                ->label('Unique ID')
                                ->required()
                                ->disabled()
                                ->dehydrated()
                                ->maxLength(255)

                        ]),
                    Forms\Components\TextInput::make('address')
                        ->required(),

                    Forms\Components\TextInput::make('phone')
                        ->tel()
                        ->required()
                        ->numeric(),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->label('Unique ID'),
                Tables\Columns\TextColumn::make('address')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->sortable(),
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
                // Simple view stock action that opens a modal
                Tables\Actions\Action::make('viewStock')
                    ->label('View Stock')
                    ->icon('heroicon-o-shopping-bag')
                    ->color('success')
                    ->modalHeading(fn(Outlets $record) => "{$record->name} - Stock")
                    ->modalContent(function (Outlets $record) {
                        // Get outlet products with their stock
                        $outletProducts = OutletProduct::where('outlet_id', $record->id)
                            ->with('product')
                            ->get();

                        if ($outletProducts->isEmpty()) {
                            return new \Illuminate\Support\HtmlString('
            <div class="flex items-center justify-center p-4">
                <p class="text-gray-500">No products found for this outlet</p>
            </div>
        ');
                        }

                        $html = '
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">Product</th>
                    <th scope="col" class="px-6 py-3 text-center">Stock</th>
                    <th scope="col" class="px-6 py-3 text-center">Status</th>
                </tr>
            </thead>
            <tbody>';

                        foreach ($outletProducts as $outletProduct) {
                            $productName = $outletProduct->product->name ?? 'Unknown Product';
                            $stock = $outletProduct->stock;

                            $statusBadge = '';
                            if ($stock <= 0) {
                                $statusBadge = '<span class="px-2.5 py-0.5 text-xs font-medium bg-red-100 text-red-800 rounded-full">Out of Stock</span>';
                            } elseif ($stock < 10) {
                                $statusBadge = '<span class="px-2.5 py-0.5 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Low Stock</span>';
                            } else {
                                $statusBadge = '<span class="px-2.5 py-0.5 text-xs font-medium bg-green-100 text-green-800 rounded-full">In Stock</span>';
                            }

                            $html .= "
            <tr class=\"bg-white border-b\">
                <td class=\"px-6 py-4 font-medium text-gray-900\">
                    {$productName}
                </td>
                <td class=\"px-6 py-4 text-center\">
                    {$stock}
                </td>
                <td class=\"px-6 py-4 text-center\">
                    {$statusBadge}
                </td>
            </tr>";
                        }

                        $html .= '
            </tbody>
        </table>
    </div>';

                        return new \Illuminate\Support\HtmlString($html);
                    })
                    ->modalSubmitAction(false),

                    Tables\Actions\ActionGroup::make([
                        Tables\Actions\ViewAction::make(),
                        Tables\Actions\EditAction::make(),
                        Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListOutlets::route('/'),
            'create' => Pages\CreateOutlets::route('/create'),
            'edit' => Pages\EditOutlets::route('/{record}/edit'),
        ];
    }
}
