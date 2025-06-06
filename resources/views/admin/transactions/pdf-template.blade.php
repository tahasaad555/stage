<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Receipt #{{ $transaction->id }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #f9f9f9;
        }
        
        .receipt-container {
            background: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #10b981;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #10b981;
            font-size: 2.5em;
            margin: 0;
            font-weight: bold;
        }
        
        .header .subtitle {
            color: #666;
            font-size: 1.2em;
            margin-top: 10px;
        }
        
        .transaction-info {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .transaction-info h2 {
            margin: 0 0 10px 0;
            font-size: 1.8em;
        }
        
        .transaction-info .amount {
            font-size: 3em;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.9em;
        }
        
        .status-completed { background: #d1fae5; color: #065f46; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-failed { background: #fee2e2; color: #991b1b; }
        .status-cancelled { background: #f3f4f6; color: #374151; }
        
        .section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 8px;
            border-left: 4px solid #10b981;
        }
        
        .section h3 {
            color: #10b981;
            margin-top: 0;
            font-size: 1.4em;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 10px;
        }
        
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 15px;
        }
        
        .detail-item {
            background: white;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }
        
        .detail-label {
            font-weight: bold;
            color: #6b7280;
            font-size: 0.9em;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        
        .detail-value {
            color: #111827;
            font-size: 1.1em;
            font-weight: 600;
        }
        
        .financial-summary {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 25px;
            border-radius: 10px;
            margin: 30px 0;
        }
        
        .financial-summary h3 {
            color: white;
            border: none;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .financial-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
        
        .financial-row:last-child {
            border-bottom: 2px solid white;
            font-size: 1.2em;
            font-weight: bold;
            margin-top: 10px;
            padding-top: 15px;
        }
        
        .land-info {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        
        .land-info h4 {
            color: white;
            margin-top: 0;
            font-size: 1.3em;
        }
        
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            color: #6b7280;
        }
        
        .footer .timestamp {
            font-size: 0.9em;
            margin-top: 10px;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 6em;
            color: rgba(16, 185, 129, 0.05);
            font-weight: bold;
            z-index: -1;
        }
        
        @media print {
            body { background: white; }
            .receipt-container { box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="watermark">AGRITERRE</div>
    
    <div class="receipt-container">
        <!-- Header -->
        <div class="header">
            <h1>🌾 AgriTerre</h1>
            <div class="subtitle">Agricultural Land Transaction Platform</div>
        </div>
        
        <!-- Transaction Summary -->
        <div class="transaction-info">
            <h2>Transaction Receipt</h2>
            <div style="font-size: 1.2em; margin: 10px 0;">Transaction #{{ $transaction->id }}</div>
            <div class="amount">${{ number_format($transaction->amount, 2) }}</div>
            <span class="status-badge status-{{ $transaction->status }}">
                {{ ucfirst($transaction->status) }}
            </span>
        </div>
        
        <!-- Transaction Details -->
        <div class="section">
            <h3>📋 Transaction Information</h3>
            <div class="details-grid">
                <div class="detail-item">
                    <div class="detail-label">Transaction ID</div>
                    <div class="detail-value">#{{ $transaction->id }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Date Created</div>
                    <div class="detail-value">{{ $transaction->created_at->format('M d, Y H:i:s') }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Payment Method</div>
                    <div class="detail-value">{{ $transaction->payment_method ?: 'Not specified' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Payment Reference</div>
                    <div class="detail-value">{{ $transaction->payment_reference ?: 'Not available' }}</div>
                </div>
                @if($transaction->completed_at)
                <div class="detail-item">
                    <div class="detail-label">Completed Date</div>
                    <div class="detail-value">{{ $transaction->completed_at->format('M d, Y H:i:s') }}</div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Financial Summary -->
        <div class="financial-summary">
            <h3>💰 Financial Breakdown</h3>
            <div class="financial-row">
                <span>Gross Transaction Amount:</span>
                <span>${{ number_format($transaction->amount, 2) }}</span>
            </div>
            <div class="financial-row">
                <span>Platform Commission:</span>
                <span>${{ number_format($transaction->commission, 2) }}</span>
            </div>
            <div class="financial-row">
                <span>Net Amount to Supplier:</span>
                <span>${{ number_format($transaction->amount - $transaction->commission, 2) }}</span>
            </div>
        </div>
        
        <!-- Client Information -->
        @if($transaction->client && $transaction->client->user)
        <div class="section">
            <h3>👤 Client Information</h3>
            <div class="details-grid">
                <div class="detail-item">
                    <div class="detail-label">Full Name</div>
                    <div class="detail-value">{{ $transaction->client->user->full_name }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Email Address</div>
                    <div class="detail-value">{{ $transaction->client->user->email }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Phone Number</div>
                    <div class="detail-value">{{ $transaction->client->user->phone ?: 'Not provided' }}</div>
                </div>
                @if($transaction->client->specialization_type)
                <div class="detail-item">
                    <div class="detail-label">Specialization</div>
                    <div class="detail-value">{{ $transaction->client->specialization_type }}</div>
                </div>
                @endif
            </div>
        </div>
        @endif
        
        <!-- Supplier Information -->
        @if($transaction->fournisseur && $transaction->fournisseur->user)
        <div class="section">
            <h3>🏢 Supplier Information</h3>
            <div class="details-grid">
                <div class="detail-item">
                    <div class="detail-label">Supplier Name</div>
                    <div class="detail-value">{{ $transaction->fournisseur->user->full_name }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Company Name</div>
                    <div class="detail-value">{{ $transaction->fournisseur->company_name }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Email Address</div>
                    <div class="detail-value">{{ $transaction->fournisseur->user->email }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Business Registration</div>
                    <div class="detail-value">{{ $transaction->fournisseur->business_registration }}</div>
                </div>
                <div class="detail-item" style="grid-column: 1 / -1;">
                    <div class="detail-label">Business Address</div>
                    <div class="detail-value">{{ $transaction->fournisseur->address }}</div>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Agricultural Land Information -->
        @if($transaction->terreAgricole)
        <div class="land-info">
            <h4>🌾 Agricultural Land Details</h4>
            <div class="details-grid" style="color: white;">
                <div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 6px;">
                    <div style="font-weight: bold; margin-bottom: 5px;">Land Title</div>
                    <div style="font-size: 1.1em;">{{ $transaction->terreAgricole->title }}</div>
                </div>
                <div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 6px;">
                    <div style="font-weight: bold; margin-bottom: 5px;">Surface Area</div>
                    <div style="font-size: 1.1em;">{{ number_format($transaction->terreAgricole->surface, 2) }} hectares</div>
                </div>
                <div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 6px;">
                    <div style="font-weight: bold; margin-bottom: 5px;">Location</div>
                    <div style="font-size: 1.1em;">{{ $transaction->terreAgricole->region }}, {{ $transaction->terreAgricole->country }}</div>
                </div>
                <div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 6px;">
                    <div style="font-weight: bold; margin-bottom: 5px;">Listed Price</div>
                    <div style="font-size: 1.1em;">${{ number_format($transaction->terreAgricole->price) }}</div>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Footer -->
        <div class="footer">
            <p><strong>Thank you for using AgriTerre!</strong></p>
            <p>This is an official transaction receipt generated by the AgriTerre platform.</p>
            <div class="timestamp">
                Generated on: {{ now()->format('F d, Y \a\t H:i:s T') }}
            </div>
            <div style="margin-top: 20px; font-size: 0.8em; color: #9ca3af;">
                For questions about this transaction, please contact our support team.<br>
                Visit: www.agriterre.com | Email: support@agriterre.com
            </div>
        </div>
    </div>
</body>
</html>