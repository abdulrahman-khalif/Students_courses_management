<?php

//login.php


if(isset($_POST['login'])){
session_start();

include '../components/connect2.php';

$useremail=$_POST['email'];
$password=$_POST['password'];

$query=mysqli_query($conn,"select * from tutors where email='$useremail' && password='$password'");

if (mysqli_num_rows($query) > 0){
$row=mysqli_fetch_array($query);

$_SESSION['id']=$row['id'];
$_SESSION ['name'] = $row['name'];
$_SESSION ['profession'] = $row ['profession'];
$_SESSION['email'] = $row['email'];
$_SESSION['password'] = $row['password'];
$_SESSION ['image'] = $row['image'];

header('location: dashboard.php');

}else{
$_SESSION['message']="Login Failed. email or password are not correct!";
$_SESSION['id']="";
$_SESSION ['name'] = "";
$_SESSION ['profession'] = "";
$_SESSION['email'] = "";
$_SESSION['password'] = "";
$_SESSION ['image'] = "";

}
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" type="icon" href="assets/k_icon.png">
   <title>Login-admin</title>
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <!-- bootstrap link -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/style2.css">
</head>
<body>
   <header class="header">
      <section class="flex">
      <a href="dashboard.php" class="logo"><img src = "../assets/k_icon.png" width = "50" hight = "50" ></a>
         <div class="icons">
            <div id="user-btn" class="fas fa-user"></div>
         </div>
         <div class="profile">
            <h3 Style = "color: white">please login or register as:</h3>
         <div class="flex-btn">
            <a href="../login.php" class="option-btn">Student</a>
            <a href="login_admin.php" class="option-btn">Tutor</a>
         </div>
            </div>
         </div>
      </section>
   </header>



   <?php if (!empty($_SESSION['message'])):  ?>

   <div class="alert">
  <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
    <?= $_SESSION['message'] ?>
  
      </div>
      <?php endif; ?>



   <section class="form-container">
      <form action="" method="post" enctype="multipart/form-data" class="login">
         <fieldset>
            <h3>Login</h3>


            <h2 style = "color: white;">Welcome Tutor</h2>
            <p class="login_form">Your email <span>*</span></p>
            <input type="email" name="email" placeholder="enter your email" maxlength="50" required class="box">
            <p class="login_form">Your password <span>*</span></p>
            <input type="password" name="password" placeholder="enter your password" maxlength="30" required class="box">
            <p class="link">Don't have an account? <a href="register.php">Register now</a></p>
            <input type="submit" name="login" value="login now" class="btn">
         </fieldset>
      </form>
   </section>

   <!-- custom js file link  -->
   <script src="../js/script.js"></script>
</body>
</html>
