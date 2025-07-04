<?php

namespace App\Filament\Widgets;

use App\Models\Mobil;
use Filament\Widgets\BarChartWidget;

class AlatPerMobilChart extends BarChartWidget
{
    protected static ?string $heading = 'Kondisi Alat Per Mobil';
    protected static ?string $maxWidth = '7xl';

    public function getColumnSpan(): int|string|array
    {
        return 'full';
    }

    public function getExtraAttributes(): array
    {
        return [
            'class' => 'w-full',
        ];
    }

    protected function getData(): array
    {
        // Ambil semua mobil beserta relasi alats
        $mobils = Mobil::with('alats')->get();

        $labels = $mobils->map(function ($mobil) {
            $namaTim = $mobil->nama_tim ?? 'Tidak Diketahui';
            return [$mobil->nomor_plat, "({$namaTim})"];
        })->toArray();

        $kondisiLabels = ['Hilang', 'Rusak'];
        $defaultColors = ["#0089B6", "#FFD200"];

        $datasets = [];

        foreach ($kondisiLabels as $index => $kondisi) {
            $datasets[] = [
                'label' => ucfirst($kondisi),
                'backgroundColor' => $defaultColors[$index % count($defaultColors)],
                'borderColor' => 'transparent',
                'borderWidth' => 0,
                'data' => $mobils->map(function ($mobil) use ($kondisi) {
                    return $mobil->alats
                        ? $mobil->alats->where('status_alat', $kondisi)->count()
                        : 0;
                })->toArray(),
            ];
        }

        return [
            'labels' => $labels,
            'datasets' => $datasets,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'borderRadius' => 5,
            'scales' => [
                'x' => ['stacked' => true],
                'y' => ['stacked' => true],
            ],
        ];
    }
}
