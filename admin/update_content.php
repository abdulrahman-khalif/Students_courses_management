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
} else { 
   $get_id = '';
   header('location:dashboard.php');
}

if(isset($_POST['update'])){

   $video_id = $_POST['video_id'];
  
   $status = $_POST['status'];
   $title = $_POST['title'];
  
   $description = $_POST['description'];

   $playlist = $_POST['playlist'];


   $update_content = "UPDATE `content` SET title = '$title', description = '$description', status = '$status' WHERE id = '$video_id'";
   $update_content_result = $conn ->query($update_content);

   if(!empty($playlist)){
      $update_playlist = "UPDATE `content` SET playlist_id = '$playlist' WHERE id = '$video_id'";
      $update_playlist_result = $conn->query($update_playlist);

   }

   $old_thumb = $_POST['old_thumb'];
   $thumb = $_FILES['thumb']['name'];
   $thumb_ext = pathinfo($thumb, PATHINFO_EXTENSION);
   $rename_thumb = unique_id().'.'.$thumb_ext;
   $thumb_size = $_FILES['thumb']['size'];
   $thumb_tmp_name = $_FILES['thumb']['tmp_name'];
   $thumb_folder = '../uploaded_files/'.$rename_thumb;

   if(!empty($thumb)){
      if($thumb_size > 2000000){
         $message[] = 'image size is too large!';
      }else{
         $update_thumb = "UPDATE `content` SET thumb = '$rename_thumb' WHERE id = '$video_id'";
         $update_thumb_result = $conn->query($update_thumb);
         move_uploaded_file($thumb_tmp_name, $thumb_folder);
         if($old_thumb != '' AND $old_thumb != $rename_thumb){
            unlink('../uploaded_files/'.$old_thumb);
         }
      }
   }

   $old_video = $_POST['old_video'];
   $video = $_FILES['video']['name'];
   $video_ext = pathinfo($video, PATHINFO_EXTENSION);
   $rename_video = unique_id().'.'.$video_ext;
   $video_tmp_name = $_FILES['video']['tmp_name'];
   $video_folder = '../uploaded_files/'.$rename_video;

   if(!empty($video)){
      $update_video = "UPDATE `content` SET video = '$rename_video' WHERE id = '$video_id'";
      $update_video_result = $conn->query($update_video);
      move_uploaded_file($video_tmp_name, $video_folder);
      if($old_video != '' AND $old_video != $rename_video){
         unlink('../uploaded_files/'.$old_video);
      }
   }

   $message[] = 'content updated!';

}

if(isset($_POST['delete_video'])){

   $delete_id = $_POST['video_id'];

   $delete_video_thumb = "SELECT thumb FROM `content` WHERE id = '$delete_id' LIMIT 1";
   $delete_video_thumb_result = $conn->query($delete_video_thumb);
   $fetch_thumb = $delete_video_thumb_result->fetch_assoc();
   unlink('../uploaded_files/'.$fetch_thumb['thumb']);

   $delete_video = "SELECT video FROM `content` WHERE id = '$delete_id' LIMIT 1";
   $delete_video_result = $conn->query($delete_video);
   $fetch_video = $delete_video_result = $result->fetch_assoc();
   unlink('../uploaded_files/'.$fetch_video['video']);

   $delete_likes = "DELETE FROM `likes` WHERE content_id = '$delete_id'";
   $delete_likes_result = $conn->query($delete_likes);
   $delete_comments = "DELETE FROM `comments` WHERE content_id = '$delete_id'";
   $delete_comments_result = $conn ->query($delete_comments);

   $delete_content = "DELETE FROM `content` WHERE id = '$delete_id'";
   $delete_content->$conn->query($delete_content);
   header('location:contents.php');
    
}
if (!empty($message)){
header('location:playlists.php');
}

?>







<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update video</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="video-form">

   <h1 class="heading">update content</h1>

   <?php
      $select_videos = "SELECT * FROM `content` WHERE id = '$get_id' AND tutor_id = '$tutor_id'";
      $select_videos_result = $conn->query($select_videos);
      if ($select_videos_result->num_rows > 0) {
        while ($fetch_video = $select_videos_result->fetch_assoc()) {
            $video_id = $fetch_video['id'];
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="video_id" value="<?= $fetch_video['id']; ?>">
      <input type="hidden" name="old_thumb" value="<?= $fetch_video['thumb']; ?>">
      <input type="hidden" name="old_video" value="<?= $fetch_video['video']; ?>">
      <p>update status <span>*</span></p>
      <select name="status" class="box" required>
         <option value="<?= $fetch_video['status']; ?>" selected><?= $fetch_video['status']; ?></option>
         <option value="active">active</option>
         <option value="deactive">deactive</option>
      </select>
      <p>update title <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="enter video title" class="box" value="<?= $fetch_video['title']; ?>">
      <p>update description <span>*</span></p>
      <textarea name="description" class="box" required placeholder="write description" maxlength="1000" cols="30" rows="10"><?= $fetch_video['description']; ?></textarea>
      <p>update playlist</p>
      <select name="playlist" class="box">
         <option value="<?= $fetch_video['playlist_id']; ?>" selected>--select playlist</option>
         <?php
         $select_playlists = "SELECT * FROM `playlist` WHERE tutor_id = '$tutor_id'";
         $select_playlists_result = $conn ->query($select_playlists);

           if ($select_playlists_result->num_rows > 0) {

        while ($fetch_playlist = $select_playlists_result->fetch_assoc()) {
         ?>
         <option value="<?= $fetch_playlist['id']; ?>"><?= $fetch_playlist['title']; ?></option>
         <?php
            }
         ?>
         <?php
         }else{
            echo '<option value="" disabled>no playlist created yet!</option>';
         }
         ?>
      </select>
      <img src="../uploaded_files/<?= $fetch_video['thumb']; ?>" alt="">
      <p>update thumbnail</p>
      <input type="file" name="thumb" accept="image/*" class="box">
      <video src="../uploaded_files/<?= $fetch_video['video']; ?>" controls></video>
      <p>update video</p>
      <input type="file" name="video" accept="video/*" class="box">
      <input type="submit" value="update content" name="update" class="btn">
      <div class="flex-btn">
         <a href="view_content.php?get_id=<?= $video_id; ?>" class="option-btn">view content</a>
         <input type="submit" value="delete content" name="delete_video" class="delete-btn">
      </div>
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">video not found! <a href="add_content.php" class="btn" style="margin-top: 1.5rem;">add videos</a></p>';
      }
   ?>

</section>















<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>