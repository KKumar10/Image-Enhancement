<?php include('connection.php');

if(!isset($_SESSION['username'])){
  header('location:home.php');
}

if(isset($_GET['logout'])) {
  session_destroy();
  unset($_SESSION['username']);
  header('location: form.php');
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--<link rel="stylesheet" href="CSS/style.css">-->
    <link rel="stylesheet" href="CSS/style.css?v=<?php echo time(); ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sansita&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>
  <body>
    <header>
      <div class="verify">
        <i class="fa fa-envelope"></i>
        <h2>Thank you for registering!<h2>
          <p>The verification email has been sent to the given address. Please verify your email. If you are unable to see the verification email from us, make sure you give your spam folder a glance.</p>
      </div>
    </header>
  </body>
</html>
