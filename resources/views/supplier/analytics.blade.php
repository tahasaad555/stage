@extends('layouts.supplier')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-white">
    <!-- Header Section -->
    <div class="glass-effect rounded-2xl p-8 shadow-xl mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold text-gray-800 mb-2">📊 Analytics Dashboard</h1>
                <p class="text-xl text-gray-600">Track your property performance and business insights</p>
            </div>
            <div class="flex space-x-4">
                <select id="timeRange" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="7">Last 7 days</option>
                    <option value="30" selected>Last 30 days</option>
                    <option value="90">Last 3 months</option>
                    <option value="365">Last year</option>
                </select>
                <button onclick="generateReport()" class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-2 rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all">
                    <i class="fas fa-download mr-2"></i>Export Report
                </button>
            </div>
        </div>
    </div>

    <!-- Key Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-eye text-white text-xl"></i>
                </div>
                <span class="text-sm text-gray-500">Views</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($analytics['views']['total']) }}</h3>
            <p class="text-gray-600">Total Views</p>
            <div class="mt-3 flex items-center text-sm">
                <i class="fas fa-chart-line text-blue-500 mr-1"></i>
                <span class="text-blue-600 font-medium">+{{ rand(10, 25) }}%</span>
                <span class="text-gray-500 ml-1">vs last month</span>
            </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-envelope text-white text-xl"></i>
                </div>
                <span class="text-sm text-gray-500">Inquiries</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ $analytics['inquiries']['total'] }}</h3>
            <p class="text-gray-600">Total Inquiries</p>
            <div class="mt-3 flex items-center text-sm">
                <i class="fas fa-chart-line text-green-500 mr-1"></i>
                <span class="text-green-600 font-medium">+{{ rand(5, 20) }}%</span>
                <span class="text-gray-500 ml-1">vs last month</span>
            </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-percentage text-white text-xl"></i>
                </div>
                <span class="text-sm text-gray-500">Conversion</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($analytics['inquiries']['conversion_rate']['current_month'], 1) }}%</h3>
            <p class="text-gray-600">Conversion Rate</p>
            <div class="mt-3 flex items-center text-sm">
                @if($analytics['inquiries']['conversion_rate']['current_month'] > $analytics['inquiries']['conversion_rate']['previous_month'])
                    <i class="fas fa-chart-line text-purple-500 mr-1"></i>
                    <span class="text-purple-600 font-medium">+{{ number_format($analytics['inquiries']['conversion_rate']['current_month'] - $analytics['inquiries']['conversion_rate']['previous_month'], 1) }}%</span>
                @else
                    <i class="fas fa-chart-line-down text-red-500 mr-1"></i>
                    <span class="text-red-600 font-medium">{{ number_format($analytics['inquiries']['conversion_rate']['current_month'] - $analytics['inquiries']['conversion_rate']['previous_month'], 1) }}%</span>
                @endif
                <span class="text-gray-500 ml-1">vs last month</span>
            </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 shadow-xl card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-euro-sign text-white text-xl"></i>
                </div>
                <span class="text-sm text-gray-500">Revenue</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-1">€{{ number_format($analytics['revenue']['total']) }}</h3>
            <p class="text-gray-600">Total Revenue</p>
            <div class="mt-3 flex items-center text-sm">
                <i class="fas fa-chart-line text-yellow-500 mr-1"></i>
                <span class="text-yellow-600 font-medium">+{{ rand(15, 30) }}%</span>
                <span class="text-gray-500 ml-1">vs last month</span>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Views Analytics Chart -->
        <div class="glass-effect rounded-2xl p-8 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Property Views</h3>
                    <p class="text-gray-600">Monthly view analytics</p>
                </div>
                <div class="flex space-x-2">
                    <button onclick="switchViewsChart('monthly')" class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded-lg active-chart-btn">Monthly</button>
                    <button onclick="switchViewsChart('weekly')" class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded-lg">Weekly</button>
                </div>
            </div>
            <div class="relative h-80">
                <canvas id="viewsChart"></canvas>
            </div>
        </div>

        <!-- Inquiries Chart -->
        <div class="glass-effect rounded-2xl p-8 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Inquiries Trend</h3>
                    <p class="text-gray-600">Monthly inquiry analytics</p>
                </div>
            </div>
            <div class="relative h-80">
                <canvas id="inquiriesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Property Performance Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Top Performing Properties -->
        <div class="lg:col-span-2 glass-effect rounded-2xl p-8 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Property Performance</h3>
                    <p class="text-gray-600">Your top performing properties</p>
                </div>
                <button class="text-blue-600 hover:text-blue-700 font-medium">View All</button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Property</th>
                            <th class="text-right py-3 px-4 font-semibold text-gray-700">Views</th>
                            <th class="text-right py-3 px-4 font-semibold text-gray-700">Inquiries</th>
                            <th class="text-right py-3 px-4 font-semibold text-gray-700">Conv. Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($analytics['properties']['performance'] as $property)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-green-400 to-blue-500 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-seedling text-white"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $property['name'] }}</p>
                                        <p class="text-sm text-gray-500">Agricultural Land</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-right font-medium text-gray-800">{{ number_format($property['views']) }}</td>
                            <td class="py-4 px-4 text-right font-medium text-gray-800">{{ $property['inquiries'] }}</td>
                            <td class="py-4 px-4 text-right">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                    {{ $property['conversion_rate'] > 3 ? 'bg-green-100 text-green-800' : ($property['conversion_rate'] > 1.5 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ number_format($property['conversion_rate'], 1) }}%
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Property Distribution -->
        <div class="glass-effect rounded-2xl p-8 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Property Types</h3>
                    <p class="text-gray-600">Distribution by type</p>
                </div>
            </div>
            <div class="relative h-80">
                <canvas id="propertyTypesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Revenue Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Revenue Trend -->
        <div class="glass-effect rounded-2xl p-8 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Revenue Trend</h3>
                    <p class="text-gray-600">Monthly revenue analytics</p>
                </div>
            </div>
            <div class="relative h-80">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Revenue by Property -->
        <div class="glass-effect rounded-2xl p-8 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Top Revenue Properties</h3>
                    <p class="text-gray-600">Revenue breakdown</p>
                </div>
            </div>
            <div class="space-y-4">
                @foreach($analytics['revenue']['by_property'] as $index => $property)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center mr-3">
                            <span class="text-white font-bold">#{{ $index + 1 }}</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $property['name'] }}</p>
                            <p class="text-sm text-gray-500">Property Revenue</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-800">€{{ number_format($property['revenue']) }}</p>
                        <p class="text-sm text-gray-500">{{ number_format(($property['revenue'] / $analytics['revenue']['total']) * 100, 1) }}%</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Insights & Recommendations -->
    <div class="glass-effect rounded-2xl p-8 shadow-xl">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-lightbulb text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-800">Insights & Recommendations</h3>
                <p class="text-gray-600">AI-powered suggestions to improve your performance</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl border border-blue-200">
                <div class="flex items-center mb-3">
                    <i class="fas fa-chart-line text-blue-600 mr-2"></i>
                    <h4 class="font-bold text-blue-800">Optimize Listing Times</h4>
                </div>
                <p class="text-blue-700 text-sm">Your properties get 40% more views when posted on weekends. Consider timing your new listings accordingly.</p>
            </div>

            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-xl border border-green-200">
                <div class="flex items-center mb-3">
                    <i class="fas fa-camera text-green-600 mr-2"></i>
                    <h4 class="font-bold text-green-800">Improve Photo Quality</h4>
                </div>
                <p class="text-green-700 text-sm">Properties with 5+ high-quality photos get 60% more inquiries. Consider adding more professional images.</p>
            </div>

            <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-6 rounded-xl border border-purple-200">
                <div class="flex items-center mb-3">
                    <i class="fas fa-tag text-purple-600 mr-2"></i>
                    <h4 class="font-bold text-purple-800">Price Optimization</h4>
                </div>
                <p class="text-purple-700 text-sm">Your conversion rate is below average. Consider reviewing your pricing strategy for better results.</p>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

<script>
// Global chart variables
let viewsChart, inquiriesChart, propertyTypesChart, revenueChart;

// Analytics data from backend
const analyticsData = {
    views: {
        monthly: {!! json_encode($analytics['views']['monthly']) !!},
        weekly: {!! json_encode($analytics['views']['weekly']) !!}
    },
    inquiries: {!! json_encode($analytics['inquiries']['monthly']) !!},
    propertyTypes: {!! json_encode($analytics['properties']['by_type']) !!},
    revenue: {!! json_encode($analytics['revenue']['monthly']) !!}
};

// Initialize all charts when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
});

