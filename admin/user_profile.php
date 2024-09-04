<?php
session_start();
include '../components/connect2.php';

if (!isset($_SESSION['id'])) {
   header('Location: login_admin.php');
   exit(); // Ensure the script stops after redirecting
} else {
    $tutor_id = $_SESSION['id'];
}
// Get the user email from the POST request
if (isset($_POST['user_fetch']) && isset($_POST['user_email'])) {
    $user_email = $_POST['user_email'];

    // Fetch user details
    $select_user = "SELECT * FROM users WHERE email = '$user_email'";
    $user_result = $conn->query($select_user);

    if ($user_result->num_rows > 0) {
        $fetch_profile = $user_result->fetch_assoc();

        // Fetch additional data such as likes, comments, and bookmarks
        $user_id = $fetch_profile['id'];

        $select_likes = "SELECT * FROM likes WHERE user_id = '$user_id'";
        $select_likes_result = $conn->query($select_likes);
        $total_likes = $select_likes_result->num_rows;

        $select_comments = "SELECT * FROM comments WHERE user_id = '$user_id'";
        $select_comments_result = $conn->query($select_comments);
        $total_comments = $select_comments_result->num_rows;

        $select_bookmark = "SELECT * FROM bookmark WHERE user_id = '$user_id'";
        $select_bookmark_result = $conn->query($select_bookmark);
        $total_bookmarked = $select_bookmark_result->num_rows;
    } 
} else{
   header("Location: my_students.php ");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>User Profile</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>




<section class="tutor-profile">

   <h1 class="heading">profile details</h1>

   <div class="details">
      <div class="tutor">
      <img src="../uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
         <h3><?= $fetch_profile['name']; ?></h3>
         <span>Student</span>
      </div>
      <div class="flex">
      <p> <i class="fas fa-bookmark"></i>  Saved Playlists : <span><?= $total_bookmarked; ?></span></p>
        <p> <i class="fas fa-heart"></i> Liked Tutorials : <span><?= $total_likes; ?></span></p>
      <p> <i class="fas fa-comment"></i> Video Comments: <span><?= $total_comments; ?></span></p>
      </div>
   </div>

</section>

<!-- profile section ends -->

<?php include '../components/footer.php'; ?>

<!-- custom js file link -->
<script src="../js/script.js"></script>
</body>
</html>
