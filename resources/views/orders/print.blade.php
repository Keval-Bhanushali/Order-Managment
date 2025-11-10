<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Order #{{ $order->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }

        .invoice {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .order-info {
            margin-bottom: 20px;
        }

        .customer-info {
            float: left;
            width: 50%;
        }

        .order-details {
            float: right;
            width: 50%;
            text-align: right;
        }

        .clear {
            clear: both;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
        }

        .total {
            text-align: right;
            margin-top: 20px;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }

        @media print {
            body {
                padding: 0;
            }

            .invoice {
                border: none;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="invoice">
        <div class="header">
            <h1>Order Invoice</h1>
        </div>

        <div class="order-info">
            <div class="customer-info">
                <h3>Bill To:</h3>
                <p>
                    {{ $order->customer->name }}<br>
                    {{ $order->customer->email }}<br>
                    {{ $order->customer->phone }}
                </p>
            </div>
            <div class="order-details">
                <h3>Order Details:</h3>
                <p>
                    Order #: {{ $order->id }}<br>
                    Date: {{ $order->created_at->format('M d, Y') }}<br>
                    Status: {{ ucfirst($order->status) }}
                </p>
            </div>
            <div class="clear"></div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->price / $item->quantity, 2) }}</td>
                        <td>${{ number_format($item->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            <h3>Total Amount: ${{ number_format($order->total_amount, 2) }}</h3>
        </div>

        <div class="footer">
            <p>Thank you for your business!</p>
        </div>
    </div>
</body>

</html>
