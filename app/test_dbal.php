<?php

declare (strict_types = 1);

// Doctrine مجموعه  من المكتبات الخاصه بي بيه اتش بيه
// DataBase Abstraction Layer (DBAL):
// Doctrine هي جزء من 

use Doctrine\DBAL\DriverManager;
use Dotenv\Dotenv;

require_once __DIR__ . "/../vendor/autoload.php";
// Dotenv استخدمتها عشان استدعي البيانات المتخزنه في ملف الانف
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Doctrine الامر دا كله جاهز وجايبه من الموقع الرسمي الخاص بي
$connectionParams = [
    'dbname' => $_ENV["DB_DATABASE"],
    'user' => $_ENV["DB_USER"],
    'password' => $_ENV["DB_PASS"],
    'host' => $_ENV["DB_HOST"],
    'driver' => $_ENV["DB_DRIVER"] ?? 'pdo_mysql',
];
// بعمل ربط بي الداتا بيز
$conn = DriverManager::getConnection($connectionParams);

// بكتب الامر بتاعي
$stmt = $conn->prepare('SELECT * FROM emails');

// وبنفذه عشان يعرض النتيجه
$result = $stmt->executeQuery();

var_dump($result->fetchAllAssociative());