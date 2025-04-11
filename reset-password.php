<?php
session_start();
$db = mysqli_connect('localhost', 'root', '', 'image_upscale');

if (!isset($_GET['token']) || empty($_GET['token'])) {
    die("Invalid or missing token.");
}

$token = mysqli_real_escape_string($db, $_GET['token']);
$query = "SELECT * FROM users WHERE reset_token = '$token' LIMIT 1";
$result = mysqli_query($db, $query);

if (!$result || mysqli_num_rows($result) !== 1) {
    die("Invalid or expired token.");
}

$user = mysqli_fetch_assoc($result);
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = mysqli_real_escape_string($db, $_POST['password']);
    $confirm = mysqli_real_escape_string($db, $_POST['confirm_password']);

    if (empty($password) || empty($confirm)) {
        $error = "Please fill in all fields.";
    } elseif (!preg_match("/^(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{8,}$/", $password)) {
        $error = "Password must be at least 8 characters, include one uppercase letter, one number, and one special character.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $update = "UPDATE users SET password = '$hashed', reset_token = NULL WHERE id = " . $user['id'];
        if (mysqli_query($db, $update)) {
            $success = "✅ Password reset successful! <a href='form.php?form=login'>Click here to log in</a>.";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #0f0c29, #302b63, #24243e);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .box {
      background-color: rgba(0, 0, 0, 0.85);
      padding: 30px 30px 40px;
      border-radius: 20px;
      width: 400px;
      box-shadow: 0 0 30px rgba(138, 43, 226, 0.5);
      color: white;
    }

    .box h2 {
      text-align: center;
      margin-bottom: 20px;
      font-size: 24px;
    }

    .form-control {
      margin-bottom: 20px;
    }

    .input-wrapper {
      position: relative;
      width: 100%;
    }

    .input-wrapper input {
      width: 100%;
      padding: 12px 40px 12px 15px;
      border-radius: 8px;
      border: none;
      background-color: #1e1e1e;
      color: #fff;
      font-size: 15px;
      box-sizing: border-box;
    }

    .toggle-icon {
      position: absolute;
      top: 50%;
      right: 12px;
      transform: translateY(-50%);
      color: #aaa;
      cursor: pointer;
      z-index: 2;
    }

    .error-message {
      color: #ff6b6b;
      font-size: 14px;
      margin-top: 8px;
      padding-left: 2px;
    }

    .success-message {
      color: #00ff99;
      font-size: 14px;
      margin-top: 8px;
      text-align: center;
    }

    button {
      width: 100%;
      padding: 14px;
      border: none;
      border-radius: 25px;
      background: linear-gradient(to right, #6A14BA, #B379F4);
      color: #fff;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
    }

    button:hover {
      background: linear-gradient(to right, #7e1ce0, #d28aff);
    }

    a {
      color: #b379f4;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="box">
    <h2>Reset Password</h2>

    <?php if (!empty($success)): ?>
      <div class="success-message"><?php echo $success; ?></div>
    <?php else: ?>
      <form method="POST">
        <div class="form-control">
          <div class="input-wrapper">
            <input type="password" name="password" id="new_password" placeholder="New Password" required>
            <i class="fa-solid fa-eye toggle-icon" onclick="togglePassword('new_password', this)"></i>
          </div>
          <?php if (!empty($error) && str_contains($error, 'uppercase')): ?>
            <div class="error-message"><?php echo $error; ?></div>
          <?php endif; ?>
        </div>

        <div class="form-control">
          <div class="input-wrapper">
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
            <i class="fa-solid fa-eye toggle-icon" onclick="togglePassword('confirm_password', this)"></i>
          </div>
          <?php if (!empty($error) && !str_contains($error, 'uppercase')): ?>
            <div class="error-message"><?php echo $error; ?></div>
          <?php endif; ?>
        </div>

        <button type="submit">Reset Password</button>
      </form>
    <?php endif; ?>
  </div>

  <script>
    function togglePassword(fieldId, iconElement) {
      const input = document.getElementById(fieldId);
      const type = input.type === "password" ? "text" : "password";
      input.type = type;
      iconElement.classList.toggle("fa-eye");
      iconElement.classList.toggle("fa-eye-slash");
    }
  </script>
</body>
</html>
