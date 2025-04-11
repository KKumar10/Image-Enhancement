<?php
session_start();
$db = mysqli_connect('localhost', 'root', '', 'image_upscale');

// 1. Check for token
if (!isset($_GET['verification']) || empty($_GET['verification'])) {
    $status = 'error';
    $message = "❌ Invalid or missing verification link.";
} else {
    $token = mysqli_real_escape_string($db, $_GET['verification']);
    $query = "SELECT * FROM users WHERE verification = '$token' LIMIT 1";
    $result = mysqli_query($db, $query);

    if (!$result || mysqli_num_rows($result) === 0) {
        $status = 'error';
        $message = "❌ Invalid verification link.";
    } else {
        $user = mysqli_fetch_assoc($result);
        if ($user['is_verified'] == 1) {
            $status = 'info';
            $message = "📩 Email already verified.";
        } else {
            mysqli_query($db, "UPDATE users SET is_verified = 1 WHERE id = " . $user['id']);
            $status = 'success';
            $message = "✅ Email successfully verified!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Email Verification</title>
  <meta http-equiv="refresh" content="<?= ($status === 'success') ? '5;url=form.php?form=login' : '' ?>">
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #6A14BA, #B379F4);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      color: white;
      animation: fadeIn 1s ease-in;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .wrapper {
      background-color: #0f0f0f;
      padding: 40px 30px;
      border-radius: 20px;
      text-align: center;
      box-shadow: 0 0 25px rgba(128, 0, 255, 0.4);
      max-width: 500px;
      animation: fadeIn 0.8s ease-in-out;
    }

    .icon {
      font-size: 48px;
      margin-bottom: 15px;
    }

    h2 {
      font-size: 26px;
      margin-bottom: 10px;
      color: <?= $status === 'error' ? '#ff6b6b' : '#cc7eff' ?>;
    }

    p {
      font-size: 16px;
      color: #ccc;
      margin-bottom: 25px;
    }

    .btn {
      display: inline-block;
      padding: 12px 30px;
      background: linear-gradient(to right, #6A14BA, #B379F4);
      border-radius: 25px;
      color: #fff;
      text-decoration: none;
      font-weight: bold;
      transition: background 0.3s ease;
    }

    .btn:hover {
      background: linear-gradient(to right, #7e1ce0, #d28aff);
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <?php if ($status === 'success'): ?>
      <div class="icon">✅</div>
      <h2><?= $message ?></h2>
      <p>You can now proceed to the next step.<br>Redirecting to login...</p>
      <a href="form.php?form=login" class="btn">Go to Login</a>

    <?php elseif ($status === 'info'): ?>
      <div class="icon">📩</div>
      <h2><?= $message ?></h2>
      <p>You may now log in to your account.</p>
      <a href="form.php?form=login" class="btn">Go to Login</a>

    <?php else: ?>
      <div class="icon">❌</div>
      <h2><?= $message ?></h2>
      <p>Please make sure your verification link is valid.</p>
      <a href="form.php?form=register" class="btn">Try Again</a>
    <?php endif; ?>
  </div>
</body>
</html>
