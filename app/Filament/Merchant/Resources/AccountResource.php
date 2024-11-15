<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\AccountResource\Pages;
use App\Filament\Merchant\Resources\AccountResource\RelationManagers;
use App\Models\Account;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Actions\Action;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\HtmlString;
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
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    public static function form(Form $form): Form
    {
        $teamId = auth()->user()->teams()->first()->id;

        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->maxLength(5)
                    ->live(onBlur: false)
                    ->afterStateUpdated(function (string $state, $set, $get) use ($teamId) {
                        $state = $state ?? '';

                        if (empty($state)) {
                            return;
                        }

                        $exists = \DB::table('accounts')
                            ->where('code', $state)
                            ->where('team_id', $teamId)
                            ->exists();

                        if ($exists) {
                            $set('code_error', 'Account Code sudah digunakan dalam tim ini!');
                        } else {
                            $set('code_error', null);
                        }
                    })
                    ->extraInputAttributes(fn(callable $get) => [
                        'class' => $get('code_error') ? 'border-danger-600' : ''
                    ])
                    ->helperText(
                        fn(callable $get) =>
                        $get('code_error') ?
                        new HtmlString('<span class="text-danger-600">' . $get('code_error') . '</span>') :
                        'Masukkan Account Code yang unik'
                    )
                    ->dehydrateStateUsing(function (string $state) use ($teamId) {
                        $exists = \DB::table('accounts')
                            ->where('code', $state)
                            ->where('team_id', $teamId)
                            ->exists();

                        if ($exists) {
                            throw ValidationException::withMessages([
                                'code' => 'Account Code sudah digunakan dalam tim ini!'
                            ]);
                        }

                        return $state;
                    })
                    ->label('Account Code')
                    ->rules([
                        fn(Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($teamId, $get) {
                            $query = \DB::table('accounts')
                                ->where('code', $value)
                                ->where('team_id', $teamId);

                            if ($get('id')) {
                                $query->where('id', '<>', $get('id'));
                            }

                            $exists = $query->exists();

                            if ($exists) {
                                $fail("Kode akun {$value} sudah terdaftar!");
                            }
                        },
                    ]),
                Forms\Components\TextInput::make('accountName')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('accountType')
                    ->label('Tipe Akun')
                    ->options([
                        'Asset' => 'Aset',
                        'Liability' => 'Hutang',
                        'Equity' => 'Modal',
                        'Revenue' => 'Pendapatan',
                        'Expense' => 'Beban',
                        'UPC' => 'HPP'
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
                    ->searchable()
                    ->label('Nama Akun'),
                Tables\Columns\TextColumn::make('accountType')
                    ->label('Tipe Akun')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'Asset' => 'Aset',
                            'Liability' => 'Hutang',
                            'Equity' => 'Modal',
                            'Revenue' => 'Pendapatan',
                            'Expense' => 'Beban',
                            'UPC' => 'HPP',
                            default => ucfirst($state),
                        };
                    }),
                Tables\Columns\TextColumn::make('asset_type')
                    ->label('Tipe Asset')
                    ->toggleable(isToggledHiddenByDefault: false)
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

    public function getFormActions(): array
    {
        return [
            Forms\Components\Button::make('submit')
                ->label('Create Account')
                ->disabled(fn() => $this->form->getState('is_create_disabled')) // Disable button based on form validation
                ->submit() // Submit the form
        ];
    }
}
