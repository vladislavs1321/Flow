<?php
require_once './AbstractUser.php';

$user = AbstractUser::regUser($_POST['username'],$_POST['password'],$_POST['confirmPassword']);
if(false !== $user){
    $responce = array(
        "success" => true,
        "username" => $user->getUsername()
    );
    echo (json_encode($responce));
    
}else{
    $responce = array(
        "success" => false,
        "error" => AbstractUser::$error
    );
    echo (json_encode($responce));
}
?>
