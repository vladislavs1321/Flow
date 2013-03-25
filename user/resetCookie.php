<?php
    if( isset($_COOKIE['id']) || isset($_COOKIE['hash'])){
        setcookie('id', FALSE,NULL,"/");
        setcookie('hash', FALSE,NULL,"/" );
    }
    header("location: http://flow.local");
?>
