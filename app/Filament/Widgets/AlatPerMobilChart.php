<?php

namespace App\Filament\Widgets;

use App\Models\Alat;
use App\Models\Mobil;
use Filament\Widgets\BarChartWidget;

class AlatPerMobilChart extends BarChartWidget
{
    protected static ?string $heading = 'Kondisi Alat per Mobil';

    protected function getData(): array
    {
        $mobils = Mobil::with('alats')->get();
        $labels = $mobils->pluck('nomor_plat')->toArray();
        $kondisiLabels = Alat::select('status_alat')->distinct()->pluck('status_alat')->toArray();

        $defaultColors = [
            '#60A5FA',
            '#34D399',
            '#FBBF24',
            '#F87171',
            '#A78BFA',
            '#FB7185',
            '#FACC15',
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
            'animation' => [
                'duration' => 1000,
                'easing' => 'easeOutBounce',
            ],
            'scales' => [
                'x' => ['stacked' => true],
                'y' => ['stacked' => true],
            ],
            'maintainAspectRatio' => false,
            'responsive' => true,
        ];
    }

    // 👇 Ini yang bikin container-nya full width
}
