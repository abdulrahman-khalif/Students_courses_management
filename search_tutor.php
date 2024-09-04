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
   <title>courses</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="teachers">

   <h1 class="heading">expert tutors</h1>

   <form action="" method="post" class="search-tutor">
      <input type="text" name="search_tutor" maxlength="100" placeholder="search tutor..." required>
      <button type="submit" name="search_tutor_btn" class="fas fa-search"></button>
   </form>

   <div class="box-container">

      <?php
         if(isset($_POST['search_tutor']) or isset($_POST['search_tutor_btn'])){
            $search_tutor = $_POST['search_tutor'];
            $select_tutors = "SELECT * FROM `tutors` WHERE name LIKE '%{$search_tutor}%'";
            $select_tutors_result = $conn->query($select_tutors);
            if($select_tutors_result->num_rows > 0){
               while($fetch_tutor = $select_tutors_result->fetch_assoc()){

                  $tutor_id = $fetch_tutor['id'];

                  $count_playlists = "SELECT * FROM `playlist` WHERE tutor_id = '$tutor_id'";
                  $count_playlists_result = $conn->query($count_playlists);
                  $total_playlists = $count_playlists_result->num_rows;

                  $count_contents = "SELECT * FROM `content` WHERE tutor_id = '$tutor_id' ";
                  $count_contents_result = $conn->query($count_contents);
                  $total_contents = $count_contents_result->num_rows;

                  $count_likes = "SELECT * FROM `likes` WHERE tutor_id = '$tutor_id'";
                  $count_likes_result = $conn->query($count_likes);
                  $total_likes = $count_likes_result->num_rows;

                  $count_comments = "SELECT * FROM `comments` WHERE tutor_id = '$tutor_id'";
                  $count_comments_result = $conn->query($count_comments);
                  $total_comments = $count_comments_result->num_rows;
      ?>
      <div class="box">
         <div class="tutor">
            <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_tutor['name']; ?></h3>
               <span><?= $fetch_tutor['profession']; ?></span>
            </div>
         </div>
         <p>playlists : <span><?= $total_playlists; ?></span></p>
         <p>total videos : <span><?= $total_contents ?></span></p>
         <p>total likes : <span><?= $total_likes ?></span></p>
         <p>total comments : <span><?= $total_comments ?></span></p>
         <form action="tutor_profile.php" method="post">
            <input type="hidden" name="tutor_email" value="<?= $fetch_tutor['email']; ?>">
            <input type="submit" value="view profile" name="tutor_fetch" class="inline-btn">
         </form>
      </div>
      <?php
               }
            }else{
               echo '<p class="empty">no results found!</p>';
            }
         }else{
            echo '<p class="empty">please search something!</p>';
         }
      ?>

   </div>

</section>

<!-- teachers section ends -->










<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>