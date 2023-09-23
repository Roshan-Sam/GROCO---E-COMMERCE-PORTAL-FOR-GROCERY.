<?php

@include 'config.php';

session_start();

$user_id =  $_SESSION['user_id'];

if(!isset($user_id)){
    header('location:login.php');
};

if(isset($_POST['send'])){

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_STRING);
    $msg = $_POST['msg'];
    $msg = filter_var($msg, FILTER_SANITIZE_STRING);
 
    $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg'") or die('query failed');

    if(mysqli_num_rows($select_message) > 0){
        $message[] = 'already sent message!';
    }else{
        mysqli_query($conn, "INSERT INTO `message`(user_id, name, email, number, message) VALUES('$user_id', '$name', '$email', '$number', '$msg')") or die('query failed');
        $message[] = 'sent message successfully!';
    }
    
 }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>contact</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'header.php'; ?>

<section class="contact">

   <h1 class="title">get in touch</h1>

   <form action="" method="POST">
      <input type="text" name="name" class="box" required placeholder="enter your name">
      <input type="email" name="email" class="box" required placeholder="enter your email">
      <input type="number" name="number" min="0" class="box" required placeholder="enter your number">
      <textarea name="msg" class="box" required placeholder="enter your message" cols="30" rows="10"></textarea>
      <input type="submit" value="send message" class="btn" name="send">
   </form>

</section>



<?php include 'footer.php'; ?>

<script src="js/script.js"></script>


</body>
</html>