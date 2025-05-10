<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;
class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        return Invoice::with('client', 'items')
            ->whereHas('client', fn($q) => $q->where('user_id', $request->user()->id))
            ->latest()
            ->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'invoice_number' => 'required|unique:invoices,invoice_number',
            'issue_date' => 'required|date',
            'due_date' => 'required|date',
            'status' => 'in:draft,sent,paid,overdue',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // Make sure the client belongs to the user
        $client = Client::where('id', $data['client_id'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $invoice = Invoice::create([
            'client_id' => $client->id,
            'invoice_number' => $data['invoice_number'],
            'issue_date' => $data['issue_date'],
            'due_date' => $data['due_date'],
            'status' => $data['status'] ?? 'draft',
            'notes' => $data['notes'] ?? null,
            'total' => 0, // Will calculate after adding items
        ]);

        $total = 0;

        foreach ($data['items'] as $itemData) {
            $itemTotal = $itemData['quantity'] * $itemData['unit_price'];
            $total += $itemTotal;

            $invoice->items()->create([
                'description' => $itemData['description'],
                'quantity' => $itemData['quantity'],
                'unit_price' => $itemData['unit_price'],
                'total' => $itemTotal,
            ]);
        }

        $invoice->update(['total' => $total]);

        return response()->json($invoice->load('items'), 201);
    }

    public function show(Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);
        return $invoice->load('client', 'items');
    }

    public function update(Request $request, Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);

        $data = $request->validate([
            'invoice_number' => 'required|unique:invoices,invoice_number,' . $invoice->id,
            'issue_date' => 'required|date',
            'due_date' => 'required|date',
            'status' => 'in:draft,sent,paid,overdue',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $invoice->update([
            'invoice_number' => $data['invoice_number'],
            'issue_date' => $data['issue_date'],
            'due_date' => $data['due_date'],
            'status' => $data['status'],
            'notes' => $data['notes'] ?? null,
        ]);

        // Delete old items and recalculate
        $invoice->items()->delete();

        $total = 0;
        foreach ($data['items'] as $itemData) {
            $itemTotal = $itemData['quantity'] * $itemData['unit_price'];
            $total += $itemTotal;

            $invoice->items()->create([
                'description' => $itemData['description'],
                'quantity' => $itemData['quantity'],
                'unit_price' => $itemData['unit_price'],
                'total' => $itemTotal,
            ]);
        }

        $invoice->update(['total' => $total]);

        return response()->json($invoice->load('items'));
    }

    public function destroy(Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);
        $invoice->delete();

        return response()->json(['message' => 'Invoice deleted']);
    }

    private function authorizeInvoice(Invoice $invoice)
    {
        if ($invoice->client->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
    }


    public function downloadPdf(Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);

        $invoice->load('client', 'items', 'client.user');

        $pdf = Pdf::loadView('invoice', compact('invoice'));

        return $pdf->download("invoice_{$invoice->invoice_number}.pdf");
    }



    public function sendInvoiceEmail(Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);

        $invoice->load('client', 'items', 'client.user');

        Mail::to($invoice->client->email)->send(new InvoiceMail($invoice));

        return response()->json(['message' => 'Invoice emailed successfully']);
    }


    public function dashboardStats(Request $request)
    {
        $user = $request->user();

        $invoices = \App\Models\Invoice::whereHas('client', fn($q) =>
        $q->where('user_id', $user->id)
        );

        return response()->json([
            'total_invoices' => $invoices->count(),
            'total_paid' => $invoices->where('status', 'paid')->count(),
            'total_unpaid' => $invoices->whereIn('status', ['draft', 'sent'])->count(),
            'total_overdue' => $invoices->where('status', 'overdue')->count(),
            'total_amount' => $invoices->sum('total'),
        ]);
    }

}
