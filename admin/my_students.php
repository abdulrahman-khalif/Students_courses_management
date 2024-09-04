<?php
session_start();
include '../components/connect2.php';

if (!isset($_SESSION['id'])) {
   header('Location: login_admin.php');
   exit();
} else {
   $tutor_id = $_SESSION['id'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>My Students</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<!-- teachers section starts  -->
<section class="teachers">
   <h1 class="heading">My Students</h1>

  

   <div class="box-container">
      <?php
         // Fetch all playlists created by the logged-in tutor
         $select_playlists = "SELECT * FROM `playlist` WHERE tutor_id = '$tutor_id'";
         $select_playlists_result = $conn->query($select_playlists);

         if($select_playlists_result->num_rows > 0){
            while($playlist = $select_playlists_result->fetch_assoc()){
               $playlist_id = $playlist['id'];
                $title = $playlist['title'];
               // Fetch bookmarks for each playlist
               $select_bookmarks = "SELECT * FROM `bookmark` WHERE playlist_id = '$playlist_id'";
               $select_bookmarks_result = $conn->query($select_bookmarks);

               if($select_bookmarks_result->num_rows > 0){
                  while($bookmark = $select_bookmarks_result->fetch_assoc()){
                     $user_id = $bookmark['user_id'];

                     // Fetch user details for each bookmarked playlist
                     $select_users = "SELECT * FROM `users` WHERE `id` = '$user_id'";
                     $select_users_result = $conn->query($select_users);

                     if($select_users_result->num_rows > 0){
                        while($user = $select_users_result->fetch_assoc()){
                           $user_name = $user['name'];
                           $user_image = $user['image'];
                           $user_email = $user['email'];

                           ?>
                           <div class="box">
                              <div class="tutor">
                                 <img src="../uploaded_files/<?= $user_image; ?>" alt="">
                                 <div>
                                    <h3><?= $user_name; ?></h3>
                                    <span>Student</span>
                                    <br/>
                                    <h2><span style = "font-size: 16px;"><?= $title ?></h2></span>
                                 </div>
                              </div>

                              <form action="user_profile.php" method="post">
                                 
                                 <input type="hidden" name="user_email" value="<?= $user_email; ?>">
                                 <input type="submit" value="View Profile" name="user_fetch" class="inline-btn">
                              </form>
                           </div>
                           <?php
                        }
                     }
                  }
               }
            }
         } else {
            echo '<p class="empty">No students found!</p>';
         }
      ?>
   </div>
</section>
<!-- teachers section ends -->

<?php include '../components/footer.php'; ?>    

<!-- custom js file link  -->
<script src="../js/script.js"></script>
</body>
</html>
