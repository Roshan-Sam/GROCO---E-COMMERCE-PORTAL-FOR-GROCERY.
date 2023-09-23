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

        <a href="home.php" class="logo">Groco<span>.</span></a>

        <nav class="navbar">
         <a href="home.php">home</a>
         <a href="shop.php">shop</a>
         <a href="orders.php">orders</a>
         <a href="about.php">about</a>
         <a href="contact.php">contact</a>
      </nav>


        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
            <a href="search_page.php" class="fas fa-search"></a>
         <?php
            $count_cart_items = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
            $count_wishlist_items = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE user_id = '$user_id'") or die('query failed');
            $cart_num_rows = mysqli_num_rows($count_cart_items);
            $wishlist_num_rows = mysqli_num_rows($count_wishlist_items);
         ?>
         <a href="wishlist.php"><i class="fas fa-heart"></i><span>(<?php echo $wishlist_num_rows; ?>)</span></a>
         <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?php echo $cart_num_rows; ?>)</span></a>
         
        </div>

        <div class="profile">
            <?php
                $select_profile = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$user_id'") or die('query failed');
                $fetch_profile = mysqli_fetch_assoc($select_profile);
            ?>
            <img src="uploaded_img/<?= $fetch_profile['image']; ?>" alt="">
            <p><?= $fetch_profile['name']; ?></p>
            <a href="user_profile_update.php" class="btn">update profile</a>
            <a href="logout.php" class="delete-btn">logout</a>
            <div class="flex-btn">
                <a href="login.php" class="option-btn">Login</a>
                <a href="register.php" class="option-btn">Register</a>

            </div>

        </div>

    </div>

</header>