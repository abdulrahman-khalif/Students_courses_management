<?php

session_start();
include '../components/connect2.php';

if (!isset($_SESSION['id'])) {
   header('Location:login_admin.php');
   exit(); // Ensure the script stops after redirecting
}else{
    $tutor_id = $_SESSION['id'];    
}

if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location:contents.php');
}

if(isset($_POST['delete_video'])){

   $delete_id = $_POST['video_id'];

   $delete_video_thumb = "SELECT thumb FROM `content` WHERE id = '$delete_id' LIMIT 1";
   $delete_video_thumb_result = $conn->query($delete_video_thumb);
   $fetch_thumb = $delete_video_thumb_result->fetch_assoc();
   unlink('../uploaded_files/'.$fetch_thumb['thumb']);

   $delete_video = "SELECT video FROM `content` WHERE id = '$delete_id' LIMIT 1";
   $delete_video_result = $conn->query($delete_video);
   $fetch_video = $delete_video_result->fetch_assoc();
   unlink('../uploaded_files/'.$fetch_video['video']);

   $delete_likes = "DELETE FROM `likes` WHERE content_id = '$delete_id'";
   $delete_likes_result = $conn->query($delete_likes);
   $delete_comments = "DELETE FROM `comments` WHERE content_id = '$delete_id'";
   $delete_comments_result = $conn->query($delete_comments);

   $delete_content = "DELETE FROM `content` WHERE id = '$delete_id'";
   $delete_content_result = $conn->query($delete_content);
   header('location:contents.php');
    
}

if(isset($_POST['delete_comment'])){

   $delete_id = $_POST['comment_id'];

   $verify_comment = "SELECT * FROM `comments` WHERE id = '$delete_id'";
   $verify_comment_result = $conn->query($verify_comment);

   if($verify_comment_result->num_rows > 0){
      $delete_comment = "DELETE FROM `comments` WHERE id = '$delete_id'";
      $delete_comment_result = $conn->query($delete_comment);
      $message[] = 'comment deleted successfully!';
   }else{
      $message[] = 'comment already deleted!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>view content</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>


<section class="view-content">

   <?php
      $select_content = "SELECT * FROM `content` WHERE id = '$get_id' AND tutor_id = '$tutor_id'";
      $select_content_result = $conn->query($select_content);
      if($select_content_result->num_rows > 0){
         while($fetch_content = $select_content_result->fetch_assoc()){
            $video_id = $fetch_content['id'];

            $count_likes = "SELECT * FROM `likes` WHERE tutor_id = '$tutor_id' AND content_id = '$video_id'";
            $count_likes_result = $conn->query($count_likes);
            $total_likes = $count_likes_result->num_rows;

            $count_comments = "SELECT * FROM `comments` WHERE tutor_id = '$tutor_id' AND content_id = '$video_id'";
            $count_comments_result = $conn ->query($count_comments);
            $total_comments = $count_comments_result->num_rows;
   ?>
   <div class="container">
      <video src="../uploaded_files/<?= $fetch_content['video']; ?>" autoplay controls poster="../uploaded_files/<?= $fetch_content['thumb']; ?>" class="video" controls muted></video>
      <div class="date"><i class="fas fa-calendar"></i><span><?= $fetch_content['date']; ?></span></div>
      <h3 class="title"><?= $fetch_content['title']; ?></h3>
      <div class="flex">
         <div><i class="fas fa-heart"></i><span><?= $total_likes; ?></span></div>
         <div><i class="fas fa-comment"></i><span><?= $total_comments; ?></span></div>
      </div>
      <div class="description"><?= $fetch_content['description']; ?></div>
      <form action="" method="post">
         <div class="flex-btn">
            <input type="hidden" name="video_id" value="<?= $video_id; ?>">
            <a href="update_content.php?get_id=<?= $video_id; ?>" class="option-btn">update</a>
            <input type="submit" value="delete" class="delete-btn" onclick="return confirm('delete this video?');" name="delete_video">
         </div>
      </form>
   </div>
   <?php
    }
   }else{
      echo '<p class="empty">no contents added yet! <a href="add_content.php" class="btn" style="margin-top: 1.5rem;">add videos</a></p>';
   }
      
   ?>

</section>

<section class="comments">

   <h1 class="heading">user comments</h1>

   
   <div class="show-comments">
      <?php
         $select_comments = "SELECT * FROM `comments` WHERE content_id = '$get_id' ";
         $select_comments_result = $conn->query($select_comments);
         if($select_comments_result->num_rows > 0){
            while($fetch_comment = $select_comments_result->fetch_assoc()){   
             
               $select_commentor = "SELECT * FROM `users` WHERE id = '$fetch_comment[user_id]'";
               $select_commentor_result = $conn->query($select_commentor);
               $fetch_commentor = $select_commentor_result->fetch_assoc();
      ?>
      <div class="box">
         <div class="user">
            <img src="../uploaded_files/<?= $fetch_commentor['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_commentor['name']; ?></h3>
               <span><?= $fetch_comment['date']; ?></span>
            </div>
         </div>
         <p class="text"><?= $fetch_comment['comment']; ?></p>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="comment_id" value="<?= $fetch_comment['id']; ?>">
            <button type="submit" name="delete_comment" class="inline-delete-btn" onclick="return confirm('delete this comment?');">delete comment</button>
         </form>
      </div>
      <?php
       }
      }else{
         echo '<p class="empty">no comments added yet!</p>';
      }
      ?>
      </div>
   
</section>












<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>