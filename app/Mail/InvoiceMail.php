<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public Invoice $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function build()
    {
        $pdf = Pdf::loadView('invoice', ['invoice' => $this->invoice]);

        return $this->markdown('emails.invoice')
            ->subject('Your Invoice #' . $this->invoice->invoice_number)
            ->attachData($pdf->output(), "invoice_{$this->invoice->invoice_number}.pdf", [
                'mime' => 'application/pdf',
            ])
            ->with(['invoice' => $this->invoice]);
    }
}
