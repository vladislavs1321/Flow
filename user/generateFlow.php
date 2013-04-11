<?php
require_once __DIR__ . '/checkCookie.php';

$user = checkCookie();
if(is_object($user)){
    if(true === $user->generateFlow($_GET['f1'],$_GET['f2'])){
        $responce=array(
            "success" => true
        );
        echo(json_encode($responce));
    } else{
        $responce=array(
            "success" => false
        );
        echo(json_encode($responce));        
    }
}else{
    var_dump("AHTUNG! NEED AUTH");exit;
}
?>
