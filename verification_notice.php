<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Check Your Email</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: sans-serif;
      background-color: #1a1a1a;
      color: white;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      text-align: center;
    }

    h2 {
      color: #7e1ce0;
    }

    a {
      color: #b379f4;
      text-decoration: none;
      margin-top: 20px;
      display: inline-block;
    }

    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <h2>📧 Please check your email!</h2>
  <p>We've sent a verification link to your inbox.<br>
     You need to click it to complete your registration.</p>

  <a href="form.php?form=login">Already verified? Log in</a>

</body>
</html>
