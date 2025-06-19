<?php

namespace App\Filament\Widgets;

use App\Models\Mobil;
use Filament\Widgets\BarChartWidget;

class AlatPerMobilChart extends BarChartWidget
{
    protected static ?string $heading = 'Distribusi Kondisi Alat Per Mobil';

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
        $mobils = Mobil::with('detail_alats')->get();

        $labels = $mobils->map(function ($mobil) {
            $namaTim = $mobil->nama_tim ?? 'Tidak Diketahui';
            return [$mobil->nomor_plat, "({$namaTim})"]; // array = baris terpisah
        })->toArray();


        $kondisiLabels = ['Hilang', 'Rusak'];
        $defaultColors = [
            "#EF4136",
            "#FFD200",
            "#4CAF50",
            "#0071BC",
            "#FF9800",
            "#9E9E9E",
            "#00BCD4",
            "#8E24AA",
            "#D81B60",
            "#795548",
        ];

        $datasets = [];

        foreach ($kondisiLabels as $index => $kondisi) {
            $datasets[] = [
                'label' => ucfirst($kondisi),
                'backgroundColor' => $defaultColors[$index % count($defaultColors)],
                'borderColor' => 'transparent',
                'borderWidth' => 0,
                'data' => $mobils->map(function ($mobil) use ($kondisi) {
                    return $mobil->detail_alats
                        ? $mobil->detail_alats->where('kondisi', $kondisi)->count()
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
