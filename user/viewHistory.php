<?php
require_once __DIR__.'/checkCookie.php';
//require_once './User.php';
$user = checkCookie(); 
if(is_object($user)){
    if( true == $responce = $user->viewHistory()){
        
        echo(json_encode($responce));
    }else{
        $responce = array(
            "success" => false,
        );
        echo (json_encode($responce));
//        var_dump("fail");
    }
}else{
    $responce = array(
            "success" => false,
    );
    echo (json_encode($responce));
//    var_dump("need AUTH");
}
?>
