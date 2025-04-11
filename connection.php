<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer files
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';
require __DIR__ . '/PHPMailer/src/Exception.php';

// Initialize variables
$username = "";
$email = "";
$errors = [];

// Connect to the database
$db = mysqli_connect('localhost', 'root', '', 'image_upscale') or die("Database connection failed.");

// REGISTER USER
if (isset($_POST['register_user'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password_0 = mysqli_real_escape_string($db, $_POST['password_0']);
    $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);

    // Field-specific validation
    if (empty($username)) {
        $errors['username'] = "Username is required";
    } elseif (strlen($username) < 5) {
        $errors['username'] = "Username must be at least 5 characters";
    }

    if (empty($email)) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Please enter a valid email";
    }

    if (empty($password_0)) {
        $errors['password'] = "Password is required";
    } elseif (!preg_match("/^(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{8,}$/", $password_0)) {
        $errors['password'] = "Password must be at least 8 characters and include one uppercase letter, one number, and one special character.";
    }

    if ($password_0 !== $password_1) {
        $errors['password_confirm'] = "Passwords do not match";
    }

    // Check if user already exists
    $check_user = "SELECT * FROM users WHERE username = '$username' OR email = '$email' LIMIT 1";
    $result = mysqli_query($db, $check_user);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        if ($user['username'] === $username) {
            $errors['username'] = "Username already taken";
        }
        if ($user['email'] === $email) {
            $errors['email'] = "Email already taken";
        }
    }

    // If no errors, send verification email (don't insert user until verified)
    if (count($errors) === 0) {
        $verification = md5(time() . $username);

        $mail = new PHPMailer(true);
        try {
            // SMTP Configuration
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'krunalk1004@gmail.com';
            $mail->Password   = 'jzmwxibqjqjaxtrk';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            // Sender and Recipient
            $mail->setFrom('krunalk1004@gmail.com', 'Image Enhancer');
            $mail->addAddress($email, $username);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Verify Your Email';
            $mail->Body    = "
                <h3>Email Verification</h3>
                <p>Hi <b>$username</b>,</p>
                <p>Click the link below to verify your account:</p>
                <a href='http://localhost/Image-Enhancement-main/verification.php?verification=$verification'>
                  Verify Email
                </a>
            ";

            $mail->send();

            $hashedPassword = password_hash($password_0, PASSWORD_DEFAULT);
            $insert = "INSERT INTO users (username, email, password, verification, is_verified)
                       VALUES ('$username', '$email', '$hashedPassword', '$verification', 0)";
                       mysqli_query($db, $insert);

                       $_SESSION['success'] = "Registration successful! Please verify your email.";
                       header('Location: verification_notice.php');
                       exit();


        } catch (Exception $e) {
            $errors['email'] = "Verification email failed: {$mail->ErrorInfo}";
        }
    }
}

// LOGIN USER
if (isset($_POST['login_user'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password_0']);

    if (empty($username)) {
        $errors['username'] = "Username is required";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required";
    }

    if (count($errors) === 0) {
        $query = "SELECT id, username, password, is_verified FROM users WHERE username='$username' LIMIT 1";
        $result = mysqli_query($db, $query);

        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);

            if (!password_verify($password, $user['password'])) {
                $errors['password'] = "Incorrect password";
            } elseif ($user['is_verified'] == 0) {
                $errors['password'] = "Please verify your email before logging in.";
            } else {
                $_SESSION['username'] = $user['username'];
                $_SESSION['success'] = "";
                header('Location: index.php');
                exit();
            }
        } else {
            $errors['username'] = "User not found";
        }
    }
}
?>
