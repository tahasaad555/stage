<div class="card">
        <div class="card-title">Commission Management</div>
        
        <div class="commission-stats">
            <div class="stat-item">
                <div class="stat-value">{{ $currentCommissionRate }}%</div>
                <div class="stat-label">Current Commission Rate</div>
            </div>
            <div class="stat-item highlight">
                <div class="stat-value">{{ number_format($totalCommissionEarned, 2) }} €</div>
                <div class="stat-label">Total Commission Earned</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ number_format($pendingCommissions, 2) }} €</div>
                <div class="stat-label">Pending Commissions</div>
            </div>
        </div>
        
        <div class="commission-form">
            <h3>Update Commission Rate</h3>
            <form wire:submit.prevent="updateCommissionRate">
                <div class="form-group">
                    <label for="newCommissionRate">New Commission Rate (%)</label>
                    <input type="number" id="newCommissionRate" wire:model="newCommissionRate" min="0" max="100" step="0.01">
                    @error('newCommissionRate') <span class="error">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-help">
                    <p>The commission rate is applied to all new transactions. Existing transactions will not be affected.</p>
                </div>
                
                <button type="submit" class="button">Update Commission Rate</button>
            </form>
        </div>
    </div>
    
    <div class="grid-layout">
        <div class="card">
            <div class="card-title">Monthly Commission Summary</div>
            
            <div class="chart-container">
                <canvas id="monthlyCommissionChart" width="600" height="300"></canvas>
            </div>
            
            <table class="monthly-summary-table">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Commission Earned</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthlySummary as $summary)
                    <tr>
                        <td>{{ $summary['month'] }}</td>
                        <td>{{ number_format($summary['commission'], 2) }} €</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="card">
            <div class="card-title">Recent Completed Transactions</div>
            
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Client</th>
                        <th>Amount</th>
                        <th>Commission</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentTransactions as $transaction)
                    <tr>
                        <td>{{ $transaction->id }}</td>
                        <td>{{ $transaction->dateTransaction->format('M d, Y') }}</td>
                        <td>{{ $transaction->client->nom }} {{ $transaction->client->prenom }}</td>
                        <td>{{ $transaction->montant }} €</td>
                        <td>{{ $transaction->commission }} €</td>
                    </tr>
                    @endforeach
                    
                    @if(count($recentTransactions) === 0)
                    <tr>
                        <td colspan="5" class="empty-table">No completed transactions yet.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            
            <div class="card-footer">
                <a href="{{ route('admin.transactions') }}?status=Completed" class="button">View All Completed Transactions</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('livewire:load', function() {
        var monthlyData = @json($monthlySummary);
        var labels = monthlyData.map(function(item) { return item.month; });
        var data = monthlyData.map(function(item) { return item.commission; });
        
        var ctx = document.getElementById('monthlyCommissionChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Commission Earned (€)',
                    data: data,
                    backgroundColor: 'rgba(44, 115, 74, 0.7)',
                    borderColor: 'rgba(44, 115, 74, 1)',
                    borderWidth: 1
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
        
        // Update chart when Livewire updates the component
        Livewire.hook('message.processed', (message, component) => {
            if (component.id === '{{ $_instance->id }}') {
                var newMonthlyData = component.get('monthlySummary');
                chart.data.labels = newMonthlyData.map(function(item) { return item.month; });
                chart.data.datasets[0].data = newMonthlyData.map(function(item) { return item.commission; });
                chart.update();
            }
        });
    });
</script>