<?php

session_start();
include 'components/connect2.php';

if (!isset($_SESSION['user_id'])) {
   header('Location:login.php');
   exit(); // Ensure the script stops after redirecting
}else{
    $user_id = $_SESSION['user_id'];    
}

if(isset($_POST['remove'])){

   if($user_id != ''){
      $content_id = $_POST['content_id'];

      $verify_likes = "SELECT * FROM `likes` WHERE user_id = '$user_id' AND content_id = '$content_id'";
      $verify_likes_result  = $conn->query($verify_likes); 

      if($verify_likes_result->num_rows > 0){
         $remove_likes = "DELETE FROM `likes` WHERE user_id = '$user_id' AND content_id = '$content_id'";
         $remove_likes_result = $conn->query($remove_likes);
         $message[] = 'removed from likes!';
      }
   }else{
      $message[] = 'please login first!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>liked videos</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- courses section starts  -->

<section class="liked-videos">

   <h1 class="heading">liked videos</h1>

   <div class="box-container">

   <?php
      $select_likes = "SELECT * FROM `likes` WHERE user_id = '$user_id'";
      $select_likes_result = $conn->query($select_likes);
      if($select_likes_result->num_rows > 0){
         while($fetch_likes = $select_likes_result->fetch_assoc()){

            $select_contents = "SELECT * FROM `content` WHERE id = '$fetch_likes[content_id]' ORDER BY date DESC";
            $select_contents_result = $conn->query($select_contents);

            if($select_contents_result->num_rows > 0){
               while($fetch_contents = $select_contents_result->fetch_assoc()){

               $select_tutors = "SELECT * FROM `tutors` WHERE id = '$fetch_contents[tutor_id]'";
               $select_tutors_result = $conn->query($select_tutors);
               $fetch_tutor = $select_tutors_result->fetch_assoc();
   ?>
   <div class="box">
      <div class="tutor">
         <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
         <div>
            <h3><?= $fetch_tutor['name']; ?></h3>
            <span><?= $fetch_contents['date']; ?></span>
         </div>
      </div>
      <img src="uploaded_files/<?= $fetch_contents['thumb']; ?>" alt="" class="thumb">
      <h3 class="title"><?= $fetch_contents['title']; ?></h3>
      <form action="" method="post" class="flex-btn">
         <input type="hidden" name="content_id" value="<?= $fetch_contents['id']; ?>">
         <a href="watch_video.php?get_id=<?= $fetch_contents['id']; ?>" class="inline-btn">watch video</a>
         <input type="submit" value="remove" class="inline-delete-btn" name="remove">
      </form>
   </div>
   <?php
            }
         }else{
            echo '<p class="emtpy">content was not found!</p>';         
         }
      }
   }else{
      echo '<p class="empty">nothing added to likes yet!</p>';
   }
   ?>

   </div>

</section>










<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>