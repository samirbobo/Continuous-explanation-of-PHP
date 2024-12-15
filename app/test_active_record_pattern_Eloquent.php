<?php

declare (strict_types = 1);

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use Illuminate\Database\Capsule\Manager as Capsule;

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../eloquent.php";

// دا عشان خاطر انشاء بيانات في الجداول بتاعت الداتا بيز
// Capsule::connection()->transaction(function () {
//     $invoice = new Invoice();

//     $invoice->amount = 50;
//     $invoice->invoice_number = "2";
//     $invoice->status = InvoiceStatus::Pending;
//     $invoice->due_date = (new Carbon())->addDays(10);
//     $invoice->save();

//     $items = [["item 4", 4, 1], ["item 5", 15, 7.5], ["item 6", 11, 10]];

//     foreach ($items as [$description, $quantity, $unitPrice]) {
//         $item = new InvoiceItem();

//         $item->description = $description;
//         $item->quantity = $quantity;
//         $item->unit_price = $unitPrice;

//         $item->invoice()->associate($invoice);

//         $item->save();
//     }
// });

// $invoiceId = 2;

// select * from `invoices` where `id` = ? عشان تطبع امر الداتا بيز زي دا هتحتاج تكتب امر بالشكل دا
// echo Invoice::query()->where("id", "=", $invoiceId)->toSql();

// الامر دا عشان احدد صف معين في الجدول واغير بياناته او اعدلها
// where ممكن تقبل مني كلمه يساوي او لا وساعتها محرك الاكود بيحطها تلقائي
// Invoice::query()->where("id", "=", $invoiceId)->update(["status" => InvoiceStatus::Pending]);

// الامر دا عشان الوب علي عدد معين من الصفوف الي في الجدول عندي
Invoice::query()->where("status", InvoiceStatus::Paid)->get()->each(function (Invoice $invoice) {
  echo $invoice->id . ", " . $invoice->status->toString() . ", " . $invoice->create_at->format("M/D/Y") . PHP_EOL;

  $item = $invoice->items->first();

  $item->description = "Foo Bar";
  $item->save();
});