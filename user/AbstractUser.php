<?php
session_start();
require_once "../database/Database.php";
require_once "./User.php";

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author vladislav
 */
abstract class AbstractUser 
{
    
    protected $personalData = array();  
    
    static $error = null;
    static $id = null;
    
    public static function createUser($username, $password)
    {   
        if(false === self::checkIsFormCorrect($username,$password) ){
            return false;
        }
        
        $database = self::connectToDb();
        if(false === $database){
            return false;
        }
        $userData = self::getUserDataFromDatabase($username, $password, $database);
        if(false === $userData){
            return false;
        } else {
//            self::setCookie(self::$id, self::generateHash());  
            return new User($userData, $database);
        }
       
    }
    
    public static function getUserDataFromDatabase($username, $password, $database){
        $password = self::encryption($password);
        $query = "SELECT * FROM user WHERE user.username = '$username' AND user.password = '$password' ";
      
        if( !$userData = $database->select($query)) {
            if( !$database->select("SELECT * FROM user WHERE user.username = '$username' ")) {  
                self::$error = array('code' => 5, 'message' =>"incorrect username");
                return false;
            } else {
                self::$error = array('code' => 6, 'message' =>"error password");
                return false;
            }
        } else {
            return $userData[0];        
        }
    }

    public static function regUser($username, $password, $confirmPassword)
    {
        if(false === self::checkIsFormCorrect($username, $password) || false == self::checkPasswordConfirm($password, $confirmPassword)){
            return false;
        }
        
        $database = self::connectToDb();
        if(false === $database){
            return false;
        }
        
        if(true === self::ifUserExist($username, $password, $database)){
            self::$error = array('code' => 7, 'message' =>"user with this username existed");
            return false;
        } else{
            if(self::$error['code'] == 5){
                self::$error = null;
                $password = self::encryption($password);
             
                //Логин свободен - можем создавать нового пользователя
                if(false === self::createNewUserDataInDatabase($username, $password, $database)){
                    return false;
                }
                //register OK
                return new User($username, $password, $database);
            } else {
                self::$error = array('code' => 7, 'message' =>"user with this username existed");
                return false;
            }
        }
    }

    public static function generateCode($length=6)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;  
        while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0,$clen)];  
        }
        return $code;
    }
    
    public static function encryption($password)
    {
        return md5(md5(trim($password)));
    }

    public static function checkPasswordConfirm($password, $confirmPassword)
    {
        if ($confirmPassword == '') 
        {      
            self::$error = array('code' => 9, 'message' =>"enter password confirm, please");
            return false;        
        }
        if($password !== $confirmPassword){
            self::$error = array('code' => 10, 'message' =>"ahTUNG,Passwörter stimmen nicht überein");
            return false;     
        }
        return true;        
    }
    
    public static function createNewUserDataInDatabase($username, $password, $database)
    {
        $query = sprintf(
            "INSERT INTO user (username , password) values ('%s' , '%s')",
            mysql_escape_string($username),
            mysql_escape_string($password)
        );
        if(false === $database->unselect($query) ){
            self::$error = array('code' => 8, 'message' =>"CANT REGISTER ");
            return false;
        }
        
        return true;
    
    }
    
    static public function ifUserExist($username, $password, $database)
    {
        $password = self::encryption($password);
        $query = "SELECT * FROM user WHERE user.username = '$username' AND user.password = '$password' ";
      
        if( !$database->select($query)) {
            if( !$database->select("SELECT * FROM user WHERE user.username = '$username' ")) {  
                self::$error = array('code' => 5, 'message' =>"incorrect username");
                return false;
            } else {
                self::$error = array('code' => 6, 'message' =>"error password");
                return false;
            }
        }
        return true;        
    }
    
    protected static function checkIsFormCorrect($username, $password)
    {
        if ($username == '' and $password == '') {
            self::$error = array('code' => 1, 'message' =>"enter username and password, please");
            return false;
        }
        if ($username == '') 
        {      
            self::$error = array('code' => 2, 'message' =>"enter username, please");
            return false;        
        }
        if( $password == '') 
        {       
            self::$error = array('code' => 3, 'message' =>"enter password, please");
            return false;
        }
        return true;
    }  
    
    public static function connectToDb(){
        $database = new Database(); 
        if(false === $database->connect()){        
            self::$error = array ('code' => 4, 'message' => "oops, try later" );
            return false;
        }
        return $database;
    }
    
    public static function checkSessionData()
    {
        session_start();
        if (null !== $_SESSION['username'] && null !== $_SESSION['password']) {
            $sessionData = array(
                'username' => $_SESSION['username'],
                'password' => $_SESSION['password']
            );
            
            return $sessionData;
        } else {
            self::$error = array ('code' => 11, 'message' => "Your time is over!" );
            return false;
        }
    }
    
    
    
    
    // *******************************************************************************************
    
    public static function generateHash()
    {
        return md5(self::generateCode(10));
    }
    
    public static function setCookie($id,$hash)
    {
        setcookie("id", $id, time()+60*60*24*30);
        setcookie("hash", $hash, time()+60*60*24*30);
    }
}

?>
