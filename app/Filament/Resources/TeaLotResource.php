<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeaLotResource\Pages;
use App\Models\TeaLot;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Barryvdh\DomPDF\Facade\Pdf;

class TeaLotResource extends Resource
{
    protected static ?string $model = TeaLot::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $inputClass = 'text-lg py-3';
        return $form
            ->schema([
                Forms\Components\TextInput::make('batch_code')
                    ->label('ロット番号')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->extraAttributes(['class' => $inputClass]),
                Forms\Components\TextInput::make('tea_type')
                    ->label('茶種')
                    ->required()
                    ->extraAttributes(['class' => $inputClass]),
                Forms\Components\TextInput::make('origin')
                    ->label('産地')
                    ->required()
                    ->extraAttributes(['class' => $inputClass]),
                Forms\Components\TextInput::make('moisture')
                    ->label('含水率（%）')
                    ->numeric()
                    ->required()
                    ->minValue(0)->maxValue(100)
                    ->extraAttributes(['class' => $inputClass]),
                Forms\Components\TextInput::make('aroma_score')
                    ->label('香りスコア')
                    ->numeric()
                    ->required()
                    ->minValue(0)->maxValue(100)
                    ->extraAttributes(['class' => $inputClass]),
                Forms\Components\TextInput::make('color_score')
                    ->label('色スコア')
                    ->numeric()
                    ->required()
                    ->minValue(0)->maxValue(100)
                    ->extraAttributes(['class' => $inputClass]),
                Forms\Components\DatePicker::make('inspected_at')
                    ->label('検査日')
                    ->required()
                    ->extraAttributes(['class' => $inputClass]),
                Forms\Components\Select::make('supplier_id')
                    ->label('サプライヤー')
                    ->relationship('supplier', 'name')
                    ->required()
                    ->extraAttributes(['class' => $inputClass]),
                Forms\Components\Select::make('inspector_id')
                    ->label('検査員')
                    ->relationship('inspector', 'name')
                    ->required()
                    ->extraAttributes(['class' => $inputClass]),
            ]);
    }

    public static function table(Table $table): Table
    {
        $isMobile = request()->header('user-agent') && preg_match('/Mobile|Android|iPhone|iPad/i', request()->header('user-agent'));
        return $table
            ->columns([
                TextColumn::make('batch_code')->label('ロット番号')->searchable(),
                TextColumn::make('tea_type')->label('茶種')->searchable()->visible(!$isMobile),
                TextColumn::make('origin')->label('産地')->visible(!$isMobile),
                TextColumn::make('moisture')->label('含水率（%）')->numeric()->sortable(),
                TextColumn::make('aroma_score')->label('香りスコア')->numeric()->sortable()->visible(!$isMobile),
                TextColumn::make('color_score')->label('色スコア')->numeric()->sortable()->visible(!$isMobile),
                TextColumn::make('inspected_at')->label('検査日')->date(),
                TextColumn::make('abnormal')
                    ->label('異常')
                    ->formatStateUsing(fn($state, $record) => (
                        $record->isMoistureAbnormal() || $record->isAromaAbnormal() || $record->isColorAbnormal()
                    ) ? '異常' : '')
                    ->badge()
                    ->color(fn($state) => $state === '異常' ? 'danger' : 'gray'),
            ])
            ->contentGrid(['md' => 2, 'xl' => 3])
            ->filters([
                Tables\Filters\Filter::make('batch_code')
                    ->form([
                        Forms\Components\TextInput::make('value')->label('ロット番号')->placeholder('例: SJK20240724A'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['value']) {
                            $query->where('batch_code', 'like', '%'.$data['value'].'%');
                        }
                    }),
                Tables\Filters\Filter::make('tea_type')
                    ->form([
                        Forms\Components\TextInput::make('value')->label('茶種')->placeholder('例: 煎茶、抹茶'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['value']) {
                            $query->where('tea_type', 'like', '%'.$data['value'].'%');
                        }
                    }),
                Tables\Filters\Filter::make('origin')
                    ->form([
                        Forms\Components\TextInput::make('value')->label('産地')->placeholder('例: 静岡、宇治'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['value']) {
                            $query->where('origin', 'like', '%'.$data['value'].'%');
                        }
                    }),
                Tables\Filters\Filter::make('supplier_id')
                    ->form([
                        Forms\Components\Select::make('value')
                            ->label('サプライヤー')
                            ->options(\App\Models\Supplier::pluck('name', 'id'))
                            ->placeholder('選択してください'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['value']) {
                            $query->where('supplier_id', $data['value']);
                        }
                    }),
                Tables\Filters\Filter::make('inspector_id')
                    ->form([
                        Forms\Components\Select::make('value')
                            ->label('検査員')
                            ->options(\App\Models\Inspector::pluck('name', 'id'))
                            ->placeholder('選択してください'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['value']) {
                            $query->where('inspector_id', $data['value']);
                        }
                    }),
                Tables\Filters\Filter::make('inspected_at')
                    ->form([
                        Forms\Components\DatePicker::make('date_from')->label('開始日'),
                        Forms\Components\DatePicker::make('date_to')->label('終了日'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['date_from']) {
                            $query->where('inspected_at', '>=', $data['date_from']);
                        }
                        if ($data['date_to']) {
                            $query->where('inspected_at', '<=', $data['date_to']);
                        }
                    }),
                Tables\Filters\Filter::make('moisture_range')
                    ->form([
                        Forms\Components\TextInput::make('min')->label('最小値')->numeric()->placeholder('0'),
                        Forms\Components\TextInput::make('max')->label('最大値')->numeric()->placeholder('100'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['min'] !== null) {
                            $query->where('moisture', '>=', $data['min']);
                        }
                        if ($data['max'] !== null) {
                            $query->where('moisture', '<=', $data['max']);
                        }
                    }),
                Tables\Filters\Filter::make('aroma_score_range')
                    ->form([
                        Forms\Components\TextInput::make('min')->label('最小値')->numeric()->placeholder('0'),
                        Forms\Components\TextInput::make('max')->label('最大値')->numeric()->placeholder('100'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['min'] !== null) {
                            $query->where('aroma_score', '>=', $data['min']);
                        }
                        if ($data['max'] !== null) {
                            $query->where('aroma_score', '<=', $data['max']);
                        }
                    }),
                Tables\Filters\Filter::make('color_score_range')
                    ->form([
                        Forms\Components\TextInput::make('min')->label('最小値')->numeric()->placeholder('0'),
                        Forms\Components\TextInput::make('max')->label('最大値')->numeric()->placeholder('100'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['min'] !== null) {
                            $query->where('color_score', '>=', $data['min']);
                        }
                        if ($data['max'] !== null) {
                            $query->where('color_score', '<=', $data['max']);
                        }
                    }),
            ])
            ->actions([
                EditAction::make(),
                Action::make('pdf')
                    ->label('PDF出力')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(fn($record) => static::exportPdf($record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
                ExportAction::make(),
                ImportAction::make(),
            ]);
    }

    public static function exportPdf($record)
    {
        $pdf = Pdf::loadView('pdf.tea_lot', ['record' => $record]);
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="tea_lot_'.$record->batch_code.'.pdf"'
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
            'index' => Pages\ListTeaLots::route('/'),
            'create' => Pages\CreateTeaLot::route('/create'),
            'edit' => Pages\EditTeaLot::route('/{record}/edit'),
        ];
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->hasRole('admin');
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->hasRole('admin');
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasRole('admin');
    }
}
