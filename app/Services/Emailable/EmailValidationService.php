<?php

declare (strict_types = 1);

namespace App\Services\Emailable;

use App\Contracts\EmailValidationInterface;
use App\DTO\EmailValidationResult;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

class EmailValidationService implements EmailValidationInterface
{
    private string $baseUrl = 'https://api.emailable.com/v1/';

    public function __construct(private string $apiKey)
    {

    }

    public function verify(string $email): EmailValidationResult
    {
      // في حاله ان الطلب للسيرفر كان في مشكله او خطاء بستخدم الاسلوب دا عشان يطبع رساله توضحيه للمستخدم عشان يفهم في ايه
      $stack = HandlerStack::create();

      $maxRetry = 3;

      $stack->push($this->getRetryMiddleware($maxRetry));


        // Guzzle Client مكتبه جديده لعمل طلبات ابي ايه بشكل احترافي اكتر وبشكل اسهل 
        $client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => 5,
            'handler' => $stack
        ]);

        $params = [
            'api_key' => $this->apiKey,
            'email' => $email,
        ];

        $response = $client->get("verify", ["query" => $params]);

        $body = json_decode($response->getBody()->getContents(), true);
        return new EmailValidationResult($body["score"], $body['state'] === 'deliverable');
    }

    // دا الكود لاكتشاف اي اخطاء في الطلبات
    public function getRetryMiddleware(int $maxRetry) {
      return Middleware::retry(
        function(
          int $retries, 
          RequestInterface $request, 
          ?ResponseInterface $response = null, 
          ?RuntimeException $e = null) use($maxRetry) {
            if($retries > $maxRetry) {
              echo "test one" . PHP_EOL;
              return false;
            }

            if($response && in_array($response->getStatusCode(), [249, 429, 503])) {
              return true;
            }

            if($e instanceof ConnectException) {
              return true;
            }

            return false;
        }
      );
    }
}