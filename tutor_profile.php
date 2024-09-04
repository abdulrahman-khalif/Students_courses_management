<?php

session_start();
include 'components/connect2.php';

if (!isset($_SESSION['user_id'])) {
   header('Location:login.php');
   exit(); // Ensure the script stops after redirecting
}else{
    $user_id = $_SESSION['user_id'];    
}


if(isset($_POST['tutor_fetch'])){

   $tutor_email = $_POST['tutor_email'];
   $tutor_email = filter_var($tutor_email, FILTER_SANITIZE_STRING);
   $select_tutor = "SELECT * FROM `tutors` WHERE email = '$tutor_email'";
   $select_tutor_result = $conn->query($select_tutor);

   $fetch_tutor = $select_tutor_result->fetch_assoc();
   $tutor_id = $fetch_tutor['id'];

   $count_playlists = "SELECT * FROM `playlist` WHERE tutor_id = '$tutor_id'";
   $count_playlists_result = $conn->query($count_playlists);
   $total_playlists = $count_playlists_result->num_rows;

   $count_contents = "SELECT * FROM `content` WHERE tutor_id = '$tutor_id'";
   $count_contents_result = $conn->query($count_contents);
   $total_contents = $count_contents_result->num_rows;

   $count_likes = "SELECT * FROM `likes` WHERE tutor_id = '$tutor_id'";
   $count_likes_result = $conn->query($count_likes);
   $total_likes = $count_likes_result->num_rows;

   $count_comments = "SELECT * FROM `comments` WHERE tutor_id = '$tutor_id' ";
   $count_comments_result = $conn->query($count_comments);
   $total_comments = $count_comments_result->num_rows;

}else{
   header('location:teachers.php');
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>tutor's profile</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- teachers profile section starts  -->

<section class="tutor-profile">

   <h1 class="heading">profile details</h1>

   <div class="details">
      <div class="tutor">
         <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
         <h3><?= $fetch_tutor['name']; ?></h3>
         <span><?= $fetch_tutor['profession']; ?></span>
      </div>
      <div class="flex">
         <p>total playlists : <span><?= $total_playlists; ?></span></p>
         <p>total videos : <span><?= $total_contents; ?></span></p>
         <p>total likes : <span><?= $total_likes; ?></span></p>
         <p>total comments : <span><?= $total_comments; ?></span></p>
      </div>
   </div>

</section>

<!-- teachers profile section ends -->

<section class="courses">

   <h1 class="heading">latest courese</h1>

   <div class="box-container">

      <?php
         $select_courses = "SELECT * FROM `playlist` WHERE tutor_id = '$tutor_id' AND status = 'active'";
         $select_courses_result = $conn->query($select_courses);
         if($select_courses_result->num_rows> 0){
            while($fetch_course = $select_courses_result->fetch_assoc()){
               $course_id = $fetch_course['id'];

               $select_tutor = "SELECT * FROM `tutors` WHERE id = '$fetch_course[tutor_id]'";
               $select_tutor_result = $conn->query($select_tutor);
               $fetch_tutor = $select_tutor_result->fetch_assoc();
      ?>
      <div class="box">
         <div class="tutor">
            <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_tutor['name']; ?></h3>
               <span><?= $fetch_course['date']; ?></span>
            </div>
         </div>
         <img src="uploaded_files/<?= $fetch_course['thumb']; ?>" class="thumb" alt="">
         <h3 class="title"><?= $fetch_course['title']; ?></h3>
         <a href="playlist.php?get_id=<?= $course_id; ?>" class="inline-btn">view playlist</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no courses added yet!</p>';
      }
      ?>

   </div>

</section>

<!-- courses section ends -->










<?php include 'components/footer.php'; ?>    

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>