<?php
include('connection.php');
$activeForm = isset($_GET['form']) && $_GET['form'] === 'login' ? 'login' : 'register';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register / Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="CSS/style.css">

  <!-- Font Awesome for eye icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    /* Prevent Chrome autofill white background */
    input:-webkit-autofill {
      -webkit-box-shadow: 0 0 0px 1000px #1e1e1e inset !important;
      -webkit-text-fill-color: white !important;
    }

    .toggle-icon {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: #aaa;
      cursor: pointer;
    }

    .form-control {
      margin-bottom: 18px;
      display: flex;
      flex-direction: column;
      position: relative;
    }

    .form-control input {
      padding: 12px 15px;
      border: 1px solid #ccc;
      background: #1e1e1e;
      color: #fff;
      font-size: 16px;
      border-radius: 8px;
      transition: border 0.3s ease;
    }

    .form-control input:focus {
      border-color: #a855f7;
      outline: none;
    }

    .error-message {
      color: #ff6b6b;
      font-size: 13px;
      margin-top: 6px;
      padding-left: 5px;
    }

    .auth-card p {
      display: block;
      width: 100%;
      text-align: center;
      margin-top: 20px;
      font-size: 14px;
      color: #ccc;
    }
    .auth-card a {
      color: #a855f7;
      text-decoration: none;
      font-weight: bold;
    }

    .auth-card a:hover {
      text-decoration: underline;
      color: #d28aff;
    }
  </style>
</head>
<body>

<div class="auth-wrapper">
  <?php if ($activeForm === 'login'): ?>
    <!-- LOGIN FORM -->
    <form class="auth-card" action="form.php?form=login" method="post">
      <h2>Log In</h2>

      <div class="form-control">
        <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username ?? '') ?>">
        <span class="error-message"><?php echo $errors['username'] ?? ''; ?></span>
      </div>

      <div class="form-control">
        <input type="password" name="password_0" id="login_password" placeholder="Password">
        <i class="fa-solid fa-eye toggle-icon" onclick="togglePassword('login_password', this)"></i>
        <span class="error-message"><?php echo $errors['password'] ?? ''; ?></span>
      </div>

      <button type="submit" name="login_user">Log In</button>
      <p><a href="forgot_password.php">Forgot your password?</a></p>
      <p>Don't have an account? <a href="form.php">Register</a></p>
    </form>

  <?php else: ?>
    <!-- REGISTER FORM -->
    <form class="auth-card" action="form.php" method="post">
      <h2>Create Account</h2>

      <div class="form-control">
        <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username ?? '') ?>">
        <span class="error-message"><?php echo $errors['username'] ?? ''; ?></span>
      </div>

      <div class="form-control">
        <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email ?? '') ?>">
        <span class="error-message"><?php echo $errors['email'] ?? ''; ?></span>
      </div>

      <div class="form-control">
        <input type="password" name="password_0" id="reg_password" placeholder="Password">
        <i class="fa-solid fa-eye toggle-icon" onclick="togglePassword('reg_password', this)"></i>
        <span class="error-message"><?php echo $errors['password'] ?? ''; ?></span>
      </div>

      <div class="form-control">
        <input type="password" name="password_1" id="reg_password_confirm" placeholder="Confirm Password">
        <i class="fa-solid fa-eye toggle-icon" onclick="togglePassword('reg_password_confirm', this)"></i>
        <span class="error-message"><?php echo $errors['password_confirm'] ?? ''; ?></span>
      </div>

      <button type="submit" name="register_user">Register</button>
      <p>Already a member? <a href="form.php?form=login">Log in</a></p>
    </form>
  <?php endif; ?>
</div>

<script>
  function togglePassword(inputId, icon) {
    const input = document.getElementById(inputId);
    const type = input.getAttribute("type") === "password" ? "text" : "password";
    input.setAttribute("type", type);
    icon.classList.toggle("fa-eye");
    icon.classList.toggle("fa-eye-slash");
  }
</script>

</body>
</html>
