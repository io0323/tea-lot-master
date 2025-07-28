<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InspectorResource\Pages;
use App\Filament\Resources\InspectorResource\RelationManagers;
use App\Models\Inspector;
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
use Filament\Tables\Filters\TextFilter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InspectorResource extends Resource
{
    protected static ?string $model = Inspector::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $inputClass = 'text-lg py-3';
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('検査員名')
                    ->required()
                    ->extraAttributes(['class' => $inputClass]),
                Forms\Components\TextInput::make('role')
                    ->label('役割')
                    ->required()
                    ->extraAttributes(['class' => $inputClass]),
                Forms\Components\TextInput::make('email')
                    ->label('メールアドレス')
                    ->email()
                    ->required()
                    ->extraAttributes(['class' => $inputClass]),
            ]);
    }

    public static function table(Table $table): Table
    {
        $isMobile = request()->header('user-agent') && preg_match('/Mobile|Android|iPhone|iPad/i', request()->header('user-agent'));
        return $table
            ->columns([
                TextColumn::make('name')->label('検査員名')->searchable(),
                TextColumn::make('role')->label('役割')->visible(!$isMobile),
                TextColumn::make('email')->label('メールアドレス')->visible(!$isMobile),
            ])
            ->contentGrid(['md' => 2, 'xl' => 3])
            ->filters([
                Tables\Filters\Filter::make('name')
                    ->form([
                        Forms\Components\TextInput::make('value')->label('検査員名')
                            ->placeholder('例: 田中'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['value']) {
                            $query->where('name', 'like', '%'.$data['value'].'%');
                        }
                    }),
                Tables\Filters\Filter::make('role')
                    ->form([
                        Forms\Components\TextInput::make('value')->label('役割')
                            ->placeholder('例: 主任検査員'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['value']) {
                            $query->where('role', 'like', '%'.$data['value'].'%');
                        }
                    }),
                Tables\Filters\Filter::make('email')
                    ->form([
                        Forms\Components\TextInput::make('value')->label('メールアドレス')
                            ->placeholder('例: example.com'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['value']) {
                            $query->where('email', 'like', '%'.$data['value'].'%');
                        }
                    }),
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
            'index' => Pages\ListInspectors::route('/'),
            'create' => Pages\CreateInspector::route('/create'),
            'edit' => Pages\EditInspector::route('/{record}/edit'),
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
