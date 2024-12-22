<?php
/*
Value Object (vo):
- (immutable) هو كائن في البرمجة يُستخدم لتمثيل قيمة أو مجموعة من القيم التي تُعتبر غير قابلة للتغيير
- الكائنات من هذا النوع تُعتبر متساوية إذا كانت قيمها الداخلية متساوية، وليس بالضرورة أن تكون هي نفسها في الذاكرة

الخصائص الأساسية:
- (No Identity) اللامعرفية
- (Immutable) غير قابل للتغيير
- المساواة على أساس القيم
 */

declare (strict_types = 1);

namespace App\Services\Shipping;

class BillableWeightCalculatorService
{
    public function calculate(
        PackageDimension $packageDimension,
        Weight $weight,
        DimDivisor $dimDivisor
    ) {
        if ($dimDivisor <= 0) {
            throw new \InvalidArgumentException('Invalid dim divisor');
        }

        $dimWeight = (int) round($packageDimension->width * $packageDimension->height * $packageDimension->length / $dimDivisor->value);

        return max($weight->value, $dimWeight);
    }
}
