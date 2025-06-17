<?php

namespace App\Filament\Widgets;

use App\Models\Alat;
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
            'class' => 'w-full', // tambahan untuk memastikan lebar penuh
        ];
    }

protected function getData(): array
{
    $mobils = Mobil::with('alats')->get();
    
    // Gabungkan nomor_plat dengan nama_tim
    $labels = $mobils->map(function ($mobil) {
        $namaTim = $mobil->nama_tim ?? 'Tidak Diketahui';
        return [
        $mobil->nomor_plat,
        "({$mobil->nama_tim})",
    ];
    })->toArray();

    $kondisiLabels = ['Hilang', 'Rusak'];

    $defaultColors = [
        "#0071BC", "#FFD200", "#EF4136", "#4CAF50",
        "#FF9800", "#9E9E9E", "#00BCD4", "#8E24AA",
        "#D81B60", "#795548",
    ];

    $datasets = [];

    foreach ($kondisiLabels as $index => $kondisi) {
        $datasets[] = [
            'label' => ucfirst($kondisi),
            'backgroundColor' => $defaultColors[$index % count($defaultColors)],
            'borderColor' => 'transparent',
            'borderWidth' => 0,
            'data' => $mobils->map(
                fn($mobil) => $mobil->alats->where('status_alat', $kondisi)->count()
            )->toArray(),
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
