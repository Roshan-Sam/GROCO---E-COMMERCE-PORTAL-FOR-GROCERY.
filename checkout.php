<?php

@include 'config.php';

session_start();

$user_id =  $_SESSION['user_id'];

if(!isset($user_id)){
    header('location:login.php');
};

if(isset($_POST['order'])){

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $method = $_POST['method'];
    $method = filter_var($method, FILTER_SANITIZE_STRING);
    $address = 'flat no. '. $_POST['flat'] .' '. $_POST['street'] .' '. $_POST['city'] .' '. $_POST['state'] .' '. $_POST['country'] .' - '. $_POST['pin_code'];
    $address = filter_var($address, FILTER_SANITIZE_STRING);
    $placed_on = date('d-M-Y');
    $limit = 750;
    $veg_id = 32;
    $prod=mysqli_query($conn, "SELECT * FROM `products` WHERE  id= '$veg_id'") or die('query failed');
    $item = mysqli_fetch_assoc($prod);
    $prod_arr[] = '';
    $prod_arr[]= $item['name'];

    $cart_total = 0;
    $cart_products[] = '';
 
    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    if(mysqli_num_rows($cart_query) > 0){
        while($cart_item = mysqli_fetch_assoc($cart_query)){
            $cart_products[] = $cart_item['name'].' ('.$cart_item['quantity'].') ';
            $sub_total = ($cart_item['price'] * $cart_item['quantity']);
            $cart_total += $sub_total;
        }
    }
 
    $total_products = implode(', ', $cart_products);
    $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');
 
    if($cart_total == 0){
        $mess[] = 'your cart is empty!';
    }elseif(mysqli_num_rows($order_query) > 0){
        $mess[] = 'order placed already!';
    }elseif($cart_total >= $limit){
      $cart_products[] =  $item['name'];
      $total_products = implode(', ', $cart_products);
      mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')") or die('query failed');
      mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
      $mess[] = 'order placed successfully! with a small size vegies kit';
    }else{
        mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')") or die('query failed');
        mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        $mess[] = 'order placed successfully!';
    }
 
 }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>checkout</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    
    <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php
if(isset($mess)){
   foreach($mess as $mess){
      echo '
      <div class="mess">
      <img src="project images/tick.png" alt=""><br><br>
         <span>'.$mess.'</span><br><br>
         <button type="button"  onclick="this.parentElement.remove();">OK</button>
      </div>
      ';
   }
}
?>

<?php include 'header.php'; ?>

<section class="display-orders">

   <?php
      
      $cart_grand_total = 0;
      $select_cart_items = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
      if(mysqli_num_rows($select_cart_items) > 0){
         while($fetch_cart_items = mysqli_fetch_assoc($select_cart_items)){
             $cart_total_price = ($fetch_cart_items['price'] * $fetch_cart_items['quantity']);
             $cart_grand_total += $cart_total_price;
   ?>
   <p> <?= $fetch_cart_items['name']; ?> <span>(<?= 'Rs'.$fetch_cart_items['price'].'/- x '. $fetch_cart_items['quantity']; ?>)</span> </p>
   <?php
    }
   }else{
      echo '<p class="empty">your cart is empty!</p>';
   }
   ?>
   <div class="grand-total">grand total : <span>Rs<?= $cart_grand_total; ?>/-</span></div>
</section>

<section class="checkout-orders">

   <form action="" method="POST">

      <h3>place your order</h3>

      <div class="flex">
         <div class="inputBox">
            <span>your name :</span>
            <input type="text" name="name" placeholder="enter your name" class="box" required>
         </div>
         <div class="inputBox">
            <span>your number :</span>
            <input type="number" name="number" placeholder="enter your number" class="box" required>
         </div>
         <div class="inputBox">
            <span>your email :</span>
            <input type="email" name="email" placeholder="enter your email" class="box" required>
         </div>
         <div class="inputBox">
            <span>payment method :</span>
            <select name="method" class="box" required>
               <option value="cash on delivery">cash on delivery</option>
               <option value="credit card">credit card</option>
               <option value="paytm">paytm</option>
               <option value="paypal">paypal</option>
            </select>
         </div>
         <div class="inputBox">
            <span>address line 01 :</span>
            <input type="text" name="flat" placeholder="e.g. flat number" class="box" required>
         </div>
         <div class="inputBox">
            <span>address line 02 :</span>
            <input type="text" name="street" placeholder="e.g. street name" class="box" required>
         </div>
         <div class="inputBox">
            <span>city :</span>
            <input type="text" name="city" placeholder="e.g. eranakulam" class="box" required>
         </div>
         <div class="inputBox">
            <span>state :</span>
            <input type="text" name="state" placeholder="e.g. kerala" class="box" required>
         </div>
         <div class="inputBox">
            <span>country :</span>
            <input type="text" name="country" placeholder="e.g. India" class="box" required>
         </div>
         <div class="inputBox">
            <span>pin code :</span>
            <input type="number" min="0" name="pin_code" placeholder="e.g. 123456" class="box" required>
         </div>
      </div>

      <input type="submit" name="order" class="btn <?= ($cart_grand_total > 1)?'':'disabled'; ?>" value="place order">

   </form>

</section>



<?php include 'footer.php'; ?>

<script src="js/script.js"></script>


</body>
</html>