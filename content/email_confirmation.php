<?php

echo 'EMAIL SENT';

$event = $_GET['event'];

$to      = 'sales@mrteasdale.com';
$subject = 'Booking Confirmed - Tickets Enclosed';
$message = 'Congratulations, you are on your way to ' . $event;
$headers = 'From: myran.teasdale@northumbria.ac.uk'       . "\r\n" .
    'Reply-To: myran.teasdale@northumbria.ac.uk' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);
?>