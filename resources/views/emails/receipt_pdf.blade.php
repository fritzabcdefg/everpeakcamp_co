@php
    $subtotal = 0;
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt #{{ $order->order_id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #222;
            font-size: 11px;
            line-height: 1.6;
            background-color: #fff;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            border-bottom: 3px solid #1a472a;
            margin-bottom: 25px;
            padding-bottom: 15px;
        }
        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #1a472a;
            margin-bottom: 3px;
        }
        .company-tagline {
            color: #666;
            font-size: 10px;
        }
        .receipt-title {
            font-size: 18px;
            font-weight: bold;
            color: #1a472a;
            margin-top: 10px;
        }
        .receipt-number {
            color: #666;
            font-size: 11px;
        }
        .two-column {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #1a472a;
            margin-bottom: 8px;
            margin-top: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid #acd168;
        }
        .field {
            margin-bottom: 6px;
        }
        .field-label {
            font-size: 10px;
            color: #999;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .field-value {
            font-size: 11px;
            color: #222;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            margin-top: 15px;
        }
        .items-table th {
            background-color: #f0f4f3;
            color: #1a472a;
            padding: 8px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
            border-bottom: 2px solid #acd168;
        }
        .items-table td {
            padding: 8px;
            border-bottom: 1px solid #e8e8e8;
            font-size: 11px;
        }
        .items-table tbody tr:nth-child(even) {
            background-color: #f9fef8;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .totals {
            width: 350px;
            margin-left: auto;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .totals tr td {
            padding: 8px;
            border-bottom: 1px solid #e8e8e8;
        }
        .totals tr td:first-child {
            text-align: left;
        }
        .totals tr td:last-child {
            text-align: right;
            font-weight: bold;
        }
        .totals-subtotal td {
            color: #666;
        }
        .totals-shipping td {
            color: #666;
        }
        .totals-grand tr td {
            font-weight: bold;
            font-size: 12px;
            color: #1a472a;
            border-top: 2px solid #1a472a;
            border-bottom: 2px solid #1a472a;
            padding: 10px 8px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #999;
            text-align: center;
        }
        .thank-you {
            font-weight: bold;
            color: #1a472a;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="company-name">{{ config('app.name') }}</div>
            <div class="company-tagline">Premium Outdoor & Camping Gear</div>
            <div class="receipt-title">Order Receipt</div>
            <div class="receipt-number">Receipt #{{ $order->order_id }}</div>
        </div>

        <div class="two-column">
            <div class="column">
                <div class="section-title">Customer Information</div>
                <div class="field">
                    <div class="field-label">Name</div>
                    <div class="field-value">{{ $order->user->first_name ?? 'N/A' }} {{ $order->user->last_name ?? 'N/A' }}</div>
                </div>
                <div class="field">
                    <div class="field-label">Email</div>
                    <div class="field-value">{{ $order->user->email ?? 'N/A' }}</div>
                </div>
                <div class="field">
                    <div class="field-label">Phone</div>
                    <div class="field-value">{{ $order->user->phone ?? 'N/A' }}</div>
                </div>
                <div class="field">
                    <div class="field-label">Address</div>
                    <div class="field-value">{{ $order->user->address ?? 'N/A' }}</div>
                </div>
            </div>
            <div class="column">
                <div class="section-title">Order Details</div>
                <div class="field">
                    <div class="field-label">Order Number</div>
                    <div class="field-value" style="font-weight: bold; font-size: 12px;">#{{ $order->order_id }}</div>
                </div>
                <div class="field">
                    <div class="field-label">Order Date</div>
                    <div class="field-value">{{ optional($order->order_date)->format('M d, Y') ?? date('M d, Y') }}</div>
                </div>
                <div class="field">
                    <div class="field-label">Status</div>
                    <div class="field-value" style="font-weight: bold; color: #1a472a;">{{ ucfirst($order->status) }}</div>
                </div>
            </div>
        </div>

        <div class="section-title" style="margin-top: 25px;">Order Items</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 50%;">Product</th>
                    <th class="text-center" style="width: 15%;">Unit Price</th>
                    <th class="text-center" style="width: 15%;">Quantity</th>
                    <th class="text-right" style="width: 20%;">Line Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                    @php
                        $lineTotal = $item->unit_price * $item->quantity;
                        $subtotal += $lineTotal;
                    @endphp
                    <tr>
                        <td>{{ $item->product->name ?? 'Product unavailable' }}</td>
                        <td class="text-center">₱{{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">₱{{ number_format($lineTotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals">
            <tr class="totals-subtotal">
                <td>Subtotal</td>
                <td>₱{{ number_format($subtotal, 2) }}</td>
            </tr>
            <tr class="totals-shipping">
                <td>Shipping Fee</td>
                <td>₱{{ number_format($order->shipping_fee ?? 0, 2) }}</td>
            </tr>
            <tr class="totals-grand">
                <td>Total Amount</td>
                <td>₱{{ number_format($subtotal + ($order->shipping_fee ?? 0), 2) }}</td>
            </tr>
        </table>

        <div class="footer">
            <div class="thank-you">Thank you for your business!</div>
            <p>This receipt contains the details of your transaction. Please keep it for your records.</p>
            <p style="margin-top: 10px; color: #bbb;">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
