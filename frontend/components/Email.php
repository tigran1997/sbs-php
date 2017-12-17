<?php
/**
 * Created by PhpStorm.
 * User: Tigran
 * Date: 12/16/2017
 * Time: 1:48 AM
 */

class Email
{
    public static function sendMail($from = "info@sbs-banking.com",
                              $subject ="From SBS Bank",
                              $to = ['name' => "User","email" => "test@example.com"],
                              $content = ['type' => "text/plain","value" => ""]){

        $from = new SendGrid\Email("SBS Bank Admissions", "info@sbs-banking.com");
        $to = new SendGrid\Email($to['name'], $to['email']);
        $content = new SendGrid\Content($content['type'], $content['value']);
        $mail = new SendGrid\Mail($from, $subject, $to, $content);
        $apiKey = SENDGRID_API_KEY;
        $sg = new \SendGrid($apiKey);
        $response = $sg->client->mail()->send()->post($mail);
//        echo $response->statusCode();
//        print_r($response->headers());
//        echo $response->body();
    }

}