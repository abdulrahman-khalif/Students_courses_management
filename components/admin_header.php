<?php

if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}

?>


<header class="header">
   <section class="flex">
      <a href="dashboard.php" class="logo"><img src = "../assets/k_icon.png" width = "50" hight = "50" ></a>

      <form action="search_page.php" method="post" class="search-form">
         <input type="text" name="search" placeholder="search here..." required maxlength="100">
         <button type="submit" class="fas fa-search" name="search_btn"></button>
      </form>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="search-btn" class="fas fa-search"></div>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="toggle-btn" class="fas fa-sun"></div>
      </div>

      <div class="profile">
      <?php
         $query=mysqli_query($conn,"select * from tutors where id ='$tutor_id'");
          
         if (mysqli_num_rows($query) > 0){
         $row=mysqli_fetch_array($query);
         
         
         $name = $row['name'];
         $profession = $row ['profession'];
         $email  = $row['email'];
         $image = $row['image'];
         
     

      ?>
         <img src="../uploaded_files/<?= $image; ?>" alt="">
         <h3><?= $name; ?></h3>
         <span><?=  $profession; ?></span>
         <a href="profile.php" class="btn">view profile</a>
         <a class = "delete-btn" href="../components/admin_logout.php" onclick="return confirm('logout from this website?');"><i class="fas fa-right-from-bracket"></i><span>logout</span></a>
      <?php
         } else {
      ?>
         <h3>please login or register</h3>
         <div class="flex-btn">
            <a href="login.php" class="option-btn">login</a>
            <a href="register.php" class="option-btn">register</a>
         </div>
      <?php
         }
      ?>
      </div>
   </section>
</header>
<!-- header section ends -->


<!-- side bar section starts  -->

<div class="side-bar">

<div class="close-side-bar">
   <i class="fas fa-times"></i>
</div>

<div class="profile">
       <?php
          $query=mysqli_query($conn,"select * from tutors where id ='$tutor_id'");
          
          if (mysqli_num_rows($query) > 0){
          $row=mysqli_fetch_array($query);
          
          
          $name = $row['name'];
          $profession = $row ['profession'];
          $email  = $row['email'];
          $image = $row['image'];
          
      ?>
                <img src="../uploaded_files/<?= $image; ?>" alt="">
                  <h3><?= $name; ?></h3>
                  <span><?= $profession; ?></span>


            
                  <a href="profile.php" class="btn">view profile</a>
                  <?php
            }
         else{
         ?>
         <h3>please login or register</h3>
          <div class="flex-btn">
            <a href="login.php" class="option-btn">login</a>
            <a href="register.php" class="option-btn">register</a>
         </div>
         <?php
            }
         ?>
      </div>

   <nav class="navbar">
      <a href="dashboard.php"><i class="fas fa-home"></i><span>home</span></a>
      <a href="playlists.php"><i class="fa-solid fa-bars-staggered"></i><span>playlists</span></a>
      <a href="contents.php"><i class="fas fa-graduation-cap"></i><span>contents</span></a>
      <a href="my_students.php"><i class="fa-solid fa-address-book"></i><span>My Students </span></a>
      <a href="comments.php"><i class="fas fa-comment"></i><span>comments</span></a>
      <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');"><i class="fas fa-right-from-bracket"></i><span>logout</span></a>
      </nav>

</div>