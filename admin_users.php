<?php

@include 'config.php';

session_start();

$admin_id =  $_SESSION['admin_id'];

if(!isset($admin_id)){
    header('location:login.php');
};

 if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `users` WHERE id = '$delete_id'") or die('query failed');
    header('location:admin_users.php');
 };

 if(isset($_POST['update'])){
   $id = $_POST['update'];
   header('location:user_orders.php');
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>users</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="user-accounts">

   <h1 class="title">user accounts</h1>

   <div class="box-container">

      <?php
        $select_users = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
        if(mysqli_num_rows($select_users) > 0){
            while($fetch_users = mysqli_fetch_assoc($select_users)){
      ?>
      <div class="box" style="<?php if($fetch_users['id'] == $admin_id){ echo 'display:none'; }; ?>">
         <img src="uploaded_img/<?= $fetch_users['image']; ?>" alt="">
         <p> user id : <span><?= $fetch_users['id']; ?></span></p>
         <p> username : <span><?= $fetch_users['name']; ?></span></p>
         <p> email : <span><?= $fetch_users['email']; ?></span></p>
         <p> user type : <span style=" color:<?php if($fetch_users['user_type'] == 'admin'){ echo 'orange'; }; ?>"><?= $fetch_users['user_type']; ?></span></p>
         <a href="admin_users.php?delete=<?= $fetch_users['id']; ?>" onclick="return confirm('delete this user?');" class="delete-btn">delete</a>
         <a href="user_orders.php?update=<?= $fetch_users['id']; ?>" class="option-btn">order</a>

      </div>
      <?php
        }
      }
      ?>
   </div>

</section>





<script src="js/script.js"></script>
    
</body>
</html>