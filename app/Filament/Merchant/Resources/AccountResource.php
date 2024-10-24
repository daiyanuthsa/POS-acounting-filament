<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\AccountResource\Pages;
use App\Filament\Merchant\Resources\AccountResource\RelationManagers;
use App\Models\Account;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccountResource extends Resource
{
    protected static ?string $model = Account::class;
    protected static ?string $tenantOwnershipRelationshipName = 'team';
    protected static ?string $navigationGroup = 'Accountings';
    protected static ?string $navigationLabel = 'Akun';
    protected static ?string $pluralModelLabel = 'Akun';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->required()

                    ->maxLength(255)
                    ->rule(function ($livewire) {
                        $teamId = auth()->user()->team_id; // Mengambil team_id dari user saat ini
            
                        if ($livewire->record) {
                            // Jika sedang dalam mode edit, cek apakah 'code' unik di dalam team_id yang sama, kecuali record yang sedang diedit
                            return 'unique:accounts,code,' . $livewire->record->id . ',id,team_id,' . $livewire->record->team_id;
                        }

                        // Jika sedang dalam mode create, cek apakah 'code' unik di dalam team_id user
                        return 'unique:accounts,code,NULL,id,team_id,' . $teamId;
                    })
                    ->label('Account Code'),
                Forms\Components\TextInput::make('accountName')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('accountType')
                    ->label('Tipe Akun')
                    ->options([
                        'Asset' => 'Asset',
                        'Liability' => 'Liability',
                        'Equity' => 'Equity',
                        'Revenue' => 'Revenue',
                        'Expense' => 'Expense'
                    ])
                    ->required()
                    ->reactive(),
                Forms\Components\Select::make('asset_type')
                    ->label('Tipe Asset')
                    ->options([
                        'current' => 'Aktiva Lancar',
                        'fixed' => 'Aktiva Tetap',
                    ])
                    ->reactive()
                    ->visible(fn(callable $get) => $get('accountType') === 'Asset')
                    ->required(fn(callable $get) => $get('accountType') === 'Asset'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->sortable('true'),
                Tables\Columns\TextColumn::make('accountName')
                    ->searchable(),
                Tables\Columns\TextColumn::make('accountType'),
                Tables\Columns\TextColumn::make('asset_type')
                    ->label('Tipe Asset')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'fixed' => 'Aktiva Tetap',
                            'current' => 'Aktiva Lancar',
                            default => ucfirst($state),
                        };
                    })
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'current' => 'warning',
                        'fixed' => 'success',
                    }),
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
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAccounts::route('/'),
            'create' => Pages\CreateAccount::route('/create'),
            'edit' => Pages\EditAccount::route('/{record}/edit'),
        ];
    }
}
