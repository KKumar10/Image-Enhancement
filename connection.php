<?php
//This will start a session and track the user login.
session_start();

//Initialising variables.
$username = "";
$email = "";
$password = "";
$server = "";
$dbname ="";
$errors = array();

//Connecting to the database.
$db = mysqli_connect('localhost', 'root', '', 'image_enhancement') or die("Database Connection Failed...");

//Registering the users and accessing the variable through POST array.
if(isset($_POST['register_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_0 = mysqli_real_escape_string($db, $_POST['password_0']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);

  //Adding validation to check if the username field is empty.
  if(empty($username)){
    array_push($errors, "Username is Required");
  }

  //Adding validation to check if the username is less then 5 characters.
  if(strlen($username) < 5){
    array_push($errors,"Your username must have more then 5 characters.");
  }

  //Adding validation to check if the email field is empty.
  if(empty($email)){
    array_push($errors, "Email is Required");
  }

  //Adding validation to check if user inputs the valid email.
  if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    array_push($errors, "Please enter the valid email.");
  }
  //Adding validation to check if the password field is empty.
  if(empty($password_0)){
    array_push($errors, "Password is Required");
  }
  //Adding validation to check if boths password matches.
  if($password_0 != $password_1) {
    array_push($errors, "Passwords does not match.");
  }

  //Adding validation to check for exisiting username or email so every user can have unique username and email.
  $user_check_query = "SELECT * FROM users WHERE username = '$username' or email = '$email' LIMIT 1";
  $results = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($results);

  if($user) {
    //Checking if the username already exists then give errors.
    if($user['username'] === $username) {
      array_push($errors, "Username already taken");
    }

    //Checking if the email already exists then give errors.
    if($user['email'] === $email) {
      array_push($errors, "Email already taken");
    }
  }

  //Registering the user only if there are no errors and hashing password.
  if(count($errors) == 0) {
    $verification = md5 (time().$username);
    $hash = password_hash($password_0, PASSWORD_DEFAULT);
    $db->query("INSERT INTO users (username, email, password, verification) VALUES ('$username', '$email', '$hash', '$verification')");

  //Sending verification email to users to confirm their email address.
    $to = $email;
    $subject = "Email Verification";
    $message = "<a href='https://localhost/project/thanks.php?verifcation=$verification'>Email Verification</a>";
    $headers = "From: krunalkamleshkumar10@gmail.com \r\n";
    $headers .= "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    mail($to, $subject, $message, $headers);
    $_SESSION['username'] = $username;
    $_SESSION['success'] = "";
    header('location:verification.php');
  }
}


  //loging the users and accessing the variable through POST array.
if(isset($_POST['login_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password_0']);

  //Adding validation to check if the username field is empty.
  if(empty($username)){
    array_push($errors, "Username is Required");
  }

  //Adding validation to check if the password field is empty.
  if(empty($password)){
    array_push($errors, "Password is Required");
  }

//Checking if the username and password the records in the database. If yes then promoting user to index page or else show error.
  $sql = $db->query("SELECT id, password FROM users WHERE username='$username'");
  if($sql->num_rows > 0) {
    $data = $sql->fetch_array();
    if(password_verify($password, $data['password'])) {
      $_SESSION['username'] = $username;
      $_SESSION['success'] = "";
      header('location:index.php');
    }
    else {
      array_push($errors, "You have entered wrong username/password. Please try again.");
    }
  }
}
?>
