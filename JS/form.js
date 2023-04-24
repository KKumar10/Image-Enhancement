//Initialising varibales and getting the elements from HTML for customising.
var loginId = document.getElementById('login');
var registerId = document.getElementById('register');
var formBtn = document.getElementById('btn');

//This line of code will show register form when clicked on register toggle button
function register() {
  loginId.style.left = "-4000px";
  registerId.style.left = "50px";
  formBtn.style.left = "110px";
}

//This line of code will show login form when clicked on login toggle button
function login() {
  loginId.style.left = "50px";
  registerId.style.left = "450px";
  formBtn.style.left = "0px";
}

//This line of code will show login form when clicked on Already a memeber button
function member() {
  loginId.style.left = "50px";
  registerId.style.left = "450px";
  formBtn.style.left = "0px";
}


//This line of code makes the hyperlinks bar black
//if the user scrolls down the page.
$(window).on("scroll", function() {
  if($(window).scrollTop()) {
    $('nav').addClass('black');
  }
  else {
    $('nav').removeClass('black');
  }
})

//This line of code allows the user to click on
//Navigation toggle button to show Hyperlinks.
$(document).ready(function() {
  $('#icon').click(function() {
    $('ul').toggleClass('show');
  });
});
