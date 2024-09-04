//logout.php
<?php
    session_start();
$_SESSION['id']="";
$_SESSION ['name'] = "";
$_SESSION ['profession'] = "";
$_SESSION['email'] = "";
$_SESSION['password'] = "";
$_SESSION ['image'] = "";
session_destroy();
 
    header('location: ../admin/login_admin.php');
 
?>