<?php
require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/User.php';
function checkCookie()
{
    $database = new Database();

    if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])){
        if(false === $database->connect()){
            return false;
        }
        $query = "SELECT * FROM user WHERE user.id = '".intval($_COOKIE['id'])."' LIMIT 1";
        if (false === $userData = $database->select($query)){
            return false;
        }

        if(($userData[0]['user_hash'] !== $_COOKIE['hash']) or ($userData[0]['id'] !== $_COOKIE['id'])) {
            setcookie("id", "", time() - 3600*24*30*12, "/");
            setcookie("hash", "", time() - 3600*24*30*12, "/");
            print "Хм, что-то не получилось";
            return false;
        } 
        return  new User($userData[0], $database);
    } else {
        return false;
    }
}
?>
