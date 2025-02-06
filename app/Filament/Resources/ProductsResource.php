<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductsResource\Pages;
use App\Filament\Resources\ProductsResource\RelationManagers;
use App\Models\Products;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Textarea;


class ProductsResource extends Resource
{
    protected static ?string $model = Products::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Forms\Components\Section::make('Product Information')->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(225)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', \Str::slug($state)) : null),
                        TextInput::make('slug')
                            ->label('Unique ID')
                            ->required()
                            ->disabled()
                            ->dehydrated()
                            ->maxLength(255)
                            ->unique(Products::class, 'slug', ignoreRecord: true),

                        MarkdownEditor::make('description')
                            ->columnSpanFull()
                            ->fileAttachmentsDirectory('products')

                    ])->columns(2),
                    Section::make('Images')->schema([
                        Forms\Components\FileUpload::make('image')
                            ->multiple()
                            ->image()
                            ->maxFiles(5)
                            ->reorderable()
                            ->directory('products'),
                    ])->columns(1),
                ])->columnSpan(2),

                Group::make()->schema([
                    Section::make('Weight')->schema([
                        TextInput::make('weight')
                            ->numeric()
                            ->inputMode('decimal')
                            ->required()
                            ->suffix('KG'),

                    ]),
                    Section::make('Price')->schema([
                        TextInput::make('price')
                            ->numeric()
                            ->inputMode('decimal')
                            ->required()
                            ->suffix('LKR'),

                    ]),
                    Section::make('Gas Categories')->schema([
                        Select::make('category_id')
                            ->label('')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->relationship('gasCategory', 'name')
                    ]),
                    Section::make('Product Status')->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->required()
                            ->default(true),
                        Forms\Components\Toggle::make('is_stock')
                            ->label('In Stock')
                            ->required()
                            ->default(true),
                        Forms\Components\Toggle::make('on_sale')
                            ->label('On Sale')
                            ->required()
                            ->default(false)
                    ])->columns(2),


                ])->columns(1),
            ])->columns(3);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('gasCategory.name')
                    ->label('Gas Category'),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Unique ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->searchable()
                    ->money('LKR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('weight')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_stock')
                    ->boolean(),
                Tables\Columns\IconColumn::make('on_sale')
                    ->boolean(),
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
                SelectFilter::make('gasCategory')
                    ->label('Gas Category')
                    ->relationship('gasCategory', 'name'),
                //     SelectFilter::make('ActiveGas')
                //     ->label('Active Gas')
                //     ->options([
                //         '1' => 'Active',
                //         '0' => 'Inactive',
                //     ])
                //     ->query(fn ($query) => $query->where('is_active', request()->input('tableFilters.ActiveGas'))),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProducts::route('/create'),
            'edit' => Pages\EditProducts::route('/{record}/edit'),
        ];
    }
}
