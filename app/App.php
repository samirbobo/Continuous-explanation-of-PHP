<?php

declare (strict_types = 1);

namespace App;

use App\Contracts\EmailValidationInterface;
use App\Exceptions\RouteNotFoundException;
use App\Services\AbstractApi\EmailValidationService;
use Dotenv\Dotenv;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;
use Twig\Extra\Intl\IntlExtension;
use Twig\Loader\FilesystemLoader;

class App
{
    private Config $config;

    public function __construct(
        protected Container $container,
        protected ?Router $router = null,
        protected array $request = [],
    ) {
    }

    public function initDb(array $config)
    {
        $capsule = new Capsule();
        $capsule->addConnection($config);
        $capsule->setEventDispatcher(new Dispatcher($this->container));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }

    public function boot(): static
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();

        $this->config = new Config($_ENV);
        $this->initDb($this->config->db);

        $loader = new FilesystemLoader(VIEW_PATH);
        $twig = new Environment($loader, [
            'cache' => STORAGE_PATH . '/cache',
            // عشان تعيد تحميل ملف الكاش من غيرها اي تعديل مش هيظهر في الصفحه الا بعد حذف الملف ودا شي غير طبيعي
            'auto_reload' => true
        ]);

        $twig->addExtension(new IntlExtension());

        $this->container->bind(MailerInterface::class, fn() => new CustomMailer($this->config->mailer['dsn']));
        // $this->container->bind(EmailValidationInterface::class, fn() => new EmailableEmailValidationService($this->config->apikeys['emailable']));
        $this->container->bind(EmailValidationInterface::class, fn() => new EmailValidationService($this->config->apikeys['abstract_api_email_validation']));

        $this->container->singleton(Environment::class, fn() => $twig);

        return $this;
    }

    public function run()
    {
        try {
            echo $this->router->resolve($this->request['uri'], strtolower($this->request['method']));
        } catch (RouteNotFoundException) {
            http_response_code(404);

            echo View::make('error/404');
        }
    }
}
