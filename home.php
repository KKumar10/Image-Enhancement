<?php include('connection.php'); ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--<link rel="stylesheet" href="CSS/style.css">-->
    <link rel="stylesheet" href="CSS/style.css?v=<?php echo time(); ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>
  <body class ="full-background">

    <!-- Creating a title for the website. -->
    <header class="title">Image Enhancement</header>

    <!--Creating navigation bar -->
      <nav>
        <div class="menu">
          <ul>
            <li><a href="home.php" class="active">Home</a></li>
            <a href="form.php" class="toggle-btn">Login or Register</a>
          </ul>
        </div>
      </nav>

      <!--Creating a box which asks users to register or login. -->
      <div class="drop-zone"id="drag-drop" >
        <div class="icon">
          <p>Please Login or Register to Enhance the Image</p>
        </div>
      </div>

      <script src="JS/form.js?v=<?php echo time(); ?>"></script>
      <script src="JS/api.js?v=<?php echo time(); ?>"></script>
  </body>
</html>
