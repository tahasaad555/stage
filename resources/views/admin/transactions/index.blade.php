@extends('layouts.admin')

@section('title', 'Transactions Management')

@section('content')
<div class="space-y-8">
    <!-- Enhanced Header -->
    <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-700 rounded-2xl p-8 text-white shadow-2xl">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-4xl font-bold mb-2">💰 Transaction Management Center</h1>
                <p class="text-xl opacity-90 mb-4">Monitor, analyze, and manage all platform financial transactions</p>
                <div class="flex items-center space-x-6 text-sm">
                    <div class="flex items-center">
                        <i class="fas fa-credit-card mr-2"></i>
                        <span>{{ number_format($stats['total']) }} Total Transactions</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-dollar-sign mr-2"></i>
                        <span>${{ number_format($stats['total_revenue'], 0) }} Revenue</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-chart-line mr-2"></i>
                        <span>{{ $stats['total'] > 0 ? number_format(($stats['completed'] / $stats['total']) * 100, 1) : 0 }}% Success Rate</span>
                    </div>
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="w-32 h-32 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-pie text-6xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Transactions Card -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all duration-300 card-hover">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-4">
                        <div class="w-14 h-14 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-credit-card text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total Transactions</p>
                            <h3 class="text-3xl font-bold">{{ number_format($stats['total']) }}</h3>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-blue-200">Completed:</span>
                            <span class="font-semibold">{{ $stats['completed'] }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-blue-200">Pending:</span>
                            <span class="font-semibold">{{ $stats['pending'] }}</span>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full" style="width: {{ $stats['total'] > 0 ? round(($stats['completed'] / $stats['total']) * 100) : 0 }}%"></div>
                        </div>
                        <p class="text-xs text-blue-200">{{ $stats['total'] > 0 ? round(($stats['completed'] / $stats['total']) * 100) : 0 }}% completion rate</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all duration-300 card-hover">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-4">
                        <div class="w-14 h-14 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-dollar-sign text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-green-100 text-sm font-medium">Total Revenue</p>
                            <h3 class="text-3xl font-bold">${{ number_format($stats['total_revenue'], 0) }}</h3>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-green-200">Average:</span>
                            <span class="font-semibold">${{ number_format($stats['avg_amount'], 0) }}</span>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full" style="width: 85%"></div>
                        </div>
                        <p class="text-xs text-green-200">Revenue growth trending</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Commission Earnings -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all duration-300 card-hover">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-4">
                        <div class="w-14 h-14 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-percentage text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Commission Earned</p>
                            <h3 class="text-3xl font-bold">${{ number_format($stats['total_commission'], 0) }}</h3>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-purple-200">Rate:</span>
                            <span class="font-semibold">{{ $stats['total_revenue'] > 0 ? number_format(($stats['total_commission'] / $stats['total_revenue']) * 100, 1) : 0 }}%</span>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full" style="width: {{ $stats['total_revenue'] > 0 ? min(round(($stats['total_commission'] / $stats['total_revenue']) * 100), 100) : 0 }}%"></div>
                        </div>
                        <p class="text-xs text-purple-200">Platform earnings</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Rate -->
        <div class="bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all duration-300 card-hover">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-4">
                        <div class="w-14 h-14 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-chart-line text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-yellow-100 text-sm font-medium">Success Rate</p>
                            <h3 class="text-3xl font-bold">{{ $stats['total'] > 0 ? number_format(($stats['completed'] / $stats['total']) * 100, 1) : 0 }}%</h3>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-yellow-200">Failed:</span>
                            <span class="font-semibold">{{ $stats['failed'] }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-yellow-200">Cancelled:</span>
                            <span class="font-semibold">{{ $stats['cancelled'] }}</span>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full" style="width: {{ $stats['total'] > 0 ? round(($stats['completed'] / $stats['total']) * 100) : 0 }}%"></div>
                        </div>
                        <p class="text-xs text-yellow-200">Transaction reliability</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Filters -->
    <div class="glass-effect rounded-2xl p-8 shadow-xl">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">🔍 Advanced Transaction Filters</h3>
                <p class="text-gray-600">Filter and search transactions with precision controls</p>
            </div>
            <div class="flex items-center space-x-3">
                <button onclick="exportTransactions()" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all">
                    <i class="fas fa-download mr-2"></i>Export Data
                </button>
                <button onclick="generateReport()" class="bg-gradient-to-r from-purple-500 to-purple-600 text-white px-4 py-2 rounded-lg hover:from-purple-600 hover:to-purple-700 transition-all">
                    <i class="fas fa-chart-bar mr-2"></i>Generate Report
                </button>
            </div>
        </div>
        
        <form method="GET" action="{{ route('admin.transactions.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search mr-2 text-blue-500"></i>Search Transactions
                </label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Reference, client, supplier..."
                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-toggle-on mr-2 text-green-500"></i>Transaction Status
                </label>
                <select name="status" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>🟡 Pending</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>🟢 Completed</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>🔴 Failed</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>⚫ Cancelled</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-credit-card mr-2 text-purple-500"></i>Payment Method
                </label>
                <select name="payment_method" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                    <option value="">All Methods</option>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method }}" {{ request('payment_method') === $method ? 'selected' : '' }}>
                            💳 {{ ucfirst($method) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar mr-2 text-indigo-500"></i>Date From
                </label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar mr-2 text-pink-500"></i>Date To
                </label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all">
            </div>

            <div class="flex items-end space-x-3">
                <button type="submit" class="flex-1 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white py-3 px-6 rounded-xl hover:from-emerald-600 hover:to-emerald-700 transition-all flex items-center justify-center">
                    <i class="fas fa-search mr-2"></i> Search
                </button>
                <a href="{{ route('admin.transactions.index') }}" class="bg-gray-200 text-gray-700 py-3 px-4 rounded-xl hover:bg-gray-300 transition-all">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Enhanced Transactions Table -->
    <div class="glass-effect rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 border-b">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">💼 Transaction Portfolio</h3>
                    <p class="text-gray-600">{{ $transactions->total() }} transactions found • ${{ number_format($stats['total_revenue'], 0) }} total value</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Sort options -->
                    <select onchange="sortTransactions(this.value)" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500">
                        <option value="newest">📅 Sort: Newest</option>
                        <option value="oldest">📅 Sort: Oldest</option>
                        <option value="amount_high">💰 Sort: Amount High-Low</option>
                        <option value="amount_low">💰 Sort: Amount Low-High</option>
                        <option value="status">📊 Sort: Status</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gradient-to-r from-gray-100 to-gray-200">
                    <tr>
                        <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-hashtag mr-2 text-blue-500"></i>Transaction
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-user mr-2 text-green-500"></i>Client
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-building mr-2 text-purple-500"></i>Supplier
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>Land Property
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-dollar-sign mr-2 text-yellow-500"></i>Amount
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-chart-pie mr-2 text-indigo-500"></i>Status
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-calendar mr-2 text-pink-500"></i>Date
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-cogs mr-2 text-gray-500"></i>Actions
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-gradient-to-r hover:from-emerald-50 hover:to-teal-50 transition-all duration-300">
                            <td class="px-8 py-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        <div class="h-12 w-12 rounded-xl bg-gradient-to-r from-{{ $transaction->status === 'completed' ? 'green' : ($transaction->status === 'pending' ? 'yellow' : 'red') }}-400 to-{{ $transaction->status === 'completed' ? 'green' : ($transaction->status === 'pending' ? 'orange' : 'red') }}-500 flex items-center justify-center shadow-lg">
                                            <i class="fas fa-{{ $transaction->status === 'completed' ? 'check' : ($transaction->status === 'pending' ? 'clock' : 'times') }} text-white"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-lg font-bold text-gray-900">
                                            <a href="{{ route('admin.transactions.show', $transaction) }}" class="hover:text-emerald-600 transition-colors">
                                                #{{ $transaction->id }}
                                            </a>
                                        </div>
                                        @if($transaction->payment_reference)
                                            <div class="text-sm text-gray-500 font-mono bg-gray-100 px-2 py-1 rounded">{{ $transaction->payment_reference }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-6">
                                @if($transaction->client && $transaction->client->user)
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-xl bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
                                                <span class="text-white font-bold text-sm">
                                                    {{ substr($transaction->client->user->first_name, 0, 1) }}{{ substr($transaction->client->user->last_name, 0, 1) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">
                                                <a href="{{ route('admin.users.show', $transaction->client->user) }}" class="hover:text-blue-600 transition-colors">
                                                    {{ $transaction->client->user->full_name }}
                                                </a>
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $transaction->client->user->email }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                        Client not found
                                    </span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-6">
                                @if($transaction->fournisseur && $transaction->fournisseur->user)
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-xl bg-gradient-to-r from-green-400 to-emerald-500 flex items-center justify-center">
                                                <span class="text-white font-bold text-sm">
                                                    {{ substr($transaction->fournisseur->user->first_name, 0, 1) }}{{ substr($transaction->fournisseur->user->last_name, 0, 1) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">
                                                <a href="{{ route('admin.users.show', $transaction->fournisseur->user) }}" class="hover:text-green-600 transition-colors">
                                                    {{ $transaction->fournisseur->user->full_name }}
                                                </a>
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $transaction->fournisseur->company_name ?: 'No company' }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                        Supplier not found
                                    </span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-6">
                                @if($transaction->terreAgricole)
                                    <div class="space-y-1">
                                        <div class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('admin.lands.show', $transaction->terreAgricole) }}" class="hover:text-emerald-600 transition-colors">
                                                {{ Str::limit($transaction->terreAgricole->title, 25) }}
                                            </a>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-500">
                                            <i class="fas fa-map-marker-alt text-red-400 mr-1"></i>
                                            {{ $transaction->terreAgricole->region }}
                                        </div>
                                    </div>
                                @else
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                        Land not found
                                    </span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-6">
                                <div class="space-y-2">
                                    <div class="text-lg font-bold text-gray-900">${{ number_format($transaction->amount, 2) }}</div>
                                    @if($transaction->commission > 0)
                                        <div class="bg-gradient-to-r from-purple-100 to-purple-200 text-purple-800 px-2 py-1 rounded-lg text-xs font-semibold">
                                            Commission: ${{ number_format($transaction->commission, 2) }}
                                        </div>
                                    @endif
                                    @if($transaction->payment_method)
                                        <div class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">
                                            {{ ucfirst($transaction->payment_method) }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            
                            <td class="px-6 py-6">
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                                    @if($transaction->status === 'completed') bg-gradient-to-r from-green-100 to-green-200 text-green-800
                                    @elseif($transaction->status === 'pending') bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800
                                    @elseif($transaction->status === 'failed') bg-gradient-to-r from-red-100 to-red-200 text-red-800
                                    @else bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800 @endif">
                                    @if($transaction->status === 'completed') 🟢 Completed
                                    @elseif($transaction->status === 'pending') 🟡 Pending
                                    @elseif($transaction->status === 'failed') 🔴 Failed
                                    @else ⚫ {{ ucfirst($transaction->status) }} @endif
                                </span>
                            </td>
                            
                            <td class="px-6 py-6">
                                <div class="space-y-1">
                                    <div class="text-sm font-medium text-gray-900">{{ $transaction->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $transaction->created_at->format('H:i') }} • {{ $transaction->created_at->diffForHumans() }}</div>
                                    @if($transaction->completed_at)
                                        <div class="text-xs text-green-600">Completed: {{ $transaction->completed_at->format('M d, H:i') }}</div>
                                    @endif
                                </div>
                            </td>
                            
                            <td class="px-6 py-6">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('admin.transactions.show', $transaction) }}" 
                                       class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white p-2 rounded-lg hover:from-emerald-600 hover:to-emerald-700 transition-all transform hover:scale-110" 
                                       title="View Details">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    
                                    @if(in_array($transaction->status, ['pending', 'failed']))
                                        <button onclick="updateTransactionStatus({{ $transaction->id }})" 
                                                class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-2 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all transform hover:scale-110" 
                                                title="Update Status">
                                            <i class="fas fa-edit text-sm"></i>
                                        </button>
                                    @endif
                                    
                                    @if($transaction->status !== 'completed')
                                        <button onclick="deleteTransaction({{ $transaction->id }})" 
                                                class="bg-gradient-to-r from-red-500 to-red-600 text-white p-2 rounded-lg hover:from-red-600 hover:to-red-700 transition-all transform hover:scale-110" 
                                                title="Delete Transaction">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-24 h-24 bg-gradient-to-r from-gray-200 to-gray-300 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-credit-card text-4xl text-gray-400"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">No Transactions Found</h3>
                                    <p class="text-gray-600 mb-4">No transactions match your current filters</p>
                                    <a href="{{ route('admin.transactions.index') }}" class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white px-6 py-3 rounded-lg hover:from-emerald-600 hover:to-emerald-700 transition-all">
                                        <i class="fas fa-refresh mr-2"></i>Reset Filters
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Enhanced Pagination -->
        @if($transactions->hasPages())
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-t">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <span>Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }} of {{ $transactions->total() }} transactions</span>
                    </div>
                    <div class="flex items-center space-x-1">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Transaction Analytics Dashboard -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Transaction Trends Chart -->
        <div class="glass-effect rounded-2xl p-8 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">📈 Transaction Trends</h3>
                    <p class="text-gray-600">Monthly transaction volume and revenue</p>
                </div>
            </div>
            <div class="relative h-64">
                <canvas id="transactionTrendsChart"></canvas>
            </div>
        </div>

        <!-- Status Distribution -->
        <div class="glass-effect rounded-2xl p-8 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">📊 Status Distribution</h3>
                    <p class="text-gray-600">Transaction status breakdown</p>
                </div>
            </div>
            
            <!-- Status Breakdown -->
            <div class="space-y-4 mb-6">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600 flex items-center">
                            <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                            Completed
                        </span>
                        <span class="font-semibold">{{ $stats['completed'] }} ({{ $stats['total'] > 0 ? round(($stats['completed'] / $stats['total']) * 100) : 0 }}%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 h-2 rounded-full" style="width: {{ $stats['total'] > 0 ? round(($stats['completed'] / $stats['total']) * 100) : 0 }}%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600 flex items-center">
                            <span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>
                            Pending
                        </span>
                        <span class="font-semibold">{{ $stats['pending'] }} ({{ $stats['total'] > 0 ? round(($stats['pending'] / $stats['total']) * 100) : 0 }}%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 h-2 rounded-full" style="width: {{ $stats['total'] > 0 ? round(($stats['pending'] / $stats['total']) * 100) : 0 }}%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600 flex items-center">
                            <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                            Failed
                        </span>
                        <span class="font-semibold">{{ $stats['failed'] }} ({{ $stats['total'] > 0 ? round(($stats['failed'] / $stats['total']) * 100) : 0 }}%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-red-500 to-red-600 h-2 rounded-full" style="width: {{ $stats['total'] > 0 ? round(($stats['failed'] / $stats['total']) * 100) : 0 }}%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600 flex items-center">
                            <span class="w-3 h-3 bg-gray-500 rounded-full mr-2"></span>
                            Cancelled
                        </span>
                        <span class="font-semibold">{{ $stats['cancelled'] }} ({{ $stats['total'] > 0 ? round(($stats['cancelled'] / $stats['total']) * 100) : 0 }}%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-gray-500 to-gray-600 h-2 rounded-full" style="width: {{ $stats['total'] > 0 ? round(($stats['cancelled'] / $stats['total']) * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Pie Chart -->
            <div class="relative h-48">
                <canvas id="statusDistributionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions Panel -->
    <div class="glass-effect rounded-2xl p-8 shadow-xl">
        <h3 class="text-2xl font-bold text-gray-800 mb-6">⚡ Quick Transaction Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <button onclick="exportTransactions()" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all transform hover:scale-105 group">
                <div class="flex items-center justify-between mb-4">
                    <i class="fas fa-file-export text-3xl group-hover:scale-110 transition-transform"></i>
                    <span class="bg-white bg-opacity-20 px-2 py-1 rounded-full text-xs">Export</span>
                </div>
                <h4 class="font-bold text-lg mb-2">Export Data</h4>
                <p class="text-sm opacity-90">Download transaction reports</p>
            </button>
            
            <button onclick="generateReport()" class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-xl hover:from-purple-600 hover:to-purple-700 transition-all transform hover:scale-105 group">
                <div class="flex items-center justify-between mb-4">
                    <i class="fas fa-chart-bar text-3xl group-hover:scale-110 transition-transform"></i>
                    <span class="bg-white bg-opacity-20 px-2 py-1 rounded-full text-xs">Report</span>
                </div>
                <h4 class="font-bold text-lg mb-2">Analytics Report</h4>
                <p class="text-sm opacity-90">Detailed financial analysis</p>
            </button>
            
            <button onclick="reconcileTransactions()" class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-xl hover:from-green-600 hover:to-green-700 transition-all transform hover:scale-105 group">
                <div class="flex items-center justify-between mb-4">
                    <i class="fas fa-balance-scale text-3xl group-hover:scale-110 transition-transform"></i>
                    <span class="bg-white bg-opacity-20 px-2 py-1 rounded-full text-xs">Finance</span>
                </div>
                <h4 class="font-bold text-lg mb-2">Reconcile</h4>
                <p class="text-sm opacity-90">Match payment records</p>
            </button>
            
            <button onclick="bulkUpdateStatus()" class="bg-gradient-to-r from-orange-500 to-red-500 text-white p-6 rounded-xl hover:from-orange-600 hover:to-red-600 transition-all transform hover:scale-105 group">
                <div class="flex items-center justify-between mb-4">
                    <i class="fas fa-tasks text-3xl group-hover:scale-110 transition-transform"></i>
                    <span class="bg-white bg-opacity-20 px-2 py-1 rounded-full text-xs">Bulk</span>
                </div>
                <h4 class="font-bold text-lg mb-2">Bulk Update</h4>
                <p class="text-sm opacity-90">Update multiple transactions</p>
            </button>
        </div>
    </div>
</div>

@push('styles')
<style>
.glass-effect {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.18);
}

.card-hover:hover {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Animation for cards */
@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card-hover {
    animation: slideUp 0.6s ease-out;
}

/* Custom scrollbar */
.overflow-x-auto::-webkit-scrollbar {
    height: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Table hover effects */
tbody tr:hover {
    background: linear-gradient(90deg, rgba(16, 185, 129, 0.05) 0%, rgba(20, 184, 166, 0.05) 100%);
}

/* Loading state */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid #ccc;
    border-top: 2px solid #10b981;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Set CSRF token for AJAX requests
window.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

// Transaction Trends Chart
const trendsCtx = document.getElementById('transactionTrendsChart').getContext('2d');
new Chart(trendsCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Transaction Volume',
            data: [15, 25, 20, 35, 30, 42],
            borderColor: '#10b981',
            backgroundColor: function(context) {
                const chart = context.chart;
                const {ctx, chartArea} = chart;
                if (!chartArea) return null;
                
                const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                gradient.addColorStop(0, 'rgba(16, 185, 129, 0.3)');
                gradient.addColorStop(1, 'rgba(16, 185, 129, 0.05)');
                return gradient;
            },
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#10b981',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 3,
            pointRadius: 6
        }, {
            label: 'Revenue ($1000s)',
            data: [25, 40, 35, 55, 50, 68],
            borderColor: '#3b82f6',
            backgroundColor: function(context) {
                const chart = context.chart;
                const {ctx, chartArea} = chart;
                if (!chartArea) return null;
                
                const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                gradient.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
                gradient.addColorStop(1, 'rgba(59, 130, 246, 0.05)');
                return gradient;
            },
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#3b82f6',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 3,
            pointRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { 
                position: 'top',
                labels: {
                    usePointStyle: true,
                    padding: 20
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: '#ffffff',
                bodyColor: '#ffffff',
                borderColor: '#10b981',
                borderWidth: 1,
                cornerRadius: 8
            }
        },
        scales: {
            x: {
                grid: { display: false },
                border: { display: false }
            },
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(0, 0, 0, 0.05)' },
                border: { display: false }
            }
        }
    }
});

// Status Distribution Chart
const statusCtx = document.getElementById('statusDistributionChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Completed', 'Pending', 'Failed', 'Cancelled'],
        datasets: [{
            data: [{{ $stats['completed'] }}, {{ $stats['pending'] }}, {{ $stats['failed'] }}, {{ $stats['cancelled'] }}],
            backgroundColor: [
                'rgba(16, 185, 129, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(239, 68, 68, 0.8)',
                'rgba(107, 114, 128, 0.8)'
            ],
            borderColor: [
                '#10b981',
                '#f59e0b',
                '#ef4444',
                '#6b7280'
            ],
            borderWidth: 2
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
                    usePointStyle: true,
                    font: {
                        size: 12
                    }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: '#ffffff',
                bodyColor: '#ffffff',
                borderColor: '#10b981',
                borderWidth: 1,
                cornerRadius: 8
            }
        }
    }
});

// Enhanced JavaScript functions
async function deleteTransaction(transactionId) {
    if (!confirm('Are you sure you want to delete this transaction? This action cannot be undone.')) return;
    
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin text-sm"></i>';
    button.disabled = true;
    
    try {
        const response = await fetch(`/admin/transactions/${transactionId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert(result.message, 'success');
            // Animate row removal
            const row = button.closest('tr');
            if (row) {
                row.style.transition = 'all 0.5s ease';
                row.style.opacity = '0';
                row.style.transform = 'scale(0.8)';
                setTimeout(() => location.reload(), 1000);
            }
        } else {
            showAlert(result.message, 'error');
            button.innerHTML = originalContent;
            button.disabled = false;
        }
    } catch (error) {
        showAlert('An error occurred', 'error');
        button.innerHTML = originalContent;
        button.disabled = false;
    }
}

// Update Transaction Status
async function updateTransactionStatus(transactionId) {
    const status = prompt('Enter new status (pending, completed, failed, cancelled):');
    if (!status || !['pending', 'completed', 'failed', 'cancelled'].includes(status.toLowerCase())) {
        showAlert('Invalid status. Please use: pending, completed, failed, or cancelled', 'error');
        return;
    }
    
    try {
        const response = await fetch(`/admin/transactions/${transactionId}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            },
            body: JSON.stringify({ status: status.toLowerCase() })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert(result.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert(result.message, 'error');
        }
    } catch (error) {
        showAlert('An error occurred', 'error');
    }
}

// Sort Transactions Function
function sortTransactions(sortType) {
    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.set('sort', sortType);
    currentUrl.searchParams.set('page', '1'); // Reset to first page
    
    window.location.href = currentUrl.toString();
}

// FIXED: Export Transactions Function
function exportTransactions() {
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Exporting...';
    button.disabled = true;
    
    try {
        // Build URL with current filters
        const currentUrl = new URL(window.location.href);
        const searchParams = currentUrl.searchParams;
        
        // Create export URL with filters - FIXED ROUTE
        const exportUrl = new URL('/admin/transactions/export', window.location.origin);
        searchParams.forEach((value, key) => {
            exportUrl.searchParams.set(key, value);
        });
        
        // Create a temporary link and trigger download
        const link = document.createElement('a');
        link.href = exportUrl.toString();
        link.download = '';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        showAlert('Export started successfully!', 'success');
        
    } catch (error) {
        showAlert('Export failed. Please try again.', 'error');
    } finally {
        setTimeout(() => {
            button.innerHTML = originalContent;
            button.disabled = false;
        }, 2000);
    }
}

// FIXED: Generate Report Function
async function generateReport() {
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Generating...';
    button.disabled = true;
    
    try {
        const response = await fetch('/admin/transactions/generate-report', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert('Transaction analytics report generated successfully!', 'success');
            
            // Show report modal with data
            showReportModal(result.data);
        } else {
            showAlert(result.message || 'Report generation failed', 'error');
        }
    } catch (error) {
        showAlert('An error occurred while generating the report', 'error');
    } finally {
        button.innerHTML = originalContent;
        button.disabled = false;
    }
}

// FIXED: Reconcile Transactions Function
async function reconcileTransactions() {
    if (!confirm('Do you want to reconcile all pending transactions with payment records?')) return;
    
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Reconciling...';
    button.disabled = true;
    
    try {
        const response = await fetch('/admin/transactions/reconcile', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert(result.message, 'success');
            setTimeout(() => location.reload(), 2000);
        } else {
            showAlert(result.message || 'Reconciliation failed', 'error');
        }
    } catch (error) {
        showAlert('An error occurred during reconciliation', 'error');
    } finally {
        button.innerHTML = originalContent;
        button.disabled = false;
    }
}

// FIXED: Bulk Update Status Function
async function bulkUpdateStatus() {
    // Create a modal for selecting transactions and status
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-2xl p-8 max-w-2xl w-full mx-4">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-tasks text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">Bulk Update Transactions</h3>
                <p class="text-gray-600">Select transactions and update their status</p>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Status</label>
                    <select id="bulkStatus" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="pending">🟡 Pending</option>
                        <option value="completed">🟢 Completed</option>
                        <option value="failed">🔴 Failed</option>
                        <option value="cancelled">⚫ Cancelled</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Transaction IDs (comma-separated)</label>
                    <textarea id="transactionIds" rows="3" placeholder="Enter transaction IDs separated by commas (e.g., 1,2,3,4)" 
                              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500"></textarea>
                </div>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>
                        <p class="text-yellow-700 text-sm">This action will update multiple transactions at once. Please verify the transaction IDs before proceeding.</p>
                    </div>
                </div>
            </div>
            
            <div class="flex space-x-3 pt-6">
                <button onclick="performBulkUpdate()" class="flex-1 bg-gradient-to-r from-orange-500 to-red-500 text-white py-3 px-6 rounded-lg hover:from-orange-600 hover:to-red-600 transition-all">
                    <i class="fas fa-tasks mr-2"></i>Update Transactions
                </button>
                <button onclick="this.closest('.fixed').remove()" class="bg-gray-200 text-gray-700 py-3 px-6 rounded-lg hover:bg-gray-300 transition-all">
                    Cancel
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Close modal when clicking outside
    modal.addEventListener('click', (e) => {
        if (e.target === modal) modal.remove();
    });
}

// Perform Bulk Update
async function performBulkUpdate() {
    const status = document.getElementById('bulkStatus').value;
    const idsText = document.getElementById('transactionIds').value.trim();
    
    if (!idsText) {
        showAlert('Please enter transaction IDs', 'error');
        return;
    }
    
    const transactionIds = idsText.split(',').map(id => parseInt(id.trim())).filter(id => !isNaN(id));
    
    if (transactionIds.length === 0) {
        showAlert('Please enter valid transaction IDs', 'error');
        return;
    }
    
    try {
        const response = await fetch('/admin/transactions/bulk-update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            },
            body: JSON.stringify({
                transaction_ids: transactionIds,
                status: status
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert(result.message, 'success');
            document.querySelector('.fixed.inset-0').remove();
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert(result.message || 'Bulk update failed', 'error');
        }
    } catch (error) {
        showAlert('An error occurred during bulk update', 'error');
    }
}

// Show Report Modal
function showReportModal(reportData) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-2xl p-8 max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-chart-bar text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">Transaction Analytics Report</h3>
                <p class="text-gray-600">Generated on: ${reportData.generated_at}</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-50 p-4 rounded-xl">
                    <div class="text-2xl font-bold text-blue-600">${reportData.stats.total_transactions}</div>
                    <div class="text-sm text-gray-600">Total Transactions</div>
                </div>
                <div class="bg-green-50 p-4 rounded-xl">
                    <div class="text-2xl font-bold text-green-600">${Math.round(reportData.stats.total_revenue).toLocaleString()}</div>
                    <div class="text-sm text-gray-600">Total Revenue</div>
                </div>
                <div class="bg-purple-50 p-4 rounded-xl">
                    <div class="text-2xl font-bold text-purple-600">${Math.round(reportData.stats.total_commission).toLocaleString()}</div>
                    <div class="text-sm text-gray-600">Total Commission</div>
                </div>
                <div class="bg-yellow-50 p-4 rounded-xl">
                    <div class="text-2xl font-bold text-yellow-600">${Math.round(reportData.stats.completion_rate)}%</div>
                    <div class="text-sm text-gray-600">Completion Rate</div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div>
                    <h4 class="text-lg font-bold mb-3">Monthly Trends</h4>
                    <div class="space-y-2">
                        ${reportData.monthly_data.map(month => `
                            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                <span class="font-medium">${month.month}</span>
                                <div class="text-right">
                                    <div class="font-bold">${month.transactions} transactions</div>
                                    <div class="text-sm text-gray-600">${Math.round(month.revenue).toLocaleString()}</div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
                
                <div>
                    <h4 class="text-lg font-bold mb-3">Top Clients</h4>
                    <div class="space-y-2">
                        ${reportData.top_clients.map(client => `
                            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                <span class="font-medium">${client.client?.user?.full_name || 'Unknown'}</span>
                                <div class="text-right">
                                    <div class="font-bold">${client.transaction_count} transactions</div>
                                    <div class="text-sm text-gray-600">${Math.round(client.total_spent).toLocaleString()}</div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            </div>
            
            <div class="flex justify-center">
                <button onclick="this.closest('.fixed').remove()" class="bg-gradient-to-r from-purple-500 to-purple-600 text-white py-3 px-6 rounded-lg hover:from-purple-600 hover:to-purple-700 transition-all">
                    <i class="fas fa-times mr-2"></i>Close Report
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Close modal when clicking outside
    modal.addEventListener('click', (e) => {
        if (e.target === modal) modal.remove();
    });
}

// Show Alert Function
function showAlert(message, type = 'success') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());
    
    const notification = document.createElement('div');
    notification.className = 'notification fixed top-4 right-4 z-50 p-6 rounded-xl shadow-2xl transform transition-all duration-500';
    
    let bgColor = 'bg-gradient-to-r from-blue-500 to-blue-600';
    let icon = 'fa-info-circle';
    
    switch(type) {
        case 'success':
            bgColor = 'bg-gradient-to-r from-green-500 to-green-600';
            icon = 'fa-check-circle';
            break;
        case 'error':
            bgColor = 'bg-gradient-to-r from-red-500 to-red-600';
            icon = 'fa-exclamation-circle';
            break;
        case 'info':
            bgColor = 'bg-gradient-to-r from-blue-500 to-blue-600';
            icon = 'fa-info-circle';
            break;
    }
    
    notification.className += ` ${bgColor} text-white`;
    
    notification.innerHTML = `
        <div class="flex items-center">
            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
                <i class="fas ${icon}"></i>
            </div>
            <div class="flex-1">
                <div class="font-semibold">${type.charAt(0).toUpperCase() + type.slice(1)}!</div>
                <div class="text-sm opacity-90">${message}</div>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 w-6 h-6 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition-all">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => notification.remove(), 500);
        }
    }, 5000);
}

// Add loading states and animations
document.addEventListener('DOMContentLoaded', function() {
    // Animate cards on load
    const cards = document.querySelectorAll('.card-hover');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Add search form enhancements
    const searchForm = document.querySelector('form');
    if (searchForm) {
        searchForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalContent = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Searching...';
                submitBtn.disabled = true;
            }
        });
    }

    // Add real-time search suggestions (demo)
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let timeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                // Here you could add live search suggestions
                console.log('Searching for:', this.value);
            }, 500);
        });
    }
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + E to export
    if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
        e.preventDefault();
        exportTransactions();
    }
    
    // Ctrl/Cmd + R to generate report
    if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
        e.preventDefault();
        generateReport();
    }
    
    // Ctrl/Cmd + F to focus search
    if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
        e.preventDefault();
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.focus();
            searchInput.select();
        }
    }
});
</script>
@endpush

@endsection