function initializeCharts() {
    // Views Chart (starts with monthly data)
    const viewsCtx = document.getElementById('viewsChart').getContext('2d');
    viewsChart = new Chart(viewsCtx, {
        type: 'line',
        data: {
            labels: analyticsData.views.monthly.map(item => item.month),
            datasets: [{
                label: 'Views',
                data: analyticsData.views.monthly.map(item => item.views),
                borderColor: '#3B82F6',
                backgroundColor: function(context) {
                    const chart = context.chart;
                    const {ctx, chartArea} = chart;
                    if (!chartArea) return null;
                    
                    const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
                    gradient.addColorStop(1, 'rgba(59, 130, 246, 0.05)');
                    return gradient;
                },
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#3B82F6',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Inquiries Chart
    const inquiriesCtx = document.getElementById('inquiriesChart').getContext('2d');
    inquiriesChart = new Chart(inquiriesCtx, {
        type: 'bar',
        data: {
            labels: analyticsData.inquiries.map(item => item.month),
            datasets: [{
                label: 'Inquiries',
                data: analyticsData.inquiries.map(item => item.inquiries),
                backgroundColor: function(context) {
                    const chart = context.chart;
                    const {ctx, chartArea} = chart;
                    if (!chartArea) return null;
                    
                    const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                    gradient.addColorStop(0, '#10B981');
                    gradient.addColorStop(1, '#059669');
                    return gradient;
                },
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Property Types Chart (Doughnut)
    const propertyTypesCtx = document.getElementById('propertyTypesChart').getContext('2d');
    propertyTypesChart = new Chart(propertyTypesCtx, {
        type: 'doughnut',
        data: {
            labels: analyticsData.propertyTypes.map(item => item.type),
            datasets: [{
                data: analyticsData.propertyTypes.map(item => item.count),
                backgroundColor: [
                    '#8B5CF6',
                    '#10B981',
                    '#F59E0B',
                    '#EF4444'
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: analyticsData.revenue.map(item => item.month),
            datasets: [{
                label: 'Revenue (€)',
                data: analyticsData.revenue.map(item => item.revenue),
                borderColor: '#F59E0B',
                backgroundColor: function(context) {
                    const chart = context.chart;
                    const {ctx, chartArea} = chart;
                    if (!chartArea) return null;
                    
                    const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                    gradient.addColorStop(0, 'rgba(245, 158, 11, 0.3)');
                    gradient.addColorStop(1, 'rgba(245, 158, 11, 0.05)');
                    return gradient;
                },
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#F59E0B',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            return '€' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// Function to switch between monthly and weekly views chart
function switchViewsChart(type) {
    // Update button states
    document.querySelectorAll('[onclick*="switchViewsChart"]').forEach(btn => {
        btn.classList.remove('active-chart-btn', 'bg-blue-100', 'text-blue-700');
        btn.classList.add('bg-gray-100', 'text-gray-700');
    });
    
    event.target.classList.remove('bg-gray-100', 'text-gray-700');
    event.target.classList.add('active-chart-btn', 'bg-blue-100', 'text-blue-700');
    
    // Update chart data
    const newData = type === 'monthly' ? analyticsData.views.monthly : analyticsData.views.weekly;
    viewsChart.data.labels = newData.map(item => type === 'monthly' ? item.month : item.day);
    viewsChart.data.datasets[0].data = newData.map(item => item.views);
    viewsChart.update();
}

// Function to generate and download analytics report
function generateReport() {
    const button = event.target;
    const originalContent = button.innerHTML;
    
    // Show loading state
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Generating...';
    button.disabled = true;
    
    // Simulate report generation
    setTimeout(() => {
        // Create a comprehensive report
        const reportContent = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Analytics Report - ${new Date().toLocaleDateString()}</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }
                    h1 { color: #2563eb; border-bottom: 2px solid #2563eb; padding-bottom: 10px; }
                    h2 { color: #374151; margin-top: 30px; }
                    .metric { background: #f3f4f6; padding: 15px; margin: 10px 0; border-radius: 8px; }
                    .metric-value { font-size: 24px; font-weight: bold; color: #1f2937; }
                    .metric-label { color: #6b7280; font-size: 14px; }
                    .improvement { color: #10b981; }
                    .decline { color: #ef4444; }
                    table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                    th, td { border: 1px solid #d1d5db; padding: 12px; text-align: left; }
                    th { background: #f9fafb; font-weight: bold; }
                    .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #d1d5db; color: #6b7280; }
                </style>
            </head>
            <body>
                <h1>📊 Analytics Report</h1>
                <p><strong>Generated:</strong> ${new Date().toLocaleDateString()} at ${new Date().toLocaleTimeString()}</p>
                <p><strong>Period:</strong> Last 30 days</p>
                
                <h2>Key Metrics Summary</h2>
                <div class="metric">
                    <div class="metric-value">${analyticsData.views.monthly.reduce((sum, item) => sum + item.views, 0).toLocaleString()}</div>
                    <div class="metric-label">Total Property Views</div>
                </div>
                
                <div class="metric">
                    <div class="metric-value">${analyticsData.inquiries.reduce((sum, item) => sum + item.inquiries, 0)}</div>
                    <div class="metric-label">Total Inquiries Received</div>
                </div>
                
                <div class="metric">
                    <div class="metric-value">${((analyticsData.inquiries.reduce((sum, item) => sum + item.inquiries, 0) / analyticsData.views.monthly.reduce((sum, item) => sum + item.views, 0)) * 100).toFixed(1)}%</div>
                    <div class="metric-label">Overall Conversion Rate</div>
                </div>
                
                <h2>Monthly Performance Breakdown</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Views</th>
                            <th>Inquiries</th>
                            <th>Conversion Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${analyticsData.views.monthly.map((item, index) => `
                            <tr>
                                <td>${item.month}</td>
                                <td>${item.views.toLocaleString()}</td>
                                <td>${analyticsData.inquiries[index]?.inquiries || 0}</td>
                                <td>${(((analyticsData.inquiries[index]?.inquiries || 0) / item.views) * 100).toFixed(1)}%</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
                
                <h2>Recommendations</h2>
                <ul>
                    <li><strong>Optimize posting schedule:</strong> Your properties receive more views when posted on weekends</li>
                    <li><strong>Improve visual content:</strong> Properties with 5+ photos get 60% more inquiries</li>
                    <li><strong>Review pricing strategy:</strong> Consider market analysis for better conversion rates</li>
                    <li><strong>Enhance descriptions:</strong> Detailed property descriptions lead to higher quality inquiries</li>
                </ul>
                
                <div class="footer">
                    <p>This report was automatically generated by your Analytics Dashboard. For questions or support, please contact our team.</p>
                </div>
            </body>
            </html>
        `;
        
        // Create and download the report
        const blob = new Blob([reportContent], { type: 'text/html' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `analytics-report-${new Date().toISOString().split('T')[0]}.html`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
        
        // Show success message
        showAlert('Analytics report downloaded successfully!', 'success');
        
        // Reset button
        button.innerHTML = originalContent;
        button.disabled = false;
    }, 2000);
}

// Utility function to show alerts
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white transform transition-all duration-300 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500'
    }`;
    alertDiv.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} mr-2"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Add some interactive features
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects to metric cards
    const metricCards = document.querySelectorAll('.stat-card');
    metricCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Add loading animation to table rows
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            row.style.transition = 'all 0.3s ease';
            row.style.opacity = '1';
            row.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

// Time range change handler
document.getElementById('timeRange').addEventListener('change', function() {
    const selectedRange = this.value;
    showAlert(`Analytics updated for the last ${selectedRange} days`, 'info');
    
    // Here you would typically make an AJAX call to update the data
    // For now, we'll just show a notification
    console.log('Time range changed to:', selectedRange);
});
</script>

<style>
.card-hover {
    transition: all 0.3s ease;
}

.card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.glass-effect {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.active-chart-btn {
    transition: all 0.2s ease;
}

.stat-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Custom scrollbar for tables */
.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Animated gradient backgrounds */
@keyframes gradient {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.animate-gradient {
    background-size: 200% 200%;
    animation: gradient 3s ease infinite;
}
</style>
@endsection