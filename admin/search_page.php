<?php

session_start();
include '../components/connect2.php';

if (!isset($_SESSION['id'])) {
   header('Location:login_admin.php');
   exit(); // Ensure the script stops after redirecting
}else{
    $tutor_id = $_SESSION['id'];

    }

if(isset($_POST['delete_video'])){
   $delete_id = $_POST['video_id'];
   $verify_video = "SELECT * FROM `content` WHERE id = '$delete_id' LIMIT 1";
   $verify_video_result = $conn->query($verify_video);

   if($verify_video->num_rows > 0){
      $delete_video_thumb = "SELECT * FROM `content` WHERE id = '$delete_id' LIMIT 1";
      $delete_video_thumb_result = $conn->query($delete_id);
      $fetch_thumb = $delete_video_thumb_result->fetch_assoc();
      unlink('../uploaded_files/'.$fetch_thumb['thumb']);
      $delete_video = "SELECT * FROM `content` WHERE id = '$delete_id' LIMIT 1";
      $delete_video_result = $conn->query($delete_video);
      $fetch_video = $delete_video_result->fetch_assoc();
      unlink('../uploaded_files/'.$fetch_video['video']);
      $delete_likes = "DELETE FROM `likes` WHERE content_id = '$delete_id')";
      $delete_likes_result = $conn->query($delete_likes);
      $delete_comments = "DELETE FROM `comments` WHERE content_id = '$delete_id'";
      $delete_comments_result = $conn->query($delete_comments);
      $delete_content = "DELETE FROM `content` WHERE id = '$delete_id'";
      $delete_content_result = $conn->query($delete_content);
      $message[] = 'video deleted!';
   }else{
      $message[] = 'video already deleted!';
   }

}

if(isset($_POST['delete_playlist'])){
   $delete_id = $_POST['playlist_id'];

   $verify_playlist = "SELECT * FROM `playlist` WHERE id = '$delete_id' AND tutor_id = '$tutor_id' LIMIT 1";
   $verify_playlist_result = $conn->query($verify_playlist);

   if($verify_playlist_result->num_rows > 0){

   

   $delete_playlist_thumb = "SELECT * FROM `playlist` WHERE id = '$delete_id' LIMIT 1";
   $delete_playlist_thumb_result = $conn->query($delete_playlist_thumb);
   $fetch_thumb = $delete_playlist_thumb_result->fetch_assoc();
   unlink('../uploaded_files/'.$fetch_thumb['thumb']);
   $delete_bookmark = "DELETE FROM `bookmark` WHERE playlist_id = '$delete_id'";
   $delete_bookmark_result = $conn->query($delete_bookmark);
   $delete_playlist = "DELETE FROM `playlist` WHERE id = '$delete_id'";
   $delete_playlist_result = $conn->query($delete_playlist);
   $message[] = 'playlist deleted!';
   }else{
      $message[] = 'playlist already deleted!';
   }
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
   
<section class="contents">

   <h1 class="heading">contents</h1>

   <div class="box-container">

   <?php
      if(isset($_POST['search']) or isset($_POST['search_btn'])){
      $search = $_POST['search'];
      $select_videos = "SELECT * FROM `content` WHERE title LIKE '%{$search}%' AND tutor_id = '$tutor_id' ORDER BY date DESC";
      $select_videos_result = $conn->query($select_videos);
      if($select_videos_result->num_rows > 0){
         while($fecth_videos = $select_videos_result->fetch_assoc()){ 
            $video_id = $fecth_videos['id'];
   ?>
      <div class="box">
         <div class="flex">
            <div><i class="fas fa-dot-circle" style="<?php if($fecth_videos['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"></i><span style="<?php if($fecth_videos['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"><?= $fecth_videos['status']; ?></span></div>
            <div><i class="fas fa-calendar"></i><span><?= $fecth_videos['date']; ?></span></div>
         </div>
         <img src="../uploaded_files/<?= $fecth_videos['thumb']; ?>" class="thumb" alt="">
         <h3 class="title"><?= $fecth_videos['title']; ?></h3>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="video_id" value="<?= $video_id; ?>">
            <a href="update_content.php?get_id=<?= $video_id; ?>" class="option-btn">update</a>
            <input type="submit" value="delete" class="delete-btn" onclick="return confirm('delete this video?');" name="delete_video">
         </form>
         <a href="view_content.php?get_id=<?= $video_id; ?>" class="btn">view content</a>
      </div>
   <?php
         }
      }else{
         echo '<p class="empty">no contents founds!</p>';
      }
   }else{
      echo '<p class="empty">please search something!</p>';
   }
   ?>

   </div>

</section>

<section class="playlists">

   <h1 class="heading">playlists</h1>

   <div class="box-container">
   
      <?php
      if(isset($_POST['search']) or isset($_POST['search_btn'])){
         $search = $_POST['search'];
         $select_playlist = "SELECT * FROM `playlist` WHERE title LIKE '%{$search}%' AND tutor_id = '$tutor_id' ORDER BY date DESC";
         $select_playlist_result = $conn->query($select_playlist);
         if($select_playlist_result->num_rows > 0){
         while($fetch_playlist = $select_playlist_result->fetch_assoc()){
            
            $count_videos = "SELECT * FROM `content` WHERE playlist_id = '$fetch_playlist[id]'";
            $count_videos_result = $conn->query($count_videos);
            $total_videos = $count_videos_result->num_rows;
      ?>
      <div class="box">
         <div class="flex">
            <div><i class="fas fa-circle-dot" style="<?php if($fetch_playlist['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"></i><span style="<?php if($fetch_playlist['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"><?= $fetch_playlist['status']; ?></span></div>
            <div><i class="fas fa-calendar"></i><span><?= $fetch_playlist['date']; ?></span></div>
         </div>
         <div class="thumb">
            <span><?= $total_videos; ?></span>
            <img src="../uploaded_files/<?= $fetch_playlist['thumb']; ?>" alt="">
         </div>
         <h3 class="title"><?= $fetch_playlist['title']; ?></h3>
         <p class="description"><?= $fetch_playlist['description']; ?></p>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="playlist_id" value="<?= $playlist_id; ?>">
            <a href="update_playlist.php?get_id=<?= $playlist_id; ?>" class="option-btn">update</a>
            <input type="submit" value="delete_playlist" class="delete-btn" onclick="return confirm('delete this playlist?');" name="delete">
         </form>
         <a href="view_playlist.php?get_id=<?= $playlist_id; ?>" class="btn">view playlist</a>
      </div>
      <?php
         } 
      }else{
         echo '<p class="empty">no playlists found!</p>';
      }}else{
         echo '<p class="empty">please search something!</p>';
      }
      ?>

   </div>

</section>















<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

<script>
   document.querySelectorAll('.playlists .box-container .box .description').forEach(content => {
      if(content.innerHTML.length > 100) content.innerHTML = content.innerHTML.slice(0, 100);
   });
</script>

</body>
</html>