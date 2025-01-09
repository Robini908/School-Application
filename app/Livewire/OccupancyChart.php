<?php

namespace App\Livewire;

use Livewire\Component;
use Asantibanez\LivewireCharts\Models\LineChartModel; // Use the correct class
use Illuminate\Support\Facades\DB;

class OccupancyChart extends Component
{
    public $selectedDormId;
    public $chart;

    public function mount($dormId)
    {
        $this->selectedDormId = $dormId;
        $this->initializeChart();
    }

    public function initializeChart()
    {
        // Fetch occupancy data for the selected dorm
        $occupancyData = DB::table('dorm_student')
            ->select('year', DB::raw('count(*) as occupancy'))
            ->where('dorm_id', $this->selectedDormId)
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        // Initialize the chart using LineChartModel
        $this->chart = (new LineChartModel())
            ->setTitle('Occupancy Over the Years')
            ->setAnimated(true);

        // Add dynamic data points to the chart
        foreach ($occupancyData as $data) {
            $this->chart->addPoint($data->year, $data->occupancy);
        }
    }

    public function render()
    {
        return view('livewire.occupancy-chart', [
            'chart' => $this->chart,
        ]);
    }
}