<?php

session_start();
include 'components/connect2.php';

if (!isset($_SESSION['user_id'])) {
   header('Location:login.php');
   exit(); // Ensure the script stops after redirecting
}else{
    $user_id = $_SESSION['user_id'];    
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

   $verify_comment = "SELECT * FROM `comments` WHERE id = '$update_id' AND comment = '$update_box' ORDER BY date DESC";
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
   <title>user comments</title>

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
         <a href="comments.php" class="inline-option-btn">cancel edit</a>
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

<section class="comments">

   <h1 class="heading">your comments</h1>

   
   <div class="show-comments">
      <?php
         $select_comments = "SELECT * FROM `comments` WHERE user_id = '$user_id'";
         $select_comments_result = $conn->query($select_comments);
         if($select_comments_result->num_rows > 0){
            while($fetch_comment = $select_comments_result->fetch_assoc()){
               $select_content = "SELECT * FROM `content` WHERE id = '$fetch_comment[content_id]'";
               $select_content_result = $conn->query($select_content);
               $fetch_content = $select_content_result->fetch_assoc();
      ?>
      <div class="box" style="<?php if($fetch_comment['user_id'] == $user_id){echo 'order:-1;';} ?>">
         <div class="content"><span><?= $fetch_comment['date']; ?></span><p> - <?= $fetch_content['title']; ?> - </p><a href="watch_video.php?get_id=<?= $fetch_content['id']; ?>">view content</a></div>
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