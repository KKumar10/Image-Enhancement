<?php include('connection.php');

//Adding authentication which checks the username variable
//If username variable is not detected then it directs user to home page.
if(!isset($_SESSION['username'])){
  header('location:home.php');
}

//If user is logged in and wish to logout, by pressing the logout button will direct them to homepage.
if(isset($_GET['logout'])) {
  session_destroy();
  unset($_SESSION['username']);
  header('location: home.php');
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="CSS/style.css?v=<?php echo time(); ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>
  <body class ="full-background">

    <!--If the user is successfully Loged in then it will display the following elements-->
    <?php if(isset($_SESSION['success'])) : ?>
    <div class="error success">
      <h3>
      <?php
      echo $_SESSION['success'];
      unset($_SESSION['success']);
       ?>
     </h3>
    </div>
  <?php endif ?>
    <header class="title">Image Enhancement</header>

    <!--If the user is successfully Loged in the username of the user will show up in navaigation bar with logout button.-->
    <nav>
      <div class="menu">
        <ul>
          <li><a href="#" class="active">Home</a></li>
          <?php if(isset($_SESSION['username'])) : ?>
          <li>
            <strong><?php echo $_SESSION['username']; ?></strong>
            <a href="index.php?logout='1'" class="logout-btn">Sign out</a>
          </li>
        </ul>
      </div>
    </nav>

    <!--Creating a drag and drop class to upload images-->
      <div class="drop-zone" id="drag-drop" >
        <div class="icon">
          <i class="fa fa-cloud-upload"></i>
          <p>Drag & Drop to Upload File</p>
          <span>OR</span>
        </div>

        <!--Adding a upload button to upload images-->
        <div class="choose-btn" id="choose-btn">
          <button type="button" name="button" class="fa fa-image"> Choose File</button>
          <input type="file" id="file-upload" hidden>
        </div>

        <!--Adding contianer class to display both images-->
        <div class="images-container">
          <div class="original-image" id="original-image">
            <p>Original Image</p>
          </div>

          <div class="enhanced-image" id="enhanced-image">
            <p>Enhanced Image</p>
          </div>
        </div>

        <!--Adding enhance  button-->
        <div class="enhance-img" id="enhance-img">
          <button class="enhance-btn" type="button" name="button"> Enhance Image</button>
        </div>

        <!--Adding referes and download button-->
        <div class="after-enhance" id="after-enhance">
          <button class="start-again" type="button" name="button"> Start Again</button>
          <button class="download-enhance" type="button" name="button"> Download </button>
        </div>

        <!--Adding ratio options as drop down for user to select ratios-->
        <div class="ratio_label">
          <label>Select ratio</label>
        </div>
        <select name="ratio" id="ratio">
          <option value="1x">1x</option>
          <option value="2x">2x</option>
          <option value="3x">3x</option>
          <option value="4x">4x</option>
        </select>

        <!--Adding format options as drop down for user to select image formats-->
        <div class="format_label">
          <label>Select the output format</label>
        </div>
        <select id="format" name="format">
            <option value="JPEG">JPEG</option>
            <option value="JPG">JPG</option>
            <option value="PNG">PNG</option>
            <option value="BMP">BMP</option>
        </select>

      </div>
      <script src="JS/form.js?v=<?php echo time(); ?>"></script>
      <script src="JS/api.js?v=<?php echo time(); ?>"></script>
  <?php endif ?>
 </body>
</html>
