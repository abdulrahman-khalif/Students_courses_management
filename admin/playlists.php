<?php
session_start();
include '../components/connect2.php';

if (!isset($_SESSION['id'])) {
    header('Location: login_admin.php');
    exit();
} else {
    $tutor_id = $_SESSION['id'];

    $query = mysqli_query($conn, "SELECT * FROM tutors WHERE id ='$tutor_id'");

    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_array($query);

        $old_name = $row['name'];
        $old_profession = $row['profession'];
        $old_email = $row['email'];
        $old_image = $row['image'];
    }
}

if (isset($_POST['delete'])) {
    $delete_id = $_POST['playlist_id'];

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
    <title>Playlists</title>
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="playlists">
    <h1 class="heading">Added Playlists</h1>

    <div class="box-container">
        <div class="box" style="text-align: center;">
            <h3 class="title" style="margin-bottom: .5rem;">Create New Playlist</h3>
            <a href="add_playlist.php" class="btn">Add Playlist</a>
        </div>

        <?php
        $select_playlist = "SELECT * FROM `playlist` WHERE tutor_id = '$tutor_id'";
        $result = $conn->query($select_playlist);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $playlist_id = $row['id'];

                $count_videos_query = "SELECT * FROM `content` WHERE playlist_id = '$playlist_id' ORDER BY date DESC";
                $count_videos_result = $conn->query($count_videos_query);
                $total_videos = $count_videos_result->num_rows;
                ?>
                <div class="box">
                    <div class="flex">
                        <div>
                            <i class="fas fa-circle-dot" style="<?php echo ($row['status'] == 'active') ? 'color:limegreen' : 'color:red'; ?>"></i>
                            <span style="<?php echo ($row['status'] == 'active') ? 'color:limegreen' : 'color:red'; ?>"><?php echo $row['status']; ?></span>
                        </div>
                        <div><i class="fas fa-calendar"></i><span><?php echo $row['date']; ?></span></div>
                    </div>
                    <div class="thumb">
                        <span><?php echo $total_videos; ?></span>
                        <img src="../uploaded_files/<?php echo $row['thumb']; ?>" alt="">
                    </div>
                    <h3 class="title"><?php echo $row['title']; ?></h3>
                    <p class="description"><?php echo $row['description']; ?></p>
                    <form action="" method="post" class="flex-btn">
                        <input type="hidden" name="playlist_id" value="<?php echo $playlist_id; ?>">
                        <a href="update_playlist.php?get_id=<?php echo $playlist_id; ?>" class="option-btn">Update</a>
                        <input type="submit" value="Delete" class="delete-btn" onclick="return confirm('Delete this playlist?');" name="delete">
                    </form>
                    <a href="view_playlist.php?get_id=<?php echo $playlist_id; ?>" class="btn">View Playlist</a>
                </div>
                <?php
            }
        } else {
            echo '<p class="empty">No playlist added yet!</p>';
        }
        ?>
    </div>
</section>

<?php include '../components/footer.php'; ?>
<script src="../js/admin_script.js"></script>
<script>
    document.querySelectorAll('.playlists .box-container .box .description').forEach(content => {
        if (content.innerHTML.length > 100) content.innerHTML = content.innerHTML.slice(0, 100);
    });
</script>

</body>
</html>
