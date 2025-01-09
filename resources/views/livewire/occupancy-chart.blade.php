<div>
    <h3>Occupancy Over the Years</h3>
    <div class="card">
        <div class="card-body">
            @if ($chart)
                <livewire:livewire-line-chart
                    key="{{ $chart->reactiveKey() }}"
                    :line-chart-model="$chart"
                />
            @else
                <p>No chart data available.</p>
            @endif
        </div>
    </div>
</div>