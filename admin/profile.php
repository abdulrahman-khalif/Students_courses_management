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
   <title>Profile</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>




<?php include '../components/admin_header.php'; ?>



<section class="tutor-profile" style="min-height: calc(100vh - 19rem);"> 
<?php
          $query=mysqli_query($conn,"select * from tutors where id ='$tutor_id' && email='$email'");

          if (mysqli_num_rows($query) > 0){
          $row=mysqli_fetch_array($query);
          
          
          $name = $row['name'];
          $profession = $row ['profession'];
          $email  = $row['email'];
          $image = $row['image'];
          }
          
      ?>

   <h1 class="heading">profile details</h1>

   <div class="details">
      <div class="tutor">
         <img src="../uploaded_files/<?= $image; ?>" alt="">
         <h3><?= $name; ?></h3>
         <span><?=  $profession?></span>
         <a href="update.php" class="inline-btn">update profile</a>
      </div>
      <div class="flex">
         <div class="box">
            <span><?= $total_playlists; ?></span>
            <p>total playlists</p>
            <a href="playlists.php" class="btn">view playlists</a>
         </div>
         <div class="box">
            <span><?= $total_contents; ?></span>
            <p>total videos</p>
            <a href="contents.php" class="btn">view contents</a>
         </div>
         <div class="box">
            <span><?= $total_likes; ?></span>
            <p>total likes</p>
            <a href="contents.php" class="btn">view contents</a>
         </div>
         <div class="box">
            <span><?= $total_comments; ?></span>
            <p>total comments</p>
            <a href="comments.php" class="btn">view comments</a>
         </div>
      </div>
   </div>

</section>















<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>