<?php

declare (strict_types = 1);

namespace App\Controllers;

use App\Attributes\Get;
use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\View;
use Carbon\Carbon;

class InvoiceController
{
    #[Get('/invoices')]
    public function index(): View
    {
        $invoices = Invoice::query()->where('status', InvoiceStatus::Paid)->get()->toArray();

        return View::make('invoices/index', ['invoices' => $invoices]);
    }

    #[Get('/invoices/new')]
    public function create(){
        $invoice = new Invoice();

        $invoice->invoice_number = 5;
        $invoice->amount = 20;
        $invoice->status = InvoiceStatus::Pending;
        // $invoice->due_date = (new Carbon())->addDay(); // عشان اخليه يضيف تاريخ انتهاء ليوم واحد 

        $invoice->save();

        echo $invoice->id . ', ' . $invoice->due_date->format('m/d/Y');
    }
}
