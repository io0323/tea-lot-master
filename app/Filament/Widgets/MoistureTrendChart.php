<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Filament\Forms;
use App\Models\Supplier;
use App\Models\TeaLot;

class MoistureTrendChart extends ChartWidget
{
    protected static ?string $heading = '日別 含水率推移';

    protected function getFormSchema(): array
    {
        $teaTypes = TeaLot::query()->distinct()->pluck('tea_type', 'tea_type');
        return [
            Forms\Components\Select::make('supplier_id')
                ->label('サプライヤー')
                ->options(Supplier::pluck('name', 'id'))
                ->searchable()
                ->placeholder('全て')
                ->reactive(),
            Forms\Components\Select::make('tea_type')
                ->label('茶種')
                ->options($teaTypes)
                ->placeholder('全て')
                ->reactive(),
            Forms\Components\DatePicker::make('date_from')
                ->label('開始日')
                ->reactive(),
            Forms\Components\DatePicker::make('date_to')
                ->label('終了日')
                ->reactive(),
        ];
    }

    protected function getData(): array
    {
        $data = $this->getFilteredData();
        $barColors = $this->getBarColors($data->pluck('avg_moisture')->toArray(), 'moisture');

        return [
            'labels' => $data->pluck('inspected_at')->map(fn($d) => date('Y-m-d', strtotime($d)))->toArray(),
            'datasets' => [
                [
                    'label' => '平均含水率（%）',
                    'data' => $data->pluck('avg_moisture')->map(fn($v) => round($v, 2))->toArray(),
                    'backgroundColor' => $barColors,
                ],
            ],
        ];
    }

    private function getFilteredData()
    {
        $query = TeaLot::query();
        
        if ($this->filter['supplier_id'] ?? null) {
            $query->where('supplier_id', $this->filter['supplier_id']);
        }
        if ($this->filter['tea_type'] ?? null) {
            $query->where('tea_type', $this->filter['tea_type']);
        }
        if ($this->filter['date_from'] ?? null) {
            $query->where('inspected_at', '>=', $this->filter['date_from']);
        }
        if ($this->filter['date_to'] ?? null) {
            $query->where('inspected_at', '<=', $this->filter['date_to']);
        }
        
        return $query
            ->selectRaw('inspected_at, AVG(moisture) as avg_moisture')
            ->groupBy('inspected_at')
            ->orderBy('inspected_at')
            ->get();
    }

    private function getBarColors(array $values, string $column): array
    {
        $allValues = TeaLot::pluck($column)->toArray();
        $avg = count($allValues) > 0 ? array_sum($allValues) / count($allValues) : 0;
        $std = 0;
        
        if (count($allValues) > 1) {
            $variance = array_sum(array_map(fn($x) => pow($x - $avg, 2), $allValues)) / (count($allValues) - 1);
            $std = sqrt($variance);
        }
        
        return array_map(function($v) use ($avg, $std) {
            if ($std > 0 && abs($v - $avg) > 2 * $std) {
                return '#ef4444'; // 赤
            }
            return '#60a5fa'; // 通常（青）
        }, $values);
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
