<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeaLot extends Model
{
    /**
     * サプライヤーリレーション
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * 検査員リレーション
     */
    public function inspector()
    {
        return $this->belongsTo(Inspector::class);
    }

    /**
     * 含水率が異常値か判定（平均±2σ）
     */
    public function isMoistureAbnormal(): bool
    {
        return $this->isAbnormal('moisture', $this->moisture);
    }

    /**
     * 香りスコアが異常値か判定（平均±2σ）
     */
    public function isAromaAbnormal(): bool
    {
        return $this->isAbnormal('aroma_score', $this->aroma_score);
    }

    /**
     * 色スコアが異常値か判定（平均±2σ）
     */
    public function isColorAbnormal(): bool
    {
        return $this->isAbnormal('color_score', $this->color_score);
    }

    /**
     * 異常値判定の共通ロジック（平均±2σ）
     */
    private function isAbnormal(string $column, float $value): bool
    {
        $values = self::pluck($column)->toArray();
        if (count($values) < 2) return false;
        
        $avg = array_sum($values) / count($values);
        $variance = array_sum(array_map(fn($x) => pow($x - $avg, 2), $values)) / (count($values) - 1);
        $std = sqrt($variance);
        
        return $std > 0 && abs($value - $avg) > 2 * $std;
    }
}
