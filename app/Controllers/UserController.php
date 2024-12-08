<?php

declare (strict_types = 1);

namespace App\Controllers;

use App\Attributes\Get;
use App\Attributes\Post;
use App\View;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class UserController
{
    public function __construct(protected MailerInterface $mailer)
    {
    }

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

        // symfony.mailer اولا ببني الرساله و بستخدم الكلاس المبني مسبقا من مكتبه  
        // ومن المميزات الي بتدهيني فانكشن بحط فيها اسم المرسل و فانكشن بحط فيها اسم الشخص الي هتروحله الرساله
        // و فانكشن بحط فيها اسم الموضوع بتاع الرساله
        // وفانكشن بتقبل مني اضافه اي ملفات
        // text , html وفانكشن بتقبل مني المحتوي وفي منها نوعين 
        $email = (new Email())
            ->from('support@example.com')
            ->to($email)
            ->subject('Welcome!')
            ->attach('Hello World!', 'welcome.txt')
            ->text($text)
            ->html($html);

        $this->mailer->send($email);

        // mailer بعد ما ببني الرساله بحتاج حاجه اسمها المرسل 
        // transport وبجبها من الكلاس الجاهز برده من نفس المكتبه الي قولنا عليها قبل كده وبتقبل مني حاجه اسمها
        // Transport::fromDsn وعشان اعرفه بستخدم امر ثابت من المكتبه وهو
        // ودا بيقبل مني نص بيعبر عن مصدر السيرفر الي هيتبعت عليه الرسايل وانا واخد مصدر مفتوح المصدر وباني بيه 
        // حاويه ومشغلها والكود التالي بيوضح الشرح بطريقه مبسطه بس احنا في الكورس اتبعنا اسلوب الحقن واستلمنا
        // construct الكلان دا كله باستخدام 

        // $dsn = "smtp://mailhog:1025";
        // $transport = Transport::fromDsn($dsn); // DSN => Data Source Name
        // $mailer = new Mailer($transport);

    }
}
