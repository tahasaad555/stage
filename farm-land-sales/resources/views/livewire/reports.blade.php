<div>
    <div class="card">
        <div class="card-title">Sales and Performance Reports</div>
        
        <div class="report-date-filter">
            <div class="date-buttons">
                <button class="button button-small {{ $period === 'weekly' ? 'active' : '' }}" 
                        wire:click="setPeriod('weekly')">
                    Last Week
                </button>
                <button class="button button-small {{ $period === 'monthly' ? 'active' : '' }}" 
                        wire:click="setPeriod('monthly')">
                    Last Month
                </button>
                <button class="button button-small {{ $period === 'yearly' ? 'active' : '' }}" 
                        wire:click="setPeriod('yearly')">
                    Last Year
                </button>
            </div>
            
            <div class="custom-date-range">
                <div class="date-input-group">
                    <label for="startDate">From</label>
                    <input type="date" id="startDate" wire:model="startDate">
                </div>
                
                <div class="date-input-group">
                    <label for="endDate">To</label>
                    <input type="date" id="endDate" wire:model="endDate">
                </div>
                
                <button class="button button-small" wire:click="applyCustomDateRange">Apply</button>
            </div>
        </div>
        
        <div class="report-summary">
            <div class="summary-card">
                <div class="summary-title">Total Sales</div>
                <div class="summary-value">€{{ number_format($totalSales, 2) }}</div>
            </div>
            
            <div class="summary-card">
                <div class="summary-title">Commissions</div>
                <div class="summary-value">€{{ number_format($totalCommissions, 2) }}</div>
            </div>
            
            <div class="summary-card">
                <div class="summary-title">New Users</div>
                <div class="summary-value">{{ $newUsers }}</div>
            </div>
            
            <div class="summary-card">
                <div class="summary-title">New Clients</div>
                <div class="summary-value">{{ $newClients }}</div>
            </div>
            
            <div class="summary-card">
                <div class="summary-title">New Suppliers</div>
                <div class="summary-value">{{ $newSuppliers }}</div>
            </div>
            
            <div class="summary-card">
                <div class="summary-title">New Lands</div>
                <div class="summary-value">{{ $newLands }}</div>
            </div>
            
            <div class="summary-card">
                <div class="summary-title">New Equipment</div>
                <div class="summary-value">{{ $newEquipment }}</div>
            </div>
        </div>
        
        <div class="report-chart">
            <h3>Sales Trend</h3>
            <div class="chart-container">
                <canvas id="salesChart" width="800" height="400"></canvas>
            </div>
        </div>
        
        <div class="report-pie-charts">
            <div class="pie-chart-container">
                <h3>Sales by Category</h3>
                <canvas id="categoryChart" width="300" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('salesChart').getContext('2d');
            var categoryCtx = document.getElementById('categoryChart').getContext('2d');
            
            // Sales Line Chart
            var chartData = @json($chartData);
            var labels = chartData.map(function(item) { return item.date; });
            var sales = chartData.map(function(item) { return item.sales; });
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Sales (EUR)',
                        data: sales,
                        backgroundColor: 'rgba(44, 115, 74, 0.2)',
                        borderColor: 'rgba(44, 115, 74, 1)',
                        borderWidth: 2,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            
            // Category Pie Chart
            var landSales = {{ $landSales }};
            var equipmentSales = {{ $equipmentSales }};
            
            new Chart(categoryCtx, {
                type: 'pie',
                data: {
                    labels: ['Agricultural Lands', 'Farm Equipment'],
                    datasets: [{
                        data: [landSales, equipmentSales],
                        backgroundColor: [
                            'rgba(44, 115, 74, 0.7)',
                            'rgba(54, 162, 235, 0.7)'
                        ],
                        borderColor: [
                            'rgba(44, 115, 74, 1)',
                            'rgba(54, 162, 235, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true
                }
            });
        });
        
        // Update charts when Livewire updates the component
        document.addEventListener('livewire:load', function() {
            Livewire.hook('message.processed', (message, component) => {
                if (component.id === '{{ $_instance->id }}') {
                    // Re-initialize charts with new data
                    setTimeout(function() {
                        document.dispatchEvent(new Event('DOMContentLoaded'));
                    }, 100);
                }
            });
        });
    </script>
</div>