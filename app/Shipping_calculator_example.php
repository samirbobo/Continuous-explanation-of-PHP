<?php

declare (strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

use App\Services\Shipping\BillableWeightCalculatorService;
use App\Services\Shipping\DimDivisor;
use App\Services\Shipping\PackageDimension;
use App\Services\Shipping\Weight;

// مقاسات العبوه الي بعمل عليها المثال 
$package = [
    'weight' => 6,
    'dimensions' => [
        'width' => 9,
        'length' => 15,
        'height' => 7,
    ],
];

// عملت كلاس يضم جميع المقاسات وفيه فانكشن لزياده العرض وكمان لاختبار هلي العبوات تحتوي علي نفس المقاسات ولا لا
$packageDimensions = new PackageDimension(
    $package['dimensions']['width'],
    $package['dimensions']['height'],
    $package['dimensions']['length']
);

// هنا وسعت العبوه
$widerPackageDimensions = $packageDimensions->increaseWidth(10);

// وثبت الوزن في متغير واحد عشان مكررش العباره ديه
$weight = new Weight($package['weight']);

// وهنا خزنت الكلاس الي بيحسبلي قيمه العبوه في متغير لتسهيل القراءة
$billClass = new BillableWeightCalculatorService();

$billableWeight = $billClass->calculate(
    $packageDimensions,
    $weight,
    DimDivisor::FEDEX
);

$billableWiderWeight = $billClass->calculate(
    $widerPackageDimensions,
    $weight,
    DimDivisor::FEDEX
);

echo $billableWeight . ' lb' . PHP_EOL;
echo $billableWiderWeight . ' lb' . PHP_EOL;
