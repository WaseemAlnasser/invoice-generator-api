<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <link rel="stylesheet" href="https://unpkg.com/sanitize.css">
    <style>
        body { font-family: sans-serif; padding: 40px; max-width: 800px; margin: auto; background: #f9f9f9; }
        .logo { max-height: 60px; }
        .invoice-box { background: white; padding: 30px; box-shadow: 0 0 10px rgba(0,0,0,0.05); }
        table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f0f0f0; }
        .total { text-align: right; font-size: 18px; margin-top: 20px; }
        .buttons { margin-top: 20px; }
        .buttons a { padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; margin-right: 10px; }
    </style>
</head>
<body>

<div class="invoice-box">
    <div style="text-align: center;">
        @if ($invoice->client->user->company_logo)
            <img src="{{ asset($invoice->client->user->company_logo) }}" class="logo" alt="Logo">
        @endif
        <h2>{{ $invoice->client->user->company_name }}</h2>
        <p>{{ $invoice->client->user->company_address }}</p>
    </div>

    <hr>

    <h3>Invoice #{{ $invoice->invoice_number }}</h3>
    <p><strong>Issued:</strong> {{ $invoice->issue_date }}<br>
        <strong>Due:</strong> {{ $invoice->due_date }}<br>
        <strong>Status:</strong> {{ ucfirst($invoice->status) }}</p>

    <h4>Bill To</h4>
    <p>{{ $invoice->client->name }}<br>{{ $invoice->client->email }}</p>

    <table>
        <thead>
        <tr>
            <th>Description</th>
            <th>Qty</th>
            <th>Unit Price</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($invoice->items as $item)
            <tr>
                <td>{{ $item->description }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->unit_price, 2) }}</td>
                <td>{{ number_format($item->total, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="total">
        Grand Total: AED {{ number_format($invoice->total, 2) }}
    </div>

    <div class="buttons">
        <a href="{{ route('download.invoice.pdf', ['invoice' => $invoice->id]) }}">Download PDF</a>
        {{-- Future: <a href="{{ $invoice->payment_link }}">Pay Now</a> --}}
    </div>
</div>

</body>
</html>
