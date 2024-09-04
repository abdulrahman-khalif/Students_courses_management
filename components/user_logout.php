//logout.php
<?php
    session_start();
$_SESSION['user_id']="";
$_SESSION ['name'] = "";
$_SESSION['email'] = "";
$_SESSION['password'] = "";
$_SESSION ['image'] = "";
session_destroy();
 
    header('location: ../login.php');
 
?>