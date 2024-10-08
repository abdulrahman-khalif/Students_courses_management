<?php

session_start();
include 'components/connect2.php';

if (!isset($_SESSION['user_id'])) {
   header('Location:login.php');
   exit(); // Ensure the script stops after redirecting
}else{
    $user_id = $_SESSION['user_id'];    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>bookmarks</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="courses">

   <h1 class="heading">bookmarked playlists</h1>

   <div class="box-container">

      <?php
         $select_bookmark = "SELECT * FROM `bookmark` WHERE user_id = '$user_id'";
         $select_bookmark_result= $conn->query($select_bookmark);
         if($select_bookmark_result->num_rows > 0){
            while($fetch_bookmark = $select_bookmark_result->fetch_assoc()){
               $select_courses = "SELECT * FROM `playlist` WHERE id = '$fetch_bookmark[playlist_id]' AND status = 'active' ORDER BY date DESC";
               $select_courses_result = $conn->query($select_courses);
               if($select_courses_result->num_rows > 0){
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
               echo '<p class="empty">no courses found!</p>';
            }
         }
      }else{
         echo '<p class="empty">nothing bookmarked yet!</p>';
      }
      ?>

   </div>

</section>










<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>