<?php include('connection.php');

if(!isset($_SESSION['username'])){
  header('location:home.php');
}
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
<body class="full-background">

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

<div class="drop-zone" id="drag-drop">
  <div class="icon">
    <i class="fa fa-cloud-upload"></i>
    <p>Drag & Drop to Upload File</p>
    <span>OR</span>
  </div>

  <div class="choose-btn" id="choose-btn">
    <button type="button" class="fa fa-image"> Choose File</button>
    <input type="file" id="file-upload" hidden>
  </div>

  <div class="images-container">
    <div class="original-image" id="original-image">
      <p>Original Image</p>
    </div>

    <div class="enhanced-image" id="enhanced-image">
      <p>Enhanced Image</p>

      <!-- ✅ Progress Bar placed under heading -->
      <div id="progress-wrapper">
        <div class="progress-bar-container">
          <div id="progress-bar"></div>
        </div>
        <p id="progress-text">0%</p>
      </div>

    </div>
  </div>

  <div class="enhance-img" id="enhance-img">
    <button class="enhance-btn" type="button">Enhance Image</button>
  </div>

  <div class="after-enhance" id="after-enhance">
    <button class="start-again" type="button">Start Again</button>
    <button class="download-enhance" type="button">Download</button>
  </div>

  <div class="format_label">
    <label>Select the output format</label>
  </div>
  <select id="format" name="format">
    <option value="JPEG">JPEG</option>
    <option value="JPG">JPG</option>
    <option value="PNG">PNG</option>
    <option value="BMP">BMP</option>
  </select>

  <div class="face_label">
    <label><input type="checkbox" id="face" checked> Enhance Faces</label>
  </div>
  <div class="clarity_label">
    <label><input type="checkbox" id="clarity" checked> Boost Clarity</label>
  </div>

</div>

<script src="JS/form.js?v=<?php echo time(); ?>"></script>
<script src="JS/api.js?v=<?php echo time(); ?>"></script>
<?php endif ?>
</body>
</html>
