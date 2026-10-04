<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class InvoiceController extends Controller
{
    /**
     * Display the digital invoice online.
     */
    public function show(string $token)
    {
        $invoice = Invoice::with(['customer', 'vehicle', 'items', 'issuedBy', 'cancelledBy'])
            ->where('share_token', $token)
            ->firstOrFail();

        return view('invoices.show', compact('invoice'));
    }

    /**
     * Stream or download the branded PDF version of the invoice.
     */
    public function pdf(string $token)
    {
        $invoice = Invoice::with(['customer', 'vehicle', 'items', 'issuedBy', 'cancelledBy'])
            ->where('share_token', $token)
            ->firstOrFail();

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'))
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        $filename = 'TheDriveClinic-Invoice-' . str_replace('/', '-', $invoice->invoice_number) . '.pdf';

        return $pdf->stream($filename);
    }
}
