<?php

session_start();
include '../components/connect2.php';

if (!isset($_SESSION['id'])) {
   header('Location:login_admin.php');
   exit(); // Ensure the script stops after redirecting
}else{
    $tutor_id = $_SESSION['id'];
    $email = $_SESSION['email'];
    $name = $_SESSION['name'];
    $image = $_SESSION['image'];
    
}


// Get the total number of palylist 

$sql_playlist = "select * from playlist where tutor_id ='$tutor_id'";
	$result = $conn->query($sql_playlist);
	
	if ($result->num_rows > 0) {
		$i = 0;
		while ($row = $result->fetch_assoc()) {
        
            $i += 1; 
         }

         $total_playlists = $i;
         }else{
            $total_playlists = 0;
         }


         
// Get the total number of comments 


$sql_playlist = "select * from comments where tutor_id ='$tutor_id'";
$result = $conn->query($sql_playlist);

if ($result->num_rows > 0) {
   $i = 0;
   while ($row = $result->fetch_assoc()) {
     
         $i += 1; 
      }

      $total_comments = $i;
      }else{
         $total_comments = 0;
      }

// Get the total number of content 

$sql_playlist = "select * from comments where tutor_id ='$tutor_id'";
$result = $conn->query($sql_playlist);

if ($result->num_rows > 0) {
   $i = 0;
   while ($row = $result->fetch_assoc()) {
     
         $i += 1; 
      }

      $total_contents = $i;
      }else{
         $total_contents = 0;
      }

// Get the total number of likes 

$sql_playlist = "select * from likes where tutor_id ='$tutor_id'";
$result = $conn->query($sql_playlist);

if ($result->num_rows > 0) {
   $i = 0;
   while ($row = $result->fetch_assoc()) {
     
         $i += 1; 
      }

      $total_likes = $i;
      }else{
         $total_likes = 0;
      }


// Get the total number of contents 

$sql_playlist = "select * from content where tutor_id ='$tutor_id'";
$result = $conn->query($sql_playlist);

if ($result->num_rows > 0) {
   $i = 0;
   while ($row = $result->fetch_assoc()) {
     
         $i += 1; 
      }

      $total_contents = $i;
      }else{
         $total_contents = 0;
      }
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>


<section class="dashboard">

   <h1 class="heading">dashboard</h1>

   <div class="box-container">

      <div class="box">
         <h3>welcome!</h3>
         <p><?= $name; ?></p>
         <a href="profile.php" class="btn">view profile</a>
      </div>

      <div class="box">
         <h3><?= $total_contents; ?></h3>
         <p>total contents</p>
         <a href="add_content.php" class="btn">add new content</a>
      </div>

      <div class="box">
         <h3><?= $total_playlists; ?></h3>
         <p>total playlists</p>
         <a href="add_playlist.php" class="btn">add new playlist</a>
      </div>

      <div class="box">
         <h3><?= $total_likes; ?></h3>
         <p>total likes</p>
         <a href="contents.php" class="btn">view contents</a>
      </div>

      <div class="box">
         <h3><?= $total_comments; ?></h3>
         <p>total comments</p>
         <a href="comments.php" class="btn">view comments</a>
      </div>

      <div class="box">
         <h3>quick select</h3>
         <p>login or register</p>
         <div class="flex-btn">
            <a href="login.php" class="option-btn">login</a>
            <a href="register.php" class="option-btn">register</a>
         </div>
      </div>

   </div>

</section>


<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>



