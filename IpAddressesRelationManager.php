<?php

namespace App\Filament\Resources\PrinterResource\RelationManagers;

use App\Models\IpAddress;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IpAddressesRelationManager extends RelationManager
{
    protected static string $relationship = 'ipAddresses';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('ip_addressable_type')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitle(fn(IpAddress $record): string => "{$record->address} ({$record->ip_addressable_type})")
            ->columns([
                Tables\Columns\TextColumn::make('subnet.subnet'),
                Tables\Columns\TextColumn::make('subnet.gateway'),
                Tables\Columns\TextColumn::make('address'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AssociateAction::make()
                    ->recordSelectSearchColumns(['address'])
                    ->preloadRecordSelect(),
            ])
            ->actions([
                Tables\Actions\DissociateAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DissociateBulkAction::make(),
                ]),
            ])->inverseRelationship('ipAddressable');
    }
}
