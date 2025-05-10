<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PublicInvoiceController extends Controller
{
    public function show($uuid)
    {
        $invoice = Invoice::with('client.user', 'items')->where('uuid', $uuid)->firstOrFail();

        return view('invoices.preview', compact('invoice'));
    }

    public function downloadPdf(Invoice $invoice)
    {
        // Ensure the invoice is loaded with required relationships
        $invoice->load('client.user', 'items');

        // Generate PDF from Blade view
        $pdf = Pdf::loadView('invoice', compact('invoice'));

        // Return downloadable response
        return $pdf->download("invoice_{$invoice->invoice_number}.pdf");
    }

}
