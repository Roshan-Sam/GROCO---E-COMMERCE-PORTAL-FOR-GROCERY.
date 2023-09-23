<?php

@include 'config.php';

session_start();

$user_id =  $_SESSION['user_id'];

if(!isset($user_id)){
    header('location:login.php');
};

if(isset($_POST['update_profile'])){

    $filter_name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $name = mysqli_real_escape_string($conn, $filter_name);
    $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $email = mysqli_real_escape_string($conn, $filter_email);

    mysqli_query($conn, "UPDATE `users` SET name = '$name', email = '$email' WHERE id = '$user_id'") or die('query failed');

    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/'.$image;
    $old_image = $_POST['old_image'];

    if(!empty($image)){
        if($image_size > 2000000){
            $message[] = 'image size is too large!';
        }else{
            mysqli_query($conn, "UPDATE `users` SET image = '$image' WHERE id = '$user_id'") or die('query failed');
            move_uploaded_file($image_tmp_name, $image_folder);
            unlink('uploaded_img/'.$old_image);
            $message[] = 'image updated successfully!';
            };
        };
    

    $old_pass = $_POST['old_pass'];
    $update_pass = filter_var($_POST['update_pass'], FILTER_SANITIZE_STRING);
    $update_pass = mysqli_real_escape_string($conn, md5($update_pass));
    $new_pass = filter_var($_POST['new_pass'], FILTER_SANITIZE_STRING);
    $new_pass = mysqli_real_escape_string($conn, md5($new_pass));
    $confirm_pass = filter_var($_POST['confirm_pass'], FILTER_SANITIZE_STRING);
    $confirm_pass = mysqli_real_escape_string($conn, md5($confirm_pass));


    if(!empty($update_pass) AND !empty($new_pass) AND !empty($confirm_pass)){
        if($update_pass != $old_pass){
            $message[] = 'old password not matched!';
        }elseif($new_pass != $confirm_pass){
            $message[] = 'confirm password not matched!';
        }else{
            mysqli_query($conn, "UPDATE `users` SET password = '$confirm_pass' WHERE id = '$user_id'") or die('query failed');
            $message[] = 'password updated successfully!';
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
    <title>update user profile</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <link rel="stylesheet" href="css/component.css">

</head>
<body>

<?php include 'header.php'; ?>

<section class="update-profile">

    <h1 class="title">update profile</h1>

    <form action="" method="POST" enctype="multipart/form-data">
        <img src="uploaded_img/<?= $fetch_profile['image']; ?>" alt="">
        <div class="flex">
            <div class="inputBox">
                <span>username :</span>
                <input type="text" name="name" value="<?= $fetch_profile['name']; ?>" placeholder="update username" required class="box">
                <span>email :</span>
                <input type="email" name="email" value="<?= $fetch_profile['email']; ?>" placeholder="update username" required class="box">
                <span>update pic :</span>
                <input type="file" name="image" accept="image/jpg, image/jpeg, image/png"  class="box">
                <input type="hidden" name="old_image" value="<?= $fetch_profile['image']; ?>">
            </div>
            <div class="inputBox">
                <input type="hidden" name="old_pass" value="<?= $fetch_profile['password']; ?>">
                <span>old password :</span>
                <input type="password" name="update_pass" placeholder="enter previous password"  class="box">
                <span>new password :</span>
                <input type="password" name="new_pass" placeholder="enter new password"  class="box">
                <span>confirm password :</span>
                <input type="password" name="confirm_pass" placeholder="confirm new password"  class="box">
            </div> 
        </div>
        <div class="flex-btn">
            <input type="submit" class="btn" value="update profile" name="update_profile">
            <a href="home.php" class="option-btn">go back</a>
        </div>
    </form>

</section>



<script src="js/script.js"></script>
    
</body>
</html>