<?php
//
//require_once '../vendor/autoload.php';
//require '../constant/const.php';
//
//// Create the Transport, an email server that is responsible for receiving  and sending email
////using the gmail server, port 
//$transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, "ssl"))
//        ->setUsername(EMAIL)
//        ->setPassword(PASSWORD);
//
//
//// Create the Mailer using your created Transport
//$mailer = new Swift_Mailer($transport);
//
//function sendVerificationEmail($Email, $token) {
//    //bring the mailer object inside our function
//    global $mailer;
//
//    $body = '<!DOCTYPE html>
//<html lang = "en">
//    <head>
//        <meta charset="UTF-8">
//        <title>Verify email</title>
//    </head>
//    <body>
//        <div class="wrapper">
//            <p>
//                Thank you for signing up on our website. Please click on the link below
//                to verify your email.
//
//            </p>
//            <a href="http://localhost/userVerification/views/homepage.php?token=' . $token . '">
//                Verify your email address
//            </a>
//        </div>
//    </body>
//</html>';
//
//// Create a message
//    $message = (new Swift_Message('Verify your email address'))
//            ->setFrom(EMAIL)
//            ->setTo($Email)
//            ->setBody($body, "text/html");
//
//// Send the message
//    $result = $mailer->send($message);
//}
