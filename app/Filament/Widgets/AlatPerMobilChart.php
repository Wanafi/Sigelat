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
        $labels = $mobils->pluck('nomor_plat')->toArray();
        $kondisiLabels = ['Habis', 'Rusak'];

        $defaultColors = [
            "#0071BC", // Biru PLN
            "#FFD200", // Kuning PLN
            "#EF4136", // Merah PLN
            "#4CAF50", // Hijau (stabil)
            "#FF9800", // Oranye (semangat)
            "#9E9E9E", // Abu-abu netral
            "#00BCD4", // Cyan (segar)
            "#8E24AA", // Ungu
            "#D81B60", // Pink tua
            "#795548", // Coklat
        ];

        $datasets = [];

        foreach ($kondisiLabels as $index => $kondisi) {
            $datasets[] = [
                'label' => ucfirst($kondisi),
                'backgroundColor' => $defaultColors[$index % count($defaultColors)],
                'borderColor' => 'transparent',
                'borderWidth' => 0,
                'data' => $mobils->map(fn($mobil) => $mobil->alats->where('status_alat', $kondisi)->count())->toArray(),
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
