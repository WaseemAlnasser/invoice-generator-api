@component('mail::message')
    # Hello {{ $invoice->client->name }}

    Thank you for your business!
    Please find attached your invoice **#{{ $invoice->invoice_number }}**.

    @component('mail::panel')
        **Total:** AED {{ number_format($invoice->total, 2) }}
        **Due Date:** {{ $invoice->due_date }}
    @endcomponent

    If you have any questions, feel free to reach out.

    Thanks,
    {{ config('app.name') }}
@endcomponent
