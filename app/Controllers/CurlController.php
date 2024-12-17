<?php

declare (strict_types = 1);

namespace App\Controllers;

use App\Attributes\Get;

/*
cURL:
HTTP  وهي مكتبة في بي اتش بيه تستخدم لإرسال واستقبال البيانات عبر
APIs  تُستخدم في التعامل مع الـ
GET و POST وتنفيذ طلبات وغيرها من الطلبات
 */

// client URL (cURL) الكلاس مسؤول عن التعامل مع ال
// APIs وهي أداة لاستدعاء الروابط والـ
class CurlController
{
    // /curl يتم ربط الفانكشن  مع الرابط
    // عندما يُستدعى الرابط  سيتم تنفيذ الكود الموجود داخل الدالة
    #[Get('/curl')]
    public function index()
    {
        $handle = curl_init(); // cURL تهيئة جلسة جديدة.

        $apiKey = $_ENV['EMAILABLE_API_KEY'];
        $email = 'samirelanany555@gmail.com';

        // request تحديد الرابط الذي سيتم عمل ليه
        $url = "https://api.emailable.com/v1/verify?email=". $email . "&api_key=" . $apiKey;

        /*
        curl_setopt() تُستخدم لتحديد الخيارات لجلسة
        $handle: cURL مؤشر جلسة.
        CURLOPT_URL: الخيار الذي يُحدد الرابط الهدف.
        $url: القيمة المُراد تعيينها (الرابط).
         */
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true); // إرجاع النتيجة كنص

        // curl_exec(): cURL يُنفذ الطلب المخصص لجلسة
        // يتم عرض محتوى الرد في المتصفح مباشرة
        $response = curl_exec($handle);

        if ($response != false) {
            $data = json_decode($response, true);

            echo '<pre>';
            print_r($data);
        }
    }
}
