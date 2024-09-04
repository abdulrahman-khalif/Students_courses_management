<?php
session_start();
include '../components/connect2.php';

if (!isset($_SESSION['id'])) {
   header('Location:login_admin.php');
   exit(); // Ensure the script stops after redirecting
}else{
    $tutor_id = $_SESSION['id'];

    $query=mysqli_query($conn,"select * from tutors where id ='$tutor_id'");

    if (mysqli_num_rows($query) > 0){
    $row=mysqli_fetch_array($query);
    
    
    $old_name = $row['name'];
    $old_profession = $row ['profession'];
    $old_email  = $row['email'];
   
    $old_image = $row['image'];
    }
}

if (isset($_POST['submit'])) {
    

    
    $prev_image = $old_image;

    $name = $_POST['name'];
    $profession = $_POST['profession'];
    $email = $_POST['email'];

    if (!empty($name)) {
        $sql_update_name = "UPDATE `tutors` SET `name`='$name' WHERE id ='$tutor_id'";
        $result = $conn->query($sql_update_name);
        $message[] = 'Username updated successfully!';
    }

    if (!empty($profession)) {

        $sql_update_profession = "UPDATE `tutors` SET `profession`='$profession' WHERE id ='$tutor_id'";
        $result = $conn->query($sql_update_profession);        
        $message[] = 'Profession updated successfully!';
    }

    if (!empty($email)) {
        $query=mysqli_query($conn,"select * from tutors where id ='$tutor_id' && email='$email'");
        if (mysqli_num_rows($query) > 0){
            $message[] = 'Email already taken!';
        } 
        else {
            $sql_update_email = "UPDATE `tutors` SET email = '$email' WHERE id = '$tutor_id'";
            $result = $conn->query($sql_update_email);        
            $message[] = 'Email updated successfully!';
        }
    }

    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = uniqid() . '.' . $ext;
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_files/' . $rename;

    if (!empty($image)) {
        if ($image_size > 2000000) {
            $message[] = 'Image size too large!';
        } else {
            $sql_update_image = "UPDATE `tutors` SET `image` = '$rename' WHERE `id` = '$tutor_id'";
            $result = $conn->query($sql_update_image); 
            move_uploaded_file($image_tmp_name, $image_folder);
            
            $message[] = 'Image updated successfully!';
        }
    }

   
    

        }
    

?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Profile</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>


<?php if (!empty($message[0])):  ?>

    <div class="alert">
   <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
   <?= htmlspecialchars($message[0]) ?>
</div>

   </div>
   <?php endif; ?>

   <?php if (!empty($message[1])):  ?>

<div class="alert">
<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
 <?= $message[1] ?>

   </div>
   <?php endif; ?>

   <?php if (!empty($message[2])):  ?>

<div class="alert">
<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
 <?= $message[2]?>

   </div>
   <?php endif; ?>


   <?php if (!empty($message[3])):  ?>

        <div class="alert">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
        <?= $message[3] ?>

   </div>
   <?php endif; ?>



<!-- register section starts  -->

<section class="form-container" style="min-height: calc(100vh - 19rem);">

   <form class="register" action="" method="post" enctype="multipart/form-data">
      <h3>update profile</h3>
      <div class="flex">
         <div class="col">
            <p>your name </p>
            <input type="text" name="name" placeholder="<?= $old_name;  ?>" maxlength="50" class="box">
            <p>your profession </p>
            <select name="profession" class="box">
               <option value="" selected><?= $old_profession; ?></option>
               <option value="developer">developer</option>
               <option value="designer">designer</option>
               <option value="musician">musician</option>
               <option value="biologist">biologist</option>
               <option value="teacher">teacher</option>
               <option value="engineer">engineer</option>
               <option value="lawyer">lawyer</option>
               <option value="accountant">accountant</option>
               <option value="doctor">doctor</option>
               <option value="journalist">journalist</option>
               <option value="photographer">photographer</option>
            </select>
            <p>your email </p>
            <input type="email" name="email"  placeholder="<?= $old_email;  ?> "      maxlength="20" class="box">
         </div>
        
      </div>
      <p>update pic :</p>
      <input type="file" name="image" accept="image/*" class="box">
      <input type="submit" name="submit" value="update now" class="btn">
   </form>

</section>

<!-- register section ends -->

<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>
