@php
    $subtotal = 0;
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt #{{ $order->order_id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #222;
            font-size: 12px;
            line-height: 1.5;
        }
        .header {
            margin-bottom: 24px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            margin: 0 0 4px;
        }
        .subtitle {
            margin: 0;
            color: #555;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 4px;
        }
        .meta-table,
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }
        .meta-table td {
            vertical-align: top;
            width: 50%;
            padding: 4px 8px 4px 0;
        }
        .items-table th,
        .items-table td {
            border-bottom: 1px solid #ddd;
            padding: 8px 6px;
        }
        .items-table th {
            text-align: left;
            background: #f5f5f5;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .totals {
            margin-top: 16px;
            width: 320px;
            margin-left: auto;
        }
        .totals td {
            padding: 6px;
        }
        .grand-total td {
            font-weight: bold;
            border-top: 2px solid #333;
        }
        .footer {
            margin-top: 30px;
            font-size: 11px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <p class="title">Order Receipt</p>
        <p class="subtitle">Receipt for Order #{{ $order->order_id }}</p>
    </div>

    <div class="section">
        <div class="section-title">Customer and Order Details</div>
        <table class="meta-table">
            <tr>
                <td>
                    <strong>Customer Name:</strong><br>
                    {{ $order->customer->name ?? 'N/A' }}<br><br>

                    <strong>Email:</strong><br>
                    {{ $order->customer->email ?? 'N/A' }}<br><br>

                    <strong>Phone:</strong><br>
                    {{ $order->customer->phone ?? 'N/A' }}<br><br>

                    <strong>Address:</strong><br>
                    {{ $order->customer->address ?? 'N/A' }}
                </td>
                <td>
                    <strong>Order Number:</strong><br>
                    #{{ $order->order_id }}<br><br>

                    <strong>Order Date:</strong><br>
                    {{ optional($order->order_date)->format('M d, Y h:i A') ?? \Carbon\Carbon::parse($order->order_date)->format('M d, Y h:i A') }}<br><br>

                    <strong>Status:</strong><br>
                    {{ ucfirst($order->status) }}<br><br>

                    <strong>Shipping Fee:</strong><br>
                    ₱{{ number_format($order->shipping_fee ?? 0, 2) }}
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Ordered Items</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th class="text-center">Unit Price</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-right">Line Total</th>
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
            <tr>
                <td>Subtotal</td>
                <td class="text-right">₱{{ number_format($subtotal, 2) }}</td>
            </tr>
            <tr>
                <td>Shipping Fee</td>
                <td class="text-right">₱{{ number_format($order->shipping_fee ?? 0, 2) }}</td>
            </tr>
            <tr class="grand-total">
                <td>Total</td>
                <td class="text-right">₱{{ number_format($subtotal + ($order->shipping_fee ?? 0), 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        This receipt contains the customer and order details for your transaction.
    </div>
</body>
</html>
