<?php

@include 'config.php';

session_start();

$user_id =  $_SESSION['user_id'];

if(!isset($user_id)){
    header('location:login.php');
};

if(isset($_POST['add_to_wishlist'])){

    $pid = $_POST['pid'];
    $pid = filter_var($pid, FILTER_SANITIZE_STRING);
    $p_name = $_POST['p_name'];
    $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
    $p_price = $_POST['p_price'];
    $p_price = filter_var($p_price, FILTER_SANITIZE_STRING);
    $p_image = $_POST['p_image'];
    $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);
 
    $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$p_name' AND user_id = '$user_id'") or die('query failed');
 
    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$p_name' AND user_id = '$user_id'") or die('query failed');
 
    if(mysqli_num_rows($check_wishlist_numbers) > 0){
       $message[] = 'already added to wishlist';
   }elseif(mysqli_num_rows($check_cart_numbers) > 0){
       $message[] = 'already added to cart';
   }else{
       mysqli_query($conn, "INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES('$user_id', '$pid', '$p_name', '$p_price', '$p_image')") or die('query failed');
       $message[] = 'product added to wishlist';
   }
 
 }
 
 if(isset($_POST['add_to_cart'])){
 
    $pid = $_POST['pid'];
    $pid = filter_var($pid, FILTER_SANITIZE_STRING);
    $p_name = $_POST['p_name'];
    $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
    $p_price = $_POST['p_price'];
    $p_price = filter_var($p_price, FILTER_SANITIZE_STRING);
    $p_image = $_POST['p_image'];
    $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);
    $p_qty = $_POST['p_qty'];
    $p_qty = filter_var($p_qty, FILTER_SANITIZE_STRING);
    
    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$p_name' AND user_id = '$user_id'") or die('query failed');
 
    if(mysqli_num_rows($check_cart_numbers) > 0){
       $message[] = 'already added to cart';
   }else{
 
       $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$p_name' AND user_id = '$user_id'") or die('query failed');
 
       if(mysqli_num_rows($check_wishlist_numbers) > 0){
           mysqli_query($conn, "DELETE FROM `wishlist` WHERE name = '$p_name' AND user_id = '$user_id'") or die('query failed');
       }
 
       mysqli_query($conn, "INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES('$user_id', '$pid', '$p_name', '$p_price', '$p_qty', '$p_image')") or die('query failed');
       $message[] = 'product added to cart';
   }
 
 }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>shop</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'header.php'; ?>

<section class="p-category">

   <a href="category.php?category=fruits">fruits</a>
   <a href="category.php?category=vegitables">vegitables</a>
   <a href="category.php?category=fish">fish</a>
   <a href="category.php?category=meat">meat</a>    
   <a href="category.php?category=vegies kit">vegies kit</a>


</section>

<section class="products">

   <h1 class="title">latest products</h1>

   <div class="box-container">

   <?php
      $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
        if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
   ?>
   <form action="" class="box" method="POST">
      <div class="price">Rs<span><?= $fetch_products['price']; ?></span>/-</div>
      <a href="view_page.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <div class="name"><?= $fetch_products['name']; ?></div>
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>">
      <input type="hidden" name="p_price" value="<?= $fetch_products['price']; ?>">
      <input type="hidden" name="p_image" value="<?= $fetch_products['image']; ?>">
      <input type="number" min="1" value="1" name="p_qty" class="qty">
      <input type="submit" value="add to wishlist" class="option-btn" name="add_to_wishlist">
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
   ?>

   </div>

</section>




<?php include 'footer.php'; ?>

<script src="js/script.js"></script>


</body>
</html>