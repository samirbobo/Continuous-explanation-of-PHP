<?php

declare (strict_types = 1);

namespace App\Controllers;

use App\Attributes\Get;
use App\Attributes\Post;
use App\Models\Email;
use App\View;
use Symfony\Component\Mime\Address;

class UserController
{
    // الصفحه ديه عشان اكريت يوزر جديد و ابعتله رساله علي الميل
    #[Get('/users/create')]
    public function create(): View
    {
        return View::make('users/register');
    }

    // الصفحه ديه عشان اخد بيانات المستخدم الي اضافه جديده و ابعت بيها ايميل
    #[Post('/users')]
    public function register()
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $firstName = explode(' ', $name)[0];

        // دا محتوي الرساله الي هبعتها ليه
        $text = <<<Body
Hello $firstName,

Thank you for signing up!
Body;

        // ودا شكل تاني لمحتوي الرساله اني اقدر ابعتها علي شكل اتش ام ال
        $html = <<<HTMLBody
<h1 style="text-align: center; color: blue;">Welcome</h1>
Hello $firstName,
<br /><br />
Thank you for signing up!
HTMLBody;

        // بعد ما جهزت شكل الاميل بتاعي بعته عشان يتخزن في الكلاس المسوله بدا 
        (new Email())->queue(
            new Address($email),
            new Address('support@example.com', 'Support'),
            "Welcome",
            $html,
            $text
        );
    }
}
