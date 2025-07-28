<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Filament\Resources\SupplierResource\RelationManagers;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\TextFilter;
use Filament\Tables\Filters\SelectFilter;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $inputClass = 'text-lg py-3';
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('サプライヤー名')
                    ->required()
                    ->extraAttributes(['class' => $inputClass]),
                Forms\Components\TextInput::make('location')
                    ->label('所在地')
                    ->required()
                    ->extraAttributes(['class' => $inputClass]),
                Forms\Components\TextInput::make('contact')
                    ->label('連絡先')
                    ->extraAttributes(['class' => $inputClass]),
                Forms\Components\Toggle::make('is_active')
                    ->label('有効')
                    ->default(true)
                    ->extraAttributes(['class' => $inputClass]),
            ]);
    }

    public static function table(Table $table): Table
    {
        $isMobile = request()->header('user-agent') && preg_match('/Mobile|Android|iPhone|iPad/i', request()->header('user-agent'));
        return $table
            ->columns([
                TextColumn::make('name')->label('サプライヤー名')->searchable(),
                TextColumn::make('location')->label('所在地')->visible(!$isMobile),
                TextColumn::make('contact')->label('連絡先')->visible(!$isMobile),
                BooleanColumn::make('is_active')->label('有効'),
            ])
            ->contentGrid(['md' => 2, 'xl' => 3])
            ->filters([
                Tables\Filters\Filter::make('name')
                    ->form([
                        Forms\Components\TextInput::make('value')->label('サプライヤー名')->placeholder('例: 静岡茶園'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['value']) {
                            $query->where('name', 'like', '%'.$data['value'].'%');
                        }
                    }),
                Tables\Filters\Filter::make('location')
                    ->form([
                        Forms\Components\TextInput::make('value')->label('所在地')->placeholder('例: 静岡県'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['value']) {
                            $query->where('location', 'like', '%'.$data['value'].'%');
                        }
                    }),
                Tables\Filters\SelectFilter::make('is_active')->label('有効/無効')
                    ->options([1 => '有効', 0 => '無効']),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ExportAction::make(),
                    ImportAction::make()
                        ->label('CSVインポート')
                        ->icon('heroicon-o-arrow-up-tray'),
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }

    public static function canEdit(
        \Illuminate\Database\Eloquent\Model $record
    ): bool {
        return auth()->user()?->hasRole('admin');
    }

    public static function canDelete(
        \Illuminate\Database\Eloquent\Model $record
    ): bool {
        return auth()->user()?->hasRole('admin');
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasRole('admin');
    }
}
