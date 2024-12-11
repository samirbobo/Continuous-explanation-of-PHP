<?php

declare (strict_types = 1);

namespace App\Entity;

use App\Enums\InvoiceStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

// الكلاس ديه بنتها عشان استخدمها في اضافه عناصر جديده في الداتا بيز 
#[Entity]
#[Table('invoices')] // معناها اني بعرف الكلاس دا علي انه نفس الجدول الي في الداتا بيز
#[HasLifecycleCallbacks]
class Invoice
{
    // كل العناصر البرافيد هنا هما نفس اسماء الاعمده الي في الجدول
    // وانا استخدامت الاتربيوت عشان يتم تعرفهم علي انهم اعمده مش مجرد متغيرات
    #[Id]
    #[Column, GeneratedValue]
    private int $id;

    #[Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private float $amount;

    #[Column(name: 'invoice_number')]
    private string $invoiceNumber;

    #[Column]
    private InvoiceStatus $status;

    #[Column(name: 'create_at')]
    private \DateTime $createAt;

    // OneToMany هنا استخدمت ربط بين الكلاس دا الي اتحول الي جدول بالجدول التاني بعلاقه 
    #[OneToMany(targetEntity: InvoiceItem::class, mappedBy: 'invoice', cascade: ['persist', 'remove'])]
    private Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    #[PrePersist]
    public function onPrePersist(LifecycleEventArgs $args)
    {
        $this->createAt = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): Invoice
    {
        $this->amount = $amount;
        return $this;
    }

    public function getInvoiceNumber(): string
    {
        return $this->invoiceNumber;
    }

    public function setInvoiceNumber(string $invoiceNumber): Invoice
    {
        $this->invoiceNumber = $invoiceNumber;
        return $this;
    }

    public function getStatus(): InvoiceStatus
    {
        return $this->status;
    }

    public function setStatus(InvoiceStatus $status): Invoice
    {
        $this->status = $status;
        return $this;
    }

    public function setCreatedAt(\DateTime $createdAt): Invoice
    {
        $this->createAt = $createdAt;
        return $this;
    }

    /**
     * @return Collection<InvoiceItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(InvoiceItem $item): Invoice
    {
        $item->setInvoice($this);
        $this->items->add($item);
        return $this;
    }
}
