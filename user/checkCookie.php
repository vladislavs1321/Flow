<?php
require_once '../database/Database.php';
$database = new Database();

if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])){
    if(false === $database->connect()){
        return false;
    }
    $query = mysql_query("SELECT * FROM users WHERE user.id = '".intval($_COOKIE['id'])."' LIMIT 1");
    $userData = $database->select($query);
    if(($userData['user_hash'] !== $_COOKIE['hash']) or ($userData['user_id'] !== $_COOKIE['id'])) {
        setcookie("id", "", time() - 3600*24*30*12, "/");
        setcookie("hash", "", time() - 3600*24*30*12, "/");
        print "Хм, что-то не получилось";
        return false;
    } 
    return true;
} else {
    print "Включите куки";
}

?>
