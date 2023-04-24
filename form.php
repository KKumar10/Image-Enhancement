<?php include('connection.php') ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>First Project</title>
    <!--<link rel="stylesheet" href="CSS/style.css">-->
    <!--<link rel="stylesheet" href="CSS/style.css?v=<?php echo time(); ?>"> -->
    <link rel="stylesheet" href="CSS/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>
  <body>
    <!-- Creating a navigation bar with register or login button. -->
      <nav>
        <div class="menu">
          <ul>
            <li><a href="home.php" class="active">Home</a></li>
            <a href="form.php" class="toggle-btn">Login or Register</a>
          </ul>
        </div>
    </nav>

    <!-- Creating Register and Login form with register and login button. -->
    <div class="hero">
      <div class="form-box">
        <div class="button-box">
          <div id="btn"></div>
          <button type="button" class="toggle-btn" onclick="login()">Log In</button>
          <button type="button" class="toggle-btn" onclick="register()">Register</button>
        </div>

        <!-- Login Form-->
        <form class="input-group" id="login" action="form.php" method="post" >
          <input type="text" class="input-field" name="username" id="username" placeholder="Username" onfocus="this.removeAttribute('placeholder');" onblur="this.setAttribute('placeholder', 'Username');" value="<?php echo $username; ?>">
          <input type="password" class="input-field" name="password_0" id="password" placeholder="Enter Password" onfocus="this.removeAttribute('placeholder');" onblur="this.setAttribute('placeholder', 'Enter Password');" ><br></br>
          <button type="submit" name="login_user" class="submit-btn">Log in</button>
        </form>

        <!-- Registration Form-->
        <form class="input-group" id="register" action="form.php" method="post">
          <input type="text" class="input-field" name="username" id="username" placeholder="Username" onfocus="this.removeAttribute('placeholder');" onblur="this.setAttribute('placeholder', 'Username');" value="<?php echo $username; ?>">
          <input type="email" class="input-field" name="email" id="email" placeholder="Email" onfocus="this.removeAttribute('placeholder');" onblur="this.setAttribute('placeholder', 'Email');" value="<?php echo $email;?>">
          <input type="password" class="input-field" name="password_0" placeholder="Enter Password" onfocus="this.removeAttribute('placeholder');" onblur="this.setAttribute('placeholder', 'Enter Password');">
          <input type="password" class="input-field" name="password_1" placeholder="Confirm Password" onfocus="this.removeAttribute('placeholder');" onblur="this.setAttribute('placeholder', 'Confirm Password');"><br></br>
          <button type="submit" name="register_user" class="submit-btn">Register</button>
          <a href="#" class="member-btn" onclick="member()">Already a member?</a>
        </form>
      </div>
    </div>
    
    <script src="JS/form.js?v=<?php echo time(); ?>"></script>
    <script src="JS/api.js?v=<?php echo time(); ?>"></script>
  </body>
</html>
