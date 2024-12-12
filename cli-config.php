<?php
// Database Migrations دا الملف الي بينشاء ليا 
// app وبعد ما بكتب كل البيانات التاليه بفتح الترمنال واخش علي الكمبونانت الخاص بي 
// ./vendor/bin/doctrine-migrations generate واكتب الامر دا 
// Database Migrations عشان يتم في النهائي انشاء ال 
require 'vendor/autoload.php';

use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$config = new PhpFile('migrations.php'); 

$params = [
    'host' => $_ENV["DB_HOST"],
    'user' => $_ENV["DB_USER"],
    'password' => $_ENV["DB_PASS"],
    'dbname' => $_ENV["DB_DATABASE"],
    'driver' => $_ENV["DB_DRIVER"] ?? 'pdo_mysql',
];

$paths = [__DIR__ . '/app/Entity'];

$entityManager = EntityManager::create(
    $params,
    Setup::createAttributeMetadataConfiguration($paths)
);


return DependencyFactory::fromEntityManager($config, new ExistingEntityManager($entityManager));
