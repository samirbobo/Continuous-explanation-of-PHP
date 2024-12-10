<?php

declare (strict_types = 1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

// الكلاس ديه بنتها عشان استخدمها في اضافه عناصر جديده في الداتا بيز

#[Entity]
#[Table('invoice_items')] // معناها اني بعرف الكلاس دا علي انه نفس الجدول الي في الداتا بيز
class InvoiceItem
{
    // كل العناصر البرافيد هنا هما نفس اسماء الاعمده الي في الجدول
    // وانا استخدامت الاتربيوت عشان يتم تعرفهم علي انهم اعمده مش مجرد متغيرات
    #[Id]
    #[Column, GeneratedValue]
    private int $id;

    #[Column(name: 'invoice_id')]
    private int $invoiceId;

    #[Column]
    private string $description;

    #[Column]
    private int $quantity;

    #[Column(name: 'unit_price', type: Types::DECIMAL, precision: 10, scale: 2)]
    private float $unitPrice;

    // ManyToOne هنا استخدمت ربط بين الكلاس دا الي اتحول الي جدول بالجدول التاني بعلاقه
    #[ManyToOne(inversedBy: 'items')]
    private Invoice $invoice;

    public function getId(): int
    {
        return $this->id;
    }

    public function getInvoiceId(): int
    {
        return $this->invoiceId;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): InvoiceItem
    {
        $this->description = $description;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): InvoiceItem
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getUnitPrice(): float
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(float $unitPrice): InvoiceItem
    {
        $this->unitPrice = $unitPrice;
        return $this;
    }

    public function getInvoice(): Invoice
    {
        return $this->invoice;
    }

    public function setInvoice(Invoice $invoice): InvoiceItem
    {
        $this->invoice = $invoice;
        return $this;
    }
}
