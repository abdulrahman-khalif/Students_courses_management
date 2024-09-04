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

if(isset($_POST['save_list'])){

   if($user_id != ''){
      
      $list_id = $_POST['list_id'];

      $select_list = "SELECT * FROM `bookmark` WHERE user_id = '$user_id' AND playlist_id = '$list_id'";
      $select_list_result = $conn->query($select_list);

      if($select_list_result->num_rows > 0){
         $remove_bookmark = "DELETE FROM `bookmark` WHERE user_id = '$user_id' AND playlist_id = '$list_id'";
         $remove_bookmark_result = $conn->query($remove_bookmark);
         $message[] = 'playlist removed!';
      }else{
         $insert_bookmark = "INSERT INTO `bookmark`(user_id, playlist_id) VALUES('$user_id','$list_id')";
         $insert_bookmark_result = $conn->query($insert_bookmark);
         $message[] = 'playlist saved!';
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
   <title>playlist</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- playlist section starts  -->

<section class="playlist">

   <h1 class="heading">playlist details</h1>

   <div class="row">

      <?php
         $select_playlist = "SELECT * FROM `playlist` WHERE id = '$get_id' and status = 'active' LIMIT 1";
         $select_playlist_result = $conn->query($select_playlist);
         if($select_playlist_result->num_rows > 0){
            $fetch_playlist = $select_playlist_result->fetch_assoc();

            $playlist_id = $fetch_playlist['id'];

            $count_videos = "SELECT * FROM `content` WHERE playlist_id = '$playlist_id'";
            $count_videos_result = $conn->query($count_videos);
            $total_videos = $count_videos_result->num_rows;

            $select_tutor = "SELECT * FROM `tutors` WHERE id = '$fetch_playlist[tutor_id]' LIMIT 1";
            $select_tutor_result = $conn->query($select_tutor);
            $fetch_tutor = $select_tutor_result->fetch_assoc();

            $select_bookmark = "SELECT * FROM `bookmark` WHERE user_id = '$user_id' AND playlist_id = '$playlist_id'";
            $select_bookmark_result  = $conn->query($select_bookmark);

      ?>

      <div class="col">
         <form action="" method="post" class="save-list">
            <input type="hidden" name="list_id" value="<?= $playlist_id; ?>">
            <?php
               if($select_bookmark_result->num_rows > 0){
            ?>
            <button type="submit" name="save_list"><i class="fas fa-bookmark"></i><span>saved</span></button>
            <?php
               }else{
            ?>
               <button type="submit" name="save_list"><i class="far fa-bookmark"></i><span>save playlist</span></button>
            <?php
               }
            ?>
         </form>
         <div class="thumb">
            <span><?= $total_videos; ?> videos</span>
            <img src="uploaded_files/<?= $fetch_playlist['thumb']; ?>" alt="">
         </div>
      </div>

      <div class="col">
         <div class="tutor">
            <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_tutor['name']; ?></h3>
               <span><?= $fetch_tutor['profession']; ?></span>
            </div>
         </div>
         <div class="details">
            <h3><?= $fetch_playlist['title']; ?></h3>
            <p><?= $fetch_playlist['description']; ?></p>
            <div class="date"><i class="fas fa-calendar"></i><span><?= $fetch_playlist['date']; ?></span></div>
         </div>
      </div>

      <?php
         }else{
            echo '<p class="empty">this playlist was not found!</p>';
         }  
      ?>

   </div>

</section>

<!-- playlist section ends -->

<!-- videos container section starts  -->

<section class="videos-container">

   <h1 class="heading">playlist videos</h1>

   <div class="box-container">

      <?php
         $select_content = "SELECT * FROM `content` WHERE playlist_id = '$playlist_id' AND status = 'active' ORDER BY date DESC";
         $select_content_result = $conn->query($select_content);
         if($select_content_result->num_rows> 0){
            while($fetch_content = $select_content_result->fetch_assoc()) {  
      ?>
      <a href="watch_video.php?get_id=<?= $fetch_content['id']; ?>" class="box">
         <i class="fas fa-play"></i>
         <img src="uploaded_files/<?= $fetch_content['thumb']; ?>" alt="">
         <h3><?= $fetch_content['title']; ?></h3>
      </a>
      <?php
            }
         }else{
            echo '<p class="empty">no videos added yet!</p>';
         }
      ?>

   </div>

</section>

<!-- videos container section ends -->











<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>