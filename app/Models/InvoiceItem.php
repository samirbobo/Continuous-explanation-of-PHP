<?php

declare(strict_types = 1);

namespace app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int    $id
 * @property int    $invoice_id
 * @property string $description
 * @property int    $quantity
 * @property float  $unit_price
*/

class InvoiceItem extends Model {
  // معناها اني بقوله وقف التعامل مع الاعمده الزمنيه عشان ميحصلش اخطاء
  public $timestamps = false;

  // many to one الفانكشن ديه عشان اعمل ربط بين الجدولين بعلاقه
  public function invoice(): BelongsTo {
    return $this->belongsTo(Invoice::class);
  }
}