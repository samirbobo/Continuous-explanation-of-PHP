<?php

declare (strict_types = 1);

// Doctrine مجموعه  من المكتبات الخاصه بي بيه اتش بيه
// Object Relational Mapper (orm):
// Doctrine هي جزء من
// هو أداة برمجية تُستخدم لتسهيل عملية التعامل مع قواعد البيانات
// في التطبيقات البرمجية، عن طريق تحويل البيانات بين قاعدة البيانات والكود البرمجي في التطبيق
// ORM كيف يعمل:
// يربط الكائنات في الكود البرمجي بالجداول في قاعدة البيانات. يقوم بتحويل العمليات البرمجية
// إلى استعلامات قاعدة بيانات والعكس، مما يجعل البرمجة أكثر سهولة وقابلية للصيانة.

use App\Entity\Invoice;
use App\Entity\InvoiceItem;
use App\Enums\InvoiceStatus;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Dotenv\Dotenv;

require_once __DIR__ . "/../vendor/autoload.php";

// Dotenv استخدمتها عشان استدعي البيانات المتخزنه في ملف الانف
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// بجيب البيانات بتاع السيرفر بتاع الداتا بيز 
$params = [
    'host' => $_ENV["DB_HOST"],
    'user' => $_ENV["DB_USER"],
    'password' => $_ENV["DB_PASS"],
    'dbname' => $_ENV["DB_DATABASE"],
    'driver' => $_ENV["DB_DRIVER"] ?? 'pdo_mysql',
];

// إعداد مسار الكيانات (Entities)
$paths = [__DIR__ . '/Entity'];

// entityManager دا الي بيربط الكلاساس علي انها جداول و بيدير الاوامر الخاصه بي الداتا بيز
$entityManager = EntityManager::create(
  $params, 
  Setup::createAttributeMetadataConfiguration($paths)
);

$items = [["Item 1", 1, 15], ["Item 2", 2, 7.5], ["Item 3", 4, 3.75]];

// Invoice بستدعي الجدول بتاع ال
// وبضيف فيه كل البيانات الي انا عايزها في الداتا بيز
$invoice = (new Invoice())
    ->setAmount(45)
    ->setInvoiceNumber("1")
    ->setStatus(InvoiceStatus::Pending)
    ->setCreatedAt(new DateTime());

  // ولان كل فاتوره ليها بيانات تانيه جواها انشاء جدول تاني وفي كل فاتوره بضيف فيها البيانات ديه
// foreach ($items as [$description, $quantity, $unitPrice]) {
//     $item = (new InvoiceItem())
//         ->setDescription($description)
//         ->setQuantity($quantity)
//         ->setUnitPrice($unitPrice);

//     // بعد ما بضيف البيانات في الجدول التاني بربطها في الجدول الاساسي وبيضاف علي انه صف جديد 
//     $invoice->addItem($item);
//     // الامر دا هو الي بيخلني انتقل من مرحله ربط كلاساس ببعض علي مستوي ال بي اتش بيه الي ربط في الداتا بيز
//     $entityManager->persist($invoice);
// }
// $entityManager->persist($invoice);


// الامر دا بيخلني ابحث علي صف معين في الجدول علي اساس الاي ديه
$invoice = $entityManager->find(Invoice::class, 1);
// وهنا غيرت قيمه كانت موجوده في عمود من اعمدته
$invoice->setStatus(InvoiceStatus::Paid);
// getItems لان الجدول بتاعي مربوط بجدول تاني في الفانكشن ديه بتودني للجدول التاني دا
// get(0) بقوله اول صف فيه بقا غير فيه قيمه من قيمه الاعمده بتاعته
$invoice->getItems()->get(0)->setDescription("Foo Bar");
// الامر دا هو الي بينفذ الكود
$entityManager->flush();