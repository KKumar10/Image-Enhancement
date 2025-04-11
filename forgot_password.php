<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// PHPMailer includes
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';
require __DIR__ . '/PHPMailer/src/Exception.php';

// DB connection
$db = mysqli_connect('localhost', 'root', '', 'image_upscale') or die("Database connection failed.");
$success = "";
$error = "";

if (isset($_POST['send_reset_link'])) {
    $email = mysqli_real_escape_string($db, $_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        $query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
        $result = mysqli_query($db, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $token = md5(time() . $email);

            // ✅ Save token to database
            $saveToken = "UPDATE users SET reset_token = '$token' WHERE email = '$email'";
            mysqli_query($db, $saveToken);

            // Send reset email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'krunalk1004@gmail.com'; // ✅ Your Gmail
                $mail->Password = 'jzmwxibqjqjaxtrk';      // ✅ Your App password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('krunalk1004@gmail.com', 'Image Enhancer');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Reset Your Password';
                $mail->Body    = "
                    <h3>Password Reset Request</h3>
                    <p>We received a request to reset your password. Click the link below to continue:</p>
                    <a href='http://localhost/Image-Enhancement-main/reset-password.php?token=$token'>Reset Password</a>
                    <p>If you didn't request this, you can ignore this email.</p>
                ";

                $mail->send();
                $success = "✅ Password reset link has been sent to your email.";

            } catch (Exception $e) {
                $error = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }

        } else {
            $error = "No account found with that email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password</title>
  <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
<div class="auth-wrapper">
  <form class="auth-card" action="" method="post">
    <h2>Forgot Password</h2>

    <?php if ($success): ?>
      <p style="color:lightgreen; text-align:center;"><?php echo $success; ?></p>
    <?php elseif ($error): ?>
      <p style="color:red; text-align:center;"><?php echo $error; ?></p>
    <?php endif; ?>

    <div class="form-control">
      <input type="email" name="email" placeholder="Enter your email" required>
    </div>

    <button type="submit" name="send_reset_link">Send Reset Link</button>
    <p><a href="form.php?form=login">Back to login</a></p>
  </form>
</div>
</body>
</html>
