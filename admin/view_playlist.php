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

if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location:playlist.php');
}

if(isset($_POST['delete_playlist'])){
   $delete_id = $_POST['playlist_id'];
   
   $delete_playlist_thumb = "SELECT * FROM `playlist` WHERE id = $delete_id LIMIT 1";
   $result = $conn->query($delete_playlist_thumb);

   $fetch_thumb = $result->fetch_assoc();
   unlink('../uploaded_files/'.$fetch_thumb['thumb']);
   $delete_bookmark = "DELETE FROM `bookmark` WHERE playlist_id = '$delete_id'";
   $result = $conn->query($delete_bookmark);
   $delete_playlist = "DELETE FROM `playlist` WHERE id = '$delete_id'";
   $result = $conn->query($delete_playlist);
   header('locatin:playlists.php');
}

if(isset($_POST['delete_video'])){
   $delete_id = $_POST['video_id'];
   $verify_video = "SELECT * FROM `content` WHERE id = '$delete_id' LIMIT 1";
   $result = $conn->query($verify_video);

   if($result->num_rows > 0){
      $delete_video_thumb = "SELECT * FROM `content` WHERE id = '$delete_id' LIMIT 1";
      $result = $conn->query($delete_video_thumb);
      $fetch_thumb = $result->fetch_assoc();
      unlink('../uploaded_files/'.$fetch_thumb['thumb']);
      $delete_video = "SELECT * FROM `content` WHERE id = '$delete_id' LIMIT 1";
      $result = $conn->query($delete_video);
      
      if (file_exists('../uploaded_files/'.$fetch_thumb['thumb'])) {
         unlink('../uploaded_files/'.$fetch_thumb['thumb']);
     }
     
      $delete_likes = "DELETE FROM `likes` WHERE content_id = '$delete_id' ";
      $delete_likes_result = $conn->query($delete_likes);
      $delete_comments = "DELETE FROM `comments` WHERE content_id = '$delete_id' ";
      $delete_comments_result = $conn->query($delete_comments);
      $delete_content = "DELETE FROM `content` WHERE id = '$delete_id'";
      $delete_content_result = $conn->query($delete_content);
      $message[] = 'video deleted!';
   }else{
      $message[] = 'video already deleted!';
   }

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Playlist Details</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="playlist-details">

   <h1 class="heading">playlist details</h1>

   <?php
      $select_playlist ="SELECT * FROM `playlist` WHERE id = '$get_id'AND tutor_id = '$tutor_id'";
      $select_playlist_result = $conn->query($select_playlist);
      if ($select_playlist_result->num_rows > 0) {
         while ($row = $select_playlist_result->fetch_assoc()) {
            $playlist_id = $row['id'];
            $count_videos = "SELECT * FROM `content` WHERE playlist_id = '$playlist_id'";
            $count_videos_result = $conn->query($count_videos);
            $total_videos = $count_videos_result->num_rows;
        
   ?>
   <div class="row">
      <div class="thumb">
         <span><?= $total_videos; ?></span>
         <img src="../uploaded_files/<?= $row['thumb']; ?>" alt="">
      </div>
      <div class="details">
         <h3 class="title"><?= $row['title']; ?></h3>
         <div class="date"><i class="fas fa-calendar"></i><span><?= $row['date']; ?></span></div>
         <div class="description"><?= $row['description']; ?></div>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="playlist_id" value="<?= $playlist_id; ?>">
            <a href="update_playlist.php?get_id=<?= $playlist_id; ?>" class="option-btn">update playlist</a>
            <input type="submit" value="delete playlist" class="delete-btn" onclick="return confirm('delete this playlist?');" name="delete">
         </form>
      </div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">no playlist found!</p>';
      }
   ?>

</section>



<section class="contents">

   <h1 class="heading">playlist videos</h1>

   <div class="box-container">

   <?php
      $select_videos = "SELECT * FROM `content` WHERE tutor_id = '$tutor_id' AND playlist_id = '$playlist_id'";
      $result = $conn->query($select_videos);
      if($result->num_rows > 0){
         while($row = $result->fetch_assoc()){ 
            $video_id = $row['id'];
   ?>
      <div class="box">
         <div class="flex">
            <div><i class="fas fa-dot-circle" style="<?php if($row['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"></i><span style="<?php if($row['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"><?= $row['status']; ?></span></div>
            <div><i class="fas fa-calendar"></i><span><?= $row['date']; ?></span></div>
         </div>
         <img src="../uploaded_files/<?= $row['thumb']; ?>" class="thumb" alt="">
         <h3 class="title"><?= $row['title']; ?></h3>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="video_id" value="<?= $video_id; ?>">
            <a href="update_content.php?get_id=<?= $video_id; ?>" class="option-btn">update</a>
            <input type="submit" value="delete" class="delete-btn" onclick="return confirm('delete this video?');" name="delete_video">
         </form>
         <a href="view_content.php?get_id=<?= $video_id; ?>" class="btn">watch video</a>
      </div>
   <?php
         }
      }else{
         echo '<p class="empty">no videos added yet! <a href="add_content.php" class="btn" style="margin-top: 1.5rem;">add videos</a></p>';
      }
   ?>

   </div>

</section>















<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>