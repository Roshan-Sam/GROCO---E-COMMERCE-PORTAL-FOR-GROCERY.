<?php

@include 'config.php';

session_start();

$admin_id =  $_SESSION['admin_id'];

if(!isset($admin_id)){
    header('location:login.php');
};

if(isset($_POST['add_product'])){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $details = mysqli_real_escape_string($conn, $_POST['details']);
 
    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/'.$image;
 
    $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE name = '$name'") or die('query failed');
 
       if(mysqli_num_rows($select_products) > 0){
         $message[] = 'product name already exist!';   
    }else{

       $insert_products = mysqli_query($conn, "INSERT INTO `products`(name, category, details, price, image) VALUES('$name', '$category', '$details', '$price', '$image')") or die('query failed');
 
       if($insert_products){
          if($image_size > 2000000){
             $message[] = 'image size is too large!';
          }else{
             move_uploaded_file($image_tmp_name, $image_folder);
             $message[] = 'new product added!';
          }
 
       }
 
    }
 
 };

 if(isset($_GET['delete'])){

    $delete_id = $_GET['delete'];
    $select_delete_image = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('query failed');
    $fetch_delete_image = mysqli_fetch_assoc($select_delete_image);
    unlink('uploaded_img/'.$fetch_delete_image['image']);
    mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
    mysqli_query($conn, "DELETE FROM `wishlist` WHERE pid = '$delete_id'") or die('query failed');
    mysqli_query($conn, "DELETE FROM `cart` WHERE pid = '$delete_id'") or die('query failed');
    header('location:admin_products.php');
 
 }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>products</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <link rel="stylesheet" href="css/admin_style.css">

   

</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="add-products">

    <h1 class="title">add new product</h1>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="flex">
            <div class="inputBox">
            <input type="text" name="name" class="box" required placeholder="enter product name">
            <select name="category" class="box" required>
                <option value="" selected disabled>select category</option>
                <option value="vegitables">vegitables</option>
                <option value="fruits">fruits</option>
               <option value="meat">meat</option>
               <option value="fish">fish</option>
               <option value="vegies kit">vegies kit</option>
            </select>
            </div>
            <div class="inputBox">
            <input type="number" min="0" name="price" class="box" required placeholder="enter product price">
            <input type="file" name="image" required class="box" accept="image/jpg, image/jpeg, image/png">
            </div>
        </div>
        <textarea name="details" class="box" required placeholder="enter product details" cols="30" rows="10"></textarea>
        <input type="submit" class="btn" value="add product" name="add_product">
    </form>

</section>


<section class="show-products">

   <h1 class="title">products added</h1>

   <div class="box-container">

   <?php
      $show_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
      if(mysqli_num_rows($show_products) > 0){
      while($fetch_products = mysqli_fetch_assoc($show_products)){      
   ?>
   <div class="box">
      <div class="price">Rs<?= $fetch_products['price']; ?>/-</div>
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <div class="name"><?= $fetch_products['name']; ?></div>
      <div class="cat"><?= $fetch_products['category']; ?></div>
      <div class="details"><?= $fetch_products['details']; ?></div>
      <div class="flex-btn">
         <a href="admin_update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">update</a>
         <a href="admin_products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
      </div>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">now products added yet!</p>';
   }
   ?>

   </div>

</section>




<script src="js/script.js"></script>
    
</body>
</html>