<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Order - {{ $salesOrder->order_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .document-title {
            font-size: 18px;
            color: #666;
        }
        .order-info {
            margin-bottom: 30px;
        }
        .order-info table {
            width: 100%;
        }
        .order-info td {
            padding: 5px 0;
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            width: 150px;
        }
        .status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status.confirmed {
            background-color: #d4edda;
            color: #155724;
        }
        .status.pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .items-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
            font-size: 12px;
            color: #666;
        }
        .signature-section {
            margin-top: 50px;
        }
        .signature-box {
            border-top: 1px solid #333;
            width: 200px;
            margin-top: 50px;
            text-align: center;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="document-title">SALES ORDER</div>
    </div>

    <!-- Order Information -->
    <div class="order-info">
        <table>
            <tr>
                <td class="label">Order Number:</td>
                <td><strong>{{ $salesOrder->order_number }}</strong></td>
                <td class="label">Date:</td>
                <td>{{ $salesOrder->created_at->format('F d, Y') }}</td>
            </tr>
            <tr>
                <td class="label">Status:</td>
                <td>
                    <span class="status {{ $salesOrder->status }}">
                        {{ ucfirst($salesOrder->status) }}
                    </span>
                </td>
                <td class="label">Created By:</td>
                <td>{{ $salesOrder->user->name }}</td>
            </tr>
        </table>
    </div>

    <!-- Order Items -->
    <h3>Order Items</h3>
    <table class="items-table">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>SKU</th>
                <th class="text-right">Unit Price</th>
                <th class="text-center">Quantity</th>
                <th class="text-right">Total Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salesOrder->items as $item)
                <tr>
                    <td>{{ $item->products->name }}</td>
                    <td>{{ $item->products->sku }}</td>
                    <td class="text-right">${{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">${{ number_format($item->total_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" class="text-right"><strong>TOTAL AMOUNT:</strong></td>
                <td class="text-right"><strong>${{ number_format($salesOrder->total_amount, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <!-- Order Summary -->
    <div style="margin-top: 30px;">
        <h4>Order Summary</h4>
        <table style="width: 300px;">
            <tr>
                <td>Total Items:</td>
                <td class="text-right"><strong>{{ $salesOrder->items->count() }}</strong></td>
            </tr>
            <tr>
                <td>Total Quantity:</td>
                <td class="text-right"><strong>{{ $salesOrder->items->sum('quantity') }}</strong></td>
            </tr>
            <tr style="border-top: 1px solid #ddd;">
                <td><strong>Total Amount:</strong></td>
                <td class="text-right"><strong>${{ number_format($salesOrder->total_amount, 2) }}</strong></td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p><strong>Note:</strong></p>
        <p>Generated on: {{ now()->format('F d, Y \a\t h:i A') }}</p>
        <p>Thank you for your business!</p>
    </div>
</body>
</html>