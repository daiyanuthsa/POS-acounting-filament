<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\CashFlowResource\Pages;
use App\Filament\Merchant\Resources\CashFlowResource\RelationManagers;
use App\Models\Account;
use App\Models\CashFlow;
use Auth;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CashFlowResource extends Resource
{
    protected static ?string $model = CashFlow::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Accountings';
    protected static ?string $pluralModelLabel = 'Jurnal Umum';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('account_id')
                    ->label('Nama Akun')
                    // ->relationship(
                    //     'account',
                    //     'accountName',
                    //     modifyQueryUsing: fn(Builder $query) => $query->where('team_id', Auth::user()->teams()->first()->id)
                    // )
                    ->options(function () {
                        return Account::query()
                            ->where('team_id', Auth::user()->teams()->first()->id)
                            ->get()
                            ->mapWithKeys(function ($account) {
                                return [
                                    $account->id => $account->accountName
                                ];
                            });
                    })
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\DatePicker::make('transaction_date')
                    ->label('Tanggal Transaksi')
                    ->required(),
                Forms\Components\TextInput::make('description')
                    ->label('Deskripsi')
                    ->maxLength(255),
                Forms\Components\TextInput::make('amount')
                    ->label('Jumlah')
                    ->required()
                    ->prefix('IDR')
                    ->numeric()
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(','),
                Forms\Components\Select::make('type')
                    ->required()
                    ->label('Tipe Transaksi')
                    ->options([
                        'debit' => 'Debit',
                        'credit' => 'Credit'
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('transaction_date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('account.accountName')
                    ->limit(24)
                    ->label('Akun'),
                Tables\Columns\TextColumn::make('transaction_date')
                    ->label('Tanggal Transaksi')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(40)
                    ->searchable(),
                Tables\Columns\TextColumn::make('debit_amount')
                    ->label('Debit')
                    ->money('IDR')

                    ->getStateUsing(function (CashFlow $record) {
                        return $record->type === 'debit' ? $record->amount : '-';
                    }),
                Tables\Columns\TextColumn::make('credit_amount')
                    ->label('Credit')
                    ->money('IDR')
                    ->getStateUsing(function (CashFlow $record) {
                        return $record->type === 'credit' ? $record->amount : '-';
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make()
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
            'index' => Pages\ListCashFlows::route('/'),
            'create' => Pages\CreateCashFlow::route('/create'),
            'edit' => Pages\EditCashFlow::route('/{record}/edit'),
        ];
    }
}
