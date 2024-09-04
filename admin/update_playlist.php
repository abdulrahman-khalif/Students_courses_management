<?php

session_start();
include '../components/connect2.php';

if (!isset($_SESSION['id'])) {
   header('Location: login_admin.php');
   exit(); // Ensure the script stops after redirecting
} else {
    $tutor_id = $_SESSION['id'];
}

if (isset($_GET['get_id'])) {
   $get_id = $_GET['get_id'];
} else {
   $get_id = '';
   header('location:playlist.php');
}

if (isset($_POST['submit'])) {

   $title = $_POST['title'];
   $description = $_POST['description'];
   $status = $_POST['status'];

   $update_playlist = "UPDATE `playlist` SET title = '$title', description = '$description', status = '$status' WHERE id = '$get_id'";
   $conn->query($update_playlist);

   $old_image = $_POST['old_image'];
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = unique_id() . '.' . $ext;
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_files/' . $rename;

   if (!empty($image)) {
      if ($image_size > 2000000) {
         $message[] = 'Image size is too large!';
      } else {
         $update_image = "UPDATE `playlist` SET thumb = '$rename' WHERE id = '$get_id'";
         $conn->query($update_image);

         move_uploaded_file($image_tmp_name, $image_folder);
         if ($old_image != '' && $old_image != $rename) {
            unlink('../uploaded_files/' . $old_image);
         }
      }
   }

   $message[] = 'Playlist updated!';
}
if (isset($_POST['delete'])) {
    $delete_id = $get_id;

    $verify_playlist = "SELECT * FROM `playlist` WHERE id = '$delete_id' AND tutor_id = '$tutor_id' LIMIT 1";
    $result = $conn->query($verify_playlist);

    if ($result->num_rows > 0) {
        $fetch_thumb = $result->fetch_assoc();
        unlink('../uploaded_files/' . $fetch_thumb['thumb']);

        $delete_bookmark = "DELETE FROM `bookmark` WHERE playlist_id = '$delete_id'";
        $conn->query($delete_bookmark);

        $delete_playlist = "DELETE FROM `playlist` WHERE id = '$delete_id'";
        $conn->query($delete_playlist);

        $message[] = 'Playlist deleted!';
    } else {
        $message[] = 'Playlist already deleted!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Playlist</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="playlist-form">

   <h1 class="heading">Update Playlist</h1>

   <?php
         $select_playlist = "SELECT * FROM `playlist` WHERE id = '$get_id'";
         $result = $conn->query($select_playlist);
         if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $playlist_id = $row['id'];
                $count_videos_query = "SELECT * FROM `content` WHERE playlist_id = '$playlist_id'";
                $count_videos_result = $conn->query($count_videos_query);
                $total_videos = $count_videos_result->num_rows;
      ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="old_image" value="<?= $row['thumb']; ?>">
      <p>Playlist Status <span>*</span></p>
      <select name="status" class="box" required>
         <option value="<?= $row['status']; ?>" selected><?= $row['status']; ?></option>
         <option value="active">Active</option>
         <option value="deactive">Deactive</option>
      </select>
      <p>Playlist Title <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="Enter playlist title" value="<?= $row['title']; ?>" class="box">
      <p>Playlist Description <span>*</span></p>
      <textarea name="description" class="box" required placeholder="Write description" maxlength="1000" cols="30" rows="10"><?= $row['description']; ?></textarea>
      <p>Playlist Thumbnail <span>*</span></p>
      <div class="thumb">
         <span><?= $total_videos; ?></span>
         <img src="../uploaded_files/<?= $row['thumb']; ?>" alt="">
      </div>
      <input type="file" name="image" accept="image/*" class="box">
      <input type="submit" value="Update Playlist" name="submit" class="btn">
      <div class="flex-btn">
         <input type="submit" value="Delete" class="delete-btn" onclick="return confirm('Delete this playlist?');" name="delete">
         <a href="view_playlist.php?get_id=<?= $playlist_id; ?>" class="option-btn">View Playlist</a>
      </div>
   </form>
   <?php
      } 
   } else {
      echo '<p class="empty">No playlist added yet!</p>';
   }
   ?>

</section>

<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>
