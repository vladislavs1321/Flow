<?php
require_once __DIR__ . '/checkCookie.php';

$user = checkCookie();
if(is_object($user)){
    if(empty($_GET)) {
        $responce = array(
            "success" => true
        );
        echo(json_encode($responce));
    } else {
        if(true === $user->generateFlow()){
            $responce = array(
                "success" => true
            );
            echo(json_encode($responce));
        } else{
            $responce = array(
                "success" => false
            );
            echo(json_encode($responce));        
        }
    }
}else{
    $responce = array(
            "success" => false
        );
    echo(json_encode($responce));
//    var_dump("AHTUNG! NEED AUTH");exit;
}
?>
