<?php

declare (strict_types = 1);

namespace App\Models;

use App\Enums\InvoiceStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int           $id
 * @property string        $invoice_number
 * @property float         $amount
 * @property InvoiceStatus $status
 * @property Carbon        $created_at
 * @property Carbon        $due_date

 * @property-read Collection $items
 */

class Invoice extends Model
{
    // المكتبه متوقعه مني ان الجدول يكون فيه عمود بالاسم دا ولان الاسم مختلف عملت السطر عشان المشكله تتحل
    const CREATED_AT = 'create_at';
    const UPDATED_AT = null;

    // عشان اشرحله البيانات ديه نوعها ايه وتتخزن بالنوع الي انا عايزه
    protected $casts = [
        'created_at' => 'datetime',
        'due_date' => 'datetime',
        'status' => InvoiceStatus::class,
    ];

    // دي فانكشن جاهزه في المكتب وبتعمل شغل معين فانا استدعتها عشان اعمل عليها اوفر رايت واعدل في واظفتها
    // eloquent مكتبه
    public static function booted()
    {
        // static::creating اسلوب جاهز في المكتبه برده عشان انفذ امر معين بشكل سريع
        // وهنا بيضيف عشر ايام في المستقبل كتاريخ انتهاء لحاله الصف الجديد الي هضيفه ف الجدول
        static::creating(function (Invoice $invoice) {
            if ($invoice->isClean('due_date')) {
                $invoice->due_date = (new Carbon())->addDays(10);
            }
        });
    }

    // one to many الفانكشن ديه عشان اعمل ربط بين الجدولين بعلاقه
    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
