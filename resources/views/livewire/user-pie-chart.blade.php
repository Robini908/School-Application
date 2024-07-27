<div class="col-lg-6">
    <div class="card bg-light shadow-sm h-100">
        <div class="card-header bg-success text-white">
            <h5 class="card-title mb-0">All Users (Pie Chart)</h5>
        </div>
        <div class="card-body">
            <canvas id="userPieChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('livewire:load', function () {
        const userCounts = @json($userCounts);

        const ctx = document.getElementById('userPieChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: Object.keys(userCounts),
                datasets: [{
                    data: Object.values(userCounts),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return context.label + ': ' + context.raw;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush

