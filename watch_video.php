<?php

session_start();
include 'components/connect2.php';

if (!isset($_SESSION['user_id'])) {
   header('Location:login.php');
   exit(); // Ensure the script stops after redirecting
}else{
    $user_id = $_SESSION['user_id'];    
}

if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location:home.php');
}

if(isset($_POST['like_content'])){

   if($user_id != ''){

      $content_id = $_POST['content_id'];

      $select_content = "SELECT * FROM `content` WHERE id = '$content_id' LIMIT 1";
      $select_content_result = $conn->query($select_content);
      $fetch_content = $select_content_result->fetch_assoc();

      $tutor_id = $fetch_content['tutor_id'];

      $select_likes = "SELECT * FROM `likes` WHERE user_id = '$user_id' AND content_id = '$content_id'";
      $select_likes_result = $conn->query($select_likes);

      if($select_likes_result->num_rows > 0){
         $remove_likes = "DELETE FROM `likes` WHERE user_id = '$user_id' AND content_id = '$content_id'";
         $remove_likes_result = $conn->query($remove_likes);
         $message[] = 'removed from likes!';
      }else{
         $insert_likes = "INSERT INTO `likes`(user_id, tutor_id, content_id) VALUES('$user_id','$tutor_id','$content_id')";
         $insert_likes_result = $conn->query($insert_likes);
         $message[] = 'added to likes!';
      }

   }else{
      $message[] = 'please login first!';
   }

}

