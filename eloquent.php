<?php

declare(strict_types = 1);

require_once __DIR__ . "/vendor/autoload.php";

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

// Dotenv استخدمتها عشان استدعي البيانات المتخزنه في ملف الانف
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// بجيب البيانات بتاع السيرفر بتاع الداتا بيز
$params = [
    'host' => $_ENV["DB_HOST"],
    'username' => $_ENV["DB_USER"],
    'password' => $_ENV["DB_PASS"],
    'database' => $_ENV["DB_DATABASE"],
    'driver' => $_ENV["DB_DRIVER"] ?? 'mysql',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
];


// ديه شبه النقطه المركزيه الي بتربط بين الداتا بيز والكود البرمجي بتاعي
$capsule = new Capsule;

$capsule->addConnection($params);
$capsule->setEventDispatcher(new Dispatcher(new Container));
$capsule->setAsGlobal();
$capsule->bootEloquent();
