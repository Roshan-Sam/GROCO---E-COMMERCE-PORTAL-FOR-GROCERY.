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

    <div class="flex">

        <a href="admin_page.php" class="logo">Admin<span>panel</span></a>

        <nav class="navbar">

            <a href="admin_page.php">home</a>
            <a href="admin_products.php">products</a>
            <a href="admin_orders.php">orders</a>
            <a href="admin_users.php">users</a>
            <a href="admin_contacts.php">messages</a>

        </nav>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>

        </div>

        <div class="profile">
            <?php
                
                $select_profile = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$admin_id'") or die('query failed');
                $fetch_profile = mysqli_fetch_assoc($select_profile);
            ?>
            <img src="uploaded_img/<?= $fetch_profile['image']; ?>" alt="">
            <p><?= $fetch_profile['name']; ?></p>
            <a href="admin_update_profile.php" class="btn">update profile</a>
            <a href="logout.php" class="delete-btn">logout</a>
            <div class="flex-btn">
                <a href="login.php" class="option-btn">Login</a>
                <a href="register.php" class="option-btn">Register</a>

            </div>

        </div>

    </div>
                
</header>