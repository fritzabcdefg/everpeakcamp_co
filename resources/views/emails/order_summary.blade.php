@php
    $order = $order ?? null;
@endphp

<div style="font-family: Arial, sans-serif; color: #222;">
    <h2>Thank you for your order!</h2>
    <p>Order #{{ $order->order_id }} — Status: {{ ucfirst($order->status) }}</p>

    <h3>Shipping Address</h3>
    @if($order->customer)
        <p>
            {{ $order->customer->name }}<br>
            {{ $order->customer->address }}<br>
            {{ $order->customer->phone }}
        </p>
    @endif

    <h3>Items</h3>
    <table style="width:100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="text-align:left; padding:6px; border-bottom:1px solid #ddd;">Product</th>
                <th style="text-align:center; padding:6px; border-bottom:1px solid #ddd;">Unit</th>
                <th style="text-align:center; padding:6px; border-bottom:1px solid #ddd;">Qty</th>
                <th style="text-align:right; padding:6px; border-bottom:1px solid #ddd;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $subtotal = 0; @endphp
            @foreach($order->orderItems as $it)
                @php $line = ($it->unit_price * $it->quantity); $subtotal += $line; @endphp
                <tr>
                    <td style="padding:6px;">{{ $it->product->name }}</td>
                    <td style="text-align:center;">${{ number_format($it->unit_price,2) }}</td>
                    <td style="text-align:center;">{{ $it->quantity }}</td>
                    <td style="text-align:right;">${{ number_format($line,2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align:right; padding:6px;">Subtotal:</td>
                <td style="text-align:right; padding:6px;">${{ number_format($subtotal,2) }}</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align:right; padding:6px;">Shipping:</td>
                <td style="text-align:right; padding:6px;">${{ number_format($order->shipping_fee ?? 0,2) }}</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align:right; padding:6px; font-weight:bold;">Total:</td>
                <td style="text-align:right; padding:6px; font-weight:bold;">${{ number_format(($subtotal + ($order->shipping_fee ?? 0)),2) }}</td>
            </tr>
        </tfoot>
    </table>

    <p>If you have any questions, reply to this email.</p>
</div>