if(isset($_POST['add_comment'])){

   if($user_id != ''){

      $id = unique_id();
      $comment_box = $_POST['comment_box'];
      $content_id = $_POST['content_id'];

      $select_content = "SELECT * FROM `content` WHERE id = '$content_id' LIMIT 1";
      $select_content_result = $conn->query($select_content);
      $fetch_content = $select_content_result->fetch_assoc();

      $tutor_id = $fetch_content['tutor_id'];

      if($select_content_result->num_rows > 0){

         $select_comment = "SELECT * FROM `comments` WHERE content_id = '$content_id' AND user_id = '$user_id' AND tutor_id = '$tutor_id' AND comment = '$comment_box'";
         $select_comment_result = $conn->query($select_comment);

         if($select_comment_result->num_rows > 0){
            $message[] = 'comment already added!';
         }else{
            $insert_comment = "INSERT INTO `comments`(id, content_id, user_id, tutor_id, comment) VALUES('$id','$content_id','$user_id','$tutor_id','$comment_box')";
            $insert_comment_result = $conn->query($insert_comment);
            $message[] = 'new comment added!';
         }

      }else{
         $message[] = 'something went wrong!';
      }

   }else{
      $message[] = 'please login first!';
   }

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

if(isset($_POST['update_now'])){

   $update_id = $_POST['update_id'];
   $update_box = $_POST['update_box'];

   $verify_comment = "SELECT * FROM `comments` WHERE id = '$user_id' AND comment = '$update_box'";
   $verify_comment_result = $conn->query($verify_comment);

   if($verify_comment_result->num_rows > 0){
      $message[] = 'comment already added!';
   }else{
      $update_comment = "UPDATE `comments` SET comment = '$update_box' WHERE id = '$update_id'";
      $update_comment_result = $conn->query($update_comment);
      $message[] = 'comment edited successfully!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>watch video</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<?php
   if(isset($_POST['edit_comment'])){
      $edit_id = $_POST['comment_id'];
      $verify_comment = "SELECT * FROM `comments` WHERE id = '$edit_id' LIMIT 1";
      $verify_comment_result = $conn->query($verify_comment);
      if($verify_comment_result->num_rows > 0){
         $fetch_edit_comment = $verify_comment_result->fetch_assoc();
?>
<section class="edit-comment">
   <h1 class="heading">edti comment</h1>
   <form action="" method="post">
      <input type="hidden" name="update_id" value="<?= $fetch_edit_comment['id']; ?>">
      <textarea name="update_box" class="box" maxlength="1000" required placeholder="please enter your comment" cols="30" rows="10"><?= $fetch_edit_comment['comment']; ?></textarea>
      <div class="flex">
         <a href="watch_video.php?get_id=<?= $get_id; ?>" class="inline-option-btn">cancel edit</a>
         <input type="submit" value="update now" name="update_now" class="inline-btn">
      </div>
   </form>
</section>
<?php
   }else{
      $message[] = 'comment was not found!';
   }
}
?>

<!-- watch video section starts  -->

<section class="watch-video">

   <?php
      $select_content = "SELECT * FROM `content` WHERE id = '$get_id' AND status = 'active'";
      $select_content_result = $conn->query($select_content);
      if($select_content_result->num_rows > 0){
         while($fetch_content = $select_content_result->fetch_assoc()){
            $content_id = $fetch_content['id'];

            $select_likes = "SELECT * FROM `likes` WHERE content_id = '$content_id'";
            $select_likes_result = $conn->query($select_likes);
            $total_likes = $select_likes_result->num_rows;  

            $verify_likes = "SELECT * FROM `likes` WHERE user_id = '$user_id' AND content_id = '$content_id'";
            $verify_likes_result = $conn->query($verify_likes);

            $select_tutor = "SELECT * FROM `tutors` WHERE id = '$fetch_content[tutor_id]' LIMIT 1";
            $select_tutor_result = $conn->query($select_tutor);
            $fetch_tutor = $select_tutor_result->fetch_assoc();
   ?>
   <div class="video-details">
      <video src="uploaded_files/<?= $fetch_content['video']; ?>" class="video" poster="uploaded_files/<?= $fetch_content['thumb']; ?>" controls autoplay></video>
      <h3 class="title"><?= $fetch_content['title']; ?></h3>
      <div class="info">
         <p><i class="fas fa-calendar"></i><span><?= $fetch_content['date']; ?></span></p>
         <p><i class="fas fa-heart"></i><span><?= $total_likes; ?> likes</span></p>
      </div>
      <div class="tutor">
         <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
         <div>
            <h3><?= $fetch_tutor['name']; ?></h3>
            <span><?= $fetch_tutor['profession']; ?></span>
         </div>
      </div>
      <form action="" method="post" class="flex">
         <input type="hidden" name="content_id" value="<?= $content_id; ?>">
         <a href="playlist.php?get_id=<?= $fetch_content['playlist_id']; ?>" class="inline-btn">view playlist</a>
         <?php
            if($verify_likes_result->num_rows > 0){
         ?>
         <button type="submit" name="like_content"><i class="fas fa-heart"></i><span>liked</span></button>
         <?php
         }else{
         ?>
         <button type="submit" name="like_content"><i class="far fa-heart"></i><span>like</span></button>
         <?php
            }
         ?>
      </form>
      <div class="description"><p><?= $fetch_content['description']; ?></p></div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">no videos added yet!</p>';
      }
   ?>

</section>

<!-- watch video section ends -->

<!-- comments section starts  -->

<section class="comments">

   <h1 class="heading">add a comment</h1>

   <form action="" method="post" class="add-comment">
      <input type="hidden" name="content_id" value="<?= $get_id; ?>">
      <textarea name="comment_box" required placeholder="write your comment..." maxlength="1000" cols="30" rows="10"></textarea>
      <input type="submit" value="add comment" name="add_comment" class="inline-btn">
   </form>

   <h1 class="heading">user comments</h1>

   
   <div class="show-comments">
      <?php
         $select_comments = "SELECT * FROM `comments` WHERE content_id = '$get_id'";
         $select_comments_result = $conn->query($select_comments);
         if($select_comments_result->num_rows > 0){
            while($fetch_comment = $select_comments_result->fetch_assoc()){   
               $select_commentor = "SELECT * FROM `users` WHERE id = '$fetch_comment[user_id]'";
               $select_commentor_result = $conn->query($select_commentor);
               $fetch_commentor = $select_commentor_result->fetch_assoc();
      ?>
      <div class="box" style="<?php if($fetch_comment['user_id'] == $user_id){echo 'order:-1;';} ?>">
         <div class="user">
            <img src="uploaded_files/<?= $fetch_commentor['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_commentor['name']; ?></h3>
               <span><?= $fetch_comment['date']; ?></span>
            </div>
         </div>
         <p class="text"><?= $fetch_comment['comment']; ?></p>
         <?php
            if($fetch_comment['user_id'] == $user_id){ 
         ?>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="comment_id" value="<?= $fetch_comment['id']; ?>">
            <button type="submit" name="edit_comment" class="inline-option-btn">edit comment</button>
            <button type="submit" name="delete_comment" class="inline-delete-btn" onclick="return confirm('delete this comment?');">delete comment</button>
         </form>
         <?php
         }
         ?>
      </div>
      <?php
       }
      }else{
         echo '<p class="empty">no comments added yet!</p>';
      }
      ?>
      </div>
   
</section>

<!-- comments section ends -->








<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>