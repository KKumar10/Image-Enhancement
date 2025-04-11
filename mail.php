<?php
function sendVerificationEmail($email, $token) {
    $subject = "Verify Your Account";
    $link = "http://localhost/Image-Enhancement-main/verification.php?email=$email&token=$token";
    $message = "Click the link to verify your email:\n\n$link";

    $headers = "From: no-reply@yourdomain.com";

    mail($email, $subject, $message, $headers);
}
