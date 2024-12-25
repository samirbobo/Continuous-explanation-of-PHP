<?php

declare (strict_types = 1);

use App\Controllers\HomeController;
use App\Controllers\InvoiceController;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Twig\Extra\Intl\IntlExtension;

require_once __DIR__ . '/../vendor/autoload.php';

define('STORAGE_PATH', __DIR__ . '/../storage');
define('VIEW_PATH', __DIR__ . '/../views');

// $container = new Container();
// $router = new Router($container);

// $router->registerRoutesFromControllerAttributes(
//     [
//         HomeController::class,
//         GeneratorExampleController::class,
//         InvoiceController::class,
//         UserController::class,
//         CurlController::class,
//     ]
// );

// (new App(
//     $container,
//     $router,
//     ['uri' => $_SERVER['REQUEST_URI'], 'method' => $_SERVER['REQUEST_METHOD']]
// ))->boot()->run();



$app = AppFactory::create();

$app->get("/", [HomeController::class, 'index']);
$app->get("/invoices", [InvoiceController::class, 'index']);

// Create Twig
$twig = Twig::create(VIEW_PATH, [
    'cache' => STORAGE_PATH . '/cache',
    'auto_reload' => true
]);

$twig->addExtension(new IntlExtension());

// Add Twig-View Middleware
$app->add(TwigMiddleware::create($app, $twig));

$app->run();