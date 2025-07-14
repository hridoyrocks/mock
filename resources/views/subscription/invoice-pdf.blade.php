<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice #{{ $transaction->transaction_id }}</title>
    <style>
        @page {
            margin: 0cm 0cm;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        .invoice-container {
            padding: 30px;
            max-width: 800px;
            margin: 0 auto;
        }

        /* Header Styles */
        .header {
            border-bottom: 3px solid #6B46C1;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header-content {
            display: table;
            width: 100%;
        }

        .header-left {
            display: table-cell;
            vertical-align: top;
            width: 60%;
        }

        .header-right {
            display: table-cell;
            vertical-align: top;
            width: 40%;
            text-align: right;
        }

        .invoice-title {
            font-size: 36px;
            font-weight: bold;
            color: #6B46C1;
            margin: 0 0 10px 0;
        }

        .transaction-id {
            font-size: 14px;
            color: #666;
            margin: 5px 0;
        }

        .logo {
            max-height: 60px;
            margin-bottom: 10px;
        }

        .status-badge {
            display: inline-block;
            background-color: #10B981;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 10px;
        }

        /* Info Boxes */
        .info-section {
            margin-bottom: 30px;
        }

        .info-box {
            background-color: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .info-box h3 {
            color: #6B46C1;
            font-size: 16px;
            margin: 0 0 15px 0;
            font-weight: bold;
        }

        .info-row {
            margin-bottom: 8px;
        }

        .info-label {
            font-weight: bold;
            color: #666;
            display: inline-block;
            width: 120px;
        }

        .info-value {
            color: #333;
        }

        /* Table Styles */
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background-color: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .invoice-table th {
            background-color: #6B46C1;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: bold;
            font-size: 14px;
        }

        .invoice-table th:last-child {
            text-align: right;
        }

        .invoice-table td {
            padding: 20px 15px;
            border-bottom: 1px solid #E5E7EB;
        }

        .invoice-table tbody tr:last-child td {
            border-bottom: none;
        }

        .item-name {
            font-weight: bold;
            font-size: 16px;
            color: #333;
            margin-bottom: 5px;
        }

        .item-description {
            color: #666;
            font-size: 12px;
        }

        .item-icon {
            display: inline-block;
            width: 40px;
            height: 40px;
            background-color: #6B46C1;
            color: white;
            text-align: center;
            line-height: 40px;
            border-radius: 8px;
            margin-right: 15px;
            font-size: 20px;
            vertical-align: middle;
        }

        .amount {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }

        /* Total Section */
        .total-section {
            background-color: #F3F4F6;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .total-row {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .total-label {
            display: table-cell;
            text-align: right;
            padding-right: 20px;
            color: #666;
            font-size: 14px;
        }

        .total-value {
            display: table-cell;
            text-align: right;
            width: 150px;
            font-size: 14px;
            color: #333;
        }

        .grand-total {
            border-top: 2px solid #6B46C1;
            padding-top: 10px;
            margin-top: 10px;
        }

        .grand-total .total-label {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .grand-total .total-value {
            font-size: 24px;
            font-weight: bold;
            color: #6B46C1;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 50px;
            padding-top: 30px;
            border-top: 1px solid #E5E7EB;
        }

        .footer-message {
            font-size: 16px;
            color: #333;
            margin-bottom: 10px;
        }

        .footer-contact {
            color: #666;
            font-size: 12px;
        }

        .footer-contact a {
            color: #6B46C1;
            text-decoration: none;
        }

        /* Utility Classes */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .mt-20 {
            margin-top: 20px;
        }

        /* Print Specific */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        /* Two Column Layout */
        .two-column {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }

        .column {
            display: table-cell;
            width: 48%;
            vertical-align: top;
        }

        .column:first-child {
            padding-right: 2%;
        }

        .column:last-child {
            padding-left: 2%;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        {{-- Header --}}
        <div class="header">
            <div class="header-content">
                <div class="header-left">
                    <h1 class="invoice-title">INVOICE</h1>
                    <p class="transaction-id">Transaction ID: <strong>{{ $transaction->transaction_id }}</strong></p>
                    <p class="transaction-id">Date: <strong>{{ $transaction->created_at->format('F d, Y') }}</strong></p>
                </div>
                <div class="header-right">
                    <img src="{{ public_path('images/logo.png') }}" alt="CD IELTS Master" class="logo">
                    <br>
                    <span class="status-badge">{{ ucfirst($transaction->status) }}</span>
                </div>
            </div>
        </div>

        {{-- Billing Information --}}
        <div class="two-column">
            <div class="column">
                <div class="info-box">
                    <h3>Bill To</h3>
                    <div class="info-row">
                        <strong>{{ $user->name }}</strong>
                    </div>
                    <div class="info-row">
                        {{ $user->email }}
                    </div>
                    @if($user->phone_number)
                        <div class="info-row">
                            {{ $user->phone_number }}
                        </div>
                    @endif
                    @if($user->city || $user->country_name)
                        <div class="info-row">
                            {{ $user->city }}{{ $user->city && $user->country_name ? ', ' : '' }}{{ $user->country_name }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="column">
                <div class="info-box">
                    <h3>Payment Information</h3>
                    <div class="info-row">
                        <span class="info-label">Method:</span>
                        <span class="info-value">{{ ucwords(str_replace('_', ' ', $transaction->payment_method)) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Currency:</span>
                        <span class="info-value">{{ $transaction->currency }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Payment Date:</span>
                        <span class="info-value">{{ $transaction->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                    @if($transaction->payment_reference)
                        <div class="info-row">
                            <span class="info-label">Reference:</span>
                            <span class="info-value">{{ $transaction->payment_reference }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Invoice Items --}}
        <table class="invoice-table">
            <thead>
                <tr>
                    <th style="width: 60%;">Item Description</th>
                    <th style="width: 20%; text-align: center;">Duration</th>
                    <th style="width: 20%; text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div style="display: flex; align-items: center;">
                            <span class="item-icon">👑</span>
                            <div>
                                <div class="item-name">{{ $plan->name }} Plan Subscription</div>
                                <div class="item-description">{{ $plan->description }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        {{ $plan->duration_days }} days
                    </td>
                    <td class="text-right">
                        <span class="amount">৳{{ number_format($transaction->amount, 2) }}</span>
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Total Section --}}
        <div class="total-section">
            <div class="total-row">
                <div class="total-label">Subtotal:</div>
                <div class="total-value">৳{{ number_format($transaction->amount, 2) }}</div>
            </div>
            <div class="total-row">
                <div class="total-label">Tax (0%):</div>
                <div class="total-value">৳0.00</div>
            </div>
            <div class="total-row grand-total">
                <div class="total-label">Total Amount:</div>
                <div class="total-value">৳{{ number_format($transaction->amount, 2) }}</div>
            </div>
        </div>

        {{-- Subscription Details --}}
        <div class="info-box mt-20">
            <h3>Subscription Details</h3>
            <div class="two-column">
                <div class="column">
                    <div class="info-row">
                        <span class="info-label">Start Date:</span>
                        <span class="info-value">{{ $subscription->starts_at->format('F d, Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">End Date:</span>
                        <span class="info-value">{{ $subscription->ends_at->format('F d, Y') }}</span>
                    </div>
                </div>
                <div class="column">
                    <div class="info-row">
                        <span class="info-label">Auto Renewal:</span>
                        <span class="info-value">{{ $subscription->auto_renew ? 'Enabled' : 'Disabled' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="info-value">{{ ucfirst($subscription->status) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p class="footer-message">Thank you for choosing CD IELTS!</p>
            <p class="footer-contact">
                If you have any questions about this invoice, please contact us at<br>
                <a href="mailto:payment@cdielts.org">payment@cdielts.org</a> | 
                <a href="https://cdielts.org">www.cdielts.org</a>
            </p>
        </div>
    </div>
</body>
</html>