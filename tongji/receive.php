<?php
   $user_name =$_POST['user_name'];
   //$user_password = $_POST['user_password'];
//if($user_name =='111'&&$user_password =='111'){
if($user_name =='admin'){
    session_start();
    $_SESSION["user_name"]="admin";//把用户名写到SESSIOn

header("Location: index.php ");
}else{
echo "<script>alert('密码错误');window.location.href='login.html';</script>";
}
?>