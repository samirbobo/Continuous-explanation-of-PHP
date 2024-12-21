<?php

declare (strict_types = 1);

namespace App\Controllers;

use App\Attributes\Get;
use App\Contracts\EmailValidationInterface;

class CurlController
{
    public function __construct(private EmailValidationInterface $emailValidationService)
    {
    }

    #[Get('/curl')]
    public function index()
    {
        $email = "samirelanany555@gmail.com";
        $result = $this->emailValidationService->verify($email);

        $score = $result->score;
        $deliverable = $result->isDeliverable;

        var_dump($score, $deliverable);

        echo '<pre>';
        print_r($result);
        echo '</pre>';
    }
}
