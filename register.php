<?php
include 'components/connect2.php';

if (isset($_POST['submit'])) {

    $id = unique_id();

    $name = $_POST['name'];
   
   
    $email = $_POST['email'];
   
    $pass = $_POST['pass'];
  
    $cpass =$_POST['cpass'];
  

    $image = $_FILES['image']['name'];

    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = unique_id() . '.' . $ext;
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_files/' . $rename;

    $select_tutor = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $select_tutor->bind_param("s", $email);
    $select_tutor->execute();
    $result = $select_tutor->get_result();

    if ($result->num_rows > 0) {
        $message[] = 'email already taken!';
    } else {
        if ($pass != $cpass) {
            $message[] = 'confirm password not matched!';
        } else {
            $insert_tutor = $conn->prepare("INSERT INTO `users` (id, name, email, password, image) VALUES (?, ?, ?, ?, ?)");
            $insert_tutor->bind_param("sssss", $id, $name, $email, $pass, $rename);
            $insert_tutor->execute();
            move_uploaded_file($image_tmp_name, $image_folder);
            $message1[] = 'new tutor registered! please login now';
        }
    }

    $select_tutor->close();
    if (isset($insert_tutor)) {
        $insert_tutor->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style2.css">

</head>
<body class="register_admin" style="padding-left: 0;">

<!-- register section starts  -->

<header class="header">
   <section class="flex">
   <a href="home.php" class="logo"><img src = "assets/k_icon.png" width = "50" hight = "50" ></a>
   <div class="icons">
         <div id="user-btn" class="fas fa-user"></div>
      </div>
      <div class="profile">
         <img src="assets/pic-3.jpg" class="image" alt="">
         <h3 class="name">Abdulrahman Nur</h3>
         <p class="role">student</p>
         <a href="profile.html" class="btn">view profile</a>
         <div class="flex-btn">
            <a href="login.html" class="option-btn">login</a>
            <a href="register.html" class="option-btn">register</a>
         </div>
      </div>
   </section>
</header>  


<?php if (!empty($message1)): ?>
   <div class="complete_it">
      <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
      <?= implode('<br>', $message1) ?>
   </div>
   <?php endif; ?>

<?php if (!empty($message)): ?>
   <div class="alert">
      <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
      <?= implode('<br>', $message) ?>
   </div>
<?php endif; ?>

<section class="form-container">
   <form class="register" action="" method="post" enctype="multipart/form-data">
      <h3>register new</h3>
      <div class="flex">
         <div class="col">
            <p>your name <span>*</span></p>
            <input type="text" name="name" placeholder="enter your name" maxlength="50" required class="box">
            
            <p>your email <span>*</span></p>
            <input type="email" name="email" placeholder="enter your email" maxlength="50" required class="box">
         </div>
         <div class="col">
            <p>your password <span>*</span></p>
            <input type="password" name="pass" placeholder="enter your password" maxlength="20" required class="box">
            <p>confirm password <span>*</span></p>
            <input type="password" name="cpass" placeholder="confirm your password" maxlength="20" required class="box">
            
         </div>
      </div>
      <p>select pic <span>*</span></p>
            <input type="file" name="image" accept="image/*" required class="box">
      <p class="link">already have an account? <a href="login.php">login now</a></p>
      <input type="submit" name="submit" value="register now" class="btn">
   </form>
</section>

<!-- register section ends -->

<script>
let darkMode = localStorage.getItem('dark-mode');
let body = document.body;

const enableDarkMode = () => {
   body.classList.add('dark');
   localStorage.setItem('dark-mode', 'enabled');
}

const disableDarkMode = () => {
   body.classList.remove('dark');
   localStorage.setItem('dark-mode', 'disabled');
}

if (darkMode === 'enabled') {
   enableDarkMode();
} else {
   disableDarkMode();
}
</script>

</body>
</html>
