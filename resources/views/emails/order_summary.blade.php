<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; background-color: #f5f7f6; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(26, 71, 42, 0.08); }
        .header { background: linear-gradient(135deg, #1a472a 0%, #2d5f3f 100%); color: white; padding: 40px 30px; text-align: center; }
        .header-title { font-size: 28px; font-weight: 700; margin-bottom: 5px; }
        .header-subtitle { font-size: 14px; opacity: 0.9; }
        .content { padding: 40px 30px; }
        .section { margin-bottom: 35px; }
        .section-title { font-size: 16px; font-weight: 700; color: #1a472a; margin-bottom: 15px; display: flex; align-items: center; }
        .section-title:before { content: ""; display: inline-block; width: 4px; height: 20px; background: linear-gradient(135deg, #1a472a 0%, #2d5f3f 100%); border-radius: 2px; margin-right: 12px; }
        .status-badge { display: inline-block; padding: 8px 16px; border-radius: 20px; font-weight: 600; font-size: 12px; margin: 10px 0; }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-processing { background-color: #d1ecf1; color: #0c5460; }
        .status-completed { background-color: #d4edda; color: #155724; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }
        .info-box { background-color: #f9fef8; border-left: 4px solid #acd168; padding: 15px; border-radius: 4px; margin: 15px 0; }
        .info-label { font-weight: 600; color: #1a472a; font-size: 13px; margin-bottom: 3px; }
        .info-text { color: #555; font-size: 14px; }
        .table-wrapper { border-radius: 6px; overflow: hidden; border: 1px solid #e8e8e8; margin: 15px 0; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background-color: #f0f4f3; }
        th { padding: 12px; text-align: left; font-weight: 700; color: #1a472a; font-size: 13px; }
        td { padding: 12px; border-bottom: 1px solid #f0f0f0; font-size: 14px; }
        tbody tr:hover { background-color: #fafbfa; }
        .total-row { background-color: #f9fef8; font-weight: 600; }
        .final-total { background: linear-gradient(135deg, rgba(26, 71, 42, 0.05) 0%, rgba(45, 95, 63, 0.05) 100%); }
        .amount { color: #1a472a; font-weight: 600; }
        .divider { height: 1px; background: linear-gradient(to right, transparent, #e0e0e0, transparent); margin: 25px 0; }
        .footer { background-color: #f9fef8; border-top: 1px solid #e8e8e8; padding: 25px 30px; text-align: center; font-size: 12px; color: #666; }
        .footer-text { margin: 8px 0; }
        .button { display: inline-block; padding: 12px 28px; background: linear-gradient(135deg, #1a472a 0%, #2d5f3f 100%); color: white; text-decoration: none; border-radius: 6px; font-weight: 600; margin: 15px 0; font-size: 14px; }
        .text-center { text-align: center; }
        .thank-you { font-size: 16px; font-weight: 700; color: #1a472a; margin-bottom: 10px; }
        .greeting { color: #2d5f3f; margin-bottom: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-title">{{ config('app.name') }}</div>
            <div class="header-subtitle">Order Confirmation</div>
        </div>

        <div class="content">
            <div class="thank-you">🎒 Thank You for Your Order!</div>
            <p class="greeting">Hi {{ $order->user->first_name ?? 'there' }},</p>
            
            <p>We're excited to help you with your outdoor adventure. Your order has been received and is being prepared for shipment.</p>

            <div class="section">
                <div class="section-title">Order Details</div>
                <div class="info-box">
                    <div class="info-label">Order Number</div>
                    <div class="info-text" style="font-size: 16px; font-weight: 700; color: #1a472a;">#{{ $order->order_id }}</div>
                </div>
                <div class="info-box">
                    <div class="info-label">Status</div>
                    <div><span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></div>
                </div>
            </div>

            <div class="section">
                <div class="section-title">Shipping Address</div>
                @if($order->user)
                    <div class="info-box">
                        <div class="info-label">{{ $order->user->first_name }} {{ $order->user->last_name }}</div>
                        <div class="info-text">{{ $order->user->address }}</div>
                        <div class="info-text">{{ $order->user->phone }}</div>
                    </div>
                @endif
            </div>

            <div class="section">
                <div class="section-title">Order Items</div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th style="text-align: center;">Qty</th>
                                <th style="text-align: center;">Unit Price</th>
                                <th style="text-align: right;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $subtotal = 0; @endphp
                            @foreach($order->orderItems as $it)
                                @php $line = ($it->unit_price * $it->quantity); $subtotal += $line; @endphp
                                <tr>
                                    <td>{{ $it->product->name }}</td>
                                    <td style="text-align: center;">{{ $it->quantity }}</td>
                                    <td style="text-align: center;">₱{{ number_format($it->unit_price, 2) }}</td>
                                    <td style="text-align: right;" class="amount">₱{{ number_format($line, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="total-row">
                                <td colspan="3" style="text-align: right;">Subtotal:</td>
                                <td style="text-align: right;" class="amount">₱{{ number_format($subtotal, 2) }}</td>
                            </tr>
                            <tr class="total-row">
                                <td colspan="3" style="text-align: right;">Shipping:</td>
                                <td style="text-align: right;" class="amount">₱{{ number_format($order->shipping_fee ?? 0, 2) }}</td>
                            </tr>
                            <tr class="total-row final-total">
                                <td colspan="3" style="text-align: right;">Total Amount:</td>
                                <td style="text-align: right; color: #1a472a; font-size: 16px;">₱{{ number_format(($subtotal + ($order->shipping_fee ?? 0)), 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="text-center">
                <p style="color: #666; font-size: 13px; margin-top: 20px;">📦 You'll receive a tracking update once your order ships!</p>
            </div>

            <div class="divider"></div>

            <div style="background-color: #f9fef8; padding: 15px; border-radius: 6px; text-align: center;">
                <p style="color: #1a472a; font-weight: 600; margin-bottom: 8px;">Questions? We're here to help!</p>
                <p style="color: #666; font-size: 13px;">Reply to this email or contact our support team anytime.</p>
            </div>
        </div>

        <div class="footer">
            <div class="footer-text">Thank you for shopping with {{ config('app.name') }}!</div>
            <div class="footer-text">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</div>
            <div class="footer-text" style="margin-top: 15px; font-size: 11px; color: #999;">This is an automated message, please do not reply to this address directly.</div>
        </div>
    </div>
</body>
</html>
