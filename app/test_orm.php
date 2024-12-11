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

// createQueryBuilder فانكشن بتخلني اكتب اوامر الداتا بيز علي شكل فانكشنس لتسهيل التعامل بين الداتا بيز و بي اتش بيه
$queryBuilder = $entityManager->createQueryBuilder();

$query = $queryBuilder
    ->select('i.createAt', 'i.amount')
    ->from(Invoice::class, "i")
    ->where(
        $queryBuilder->expr()->andX(
            $queryBuilder->expr()->lt("i.amount", ":amount"),
            $queryBuilder->expr()->orX(
                $queryBuilder->expr()->eq("i.status", ":status"),
                $queryBuilder->expr()->gte("i.createAt", ":date")
            )
        )
    )
    ->setParameter("amount", 100)
    ->setParameter("status", InvoiceStatus::Paid)
    ->setParameter("date", "2022-03-25 00:00:00")
    ->orderBy("i.createAt", "desc")
    ->getQuery();

//getDQL الفانكشن ديه بتعرض شكل الكويري الي كتبتها باستخدام الفانكشنس الي في السطور السابقه وفايدتها انك تتاكد
// هل الكويري بتاعتك هتترجم صح ولا في اي مشكله
echo $query->getDQL();
// النتيجه
// SELECT i.createAt, i.amount FROM App\Entity\Invoice i WHERE i.amount < :amount AND (i.status = :status OR i.createAt >= :date) ORDER BY i.createAt desc

/*
التلت سطور دول لما بحطهم بيدوني النتيجه الي تحتهم وانا عايز اعمل شرط مختلف وعشان كده المكتبه موفره
فانكشن تقدر تعمل بيها شرط خاص يناسب شغلك بشكل مخصوص
->where("i.amount < :amount")
->andWhere("i.status = :status")
->orWhere("i.createAt >= :date")
WHERE (i.amount < :amount AND i.status = :status) OR i.createAt >= :date دا مش مطلوب
WHERE i.amount < :amount AND (i.status = :status OR i.createAt >= :date) دا المطلوب وعشان اوصله بعمل الاومر التاليه

custom where
expr => new expression
andX => and where
orX => or where
lt => smaller thant <=
eq => equal than =
gte => getter than or equal >=
gt => getter than >=
where(
        $queryBuilder->expr()->andX(
            $queryBuilder->expr()->lt("i.amount", ":amount"),
            $queryBuilder->expr()->orX(
                $queryBuilder->expr()->eq("i.status", ":status"),
                $queryBuilder->expr()->gte("i.createAt", ":date")
            )
        )
    )
*/
exit;

$invoices = $query->getResult();

var_dump($invoices);
