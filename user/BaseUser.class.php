<?php

require_once "../database/db.class.php";
require_once "User.class.php";
require_once "Boss.class.php";

abstract class BaseUser
{
    protected $personalData = array();  
    protected $db;
    protected $isBoss;
    
    static $error = null;
    
    
    public static function createUser( $login, $password)
    {   
        if(false === self::checkIsFormCorrect($login,$password) ){
            return false;
        }
        
        $database = self::connectToDb();
        if(false === $database){
            return false;
        }
        
        if(false === self::ifUserExist($login, $password, $database)) {
            return false;
        }
        
        if(false === self::isBoss($login, $password, $database) ){
            return new User($login, $password, $database);
        } else {
            return new Boss($login, $password, $database);
        }
    }
    
    public static function isBoss($login, $password, $database){
        $query = sprintf(
            "SELECT is_boss FROM user WHERE user.login = '%s' AND user.password = '%s'",
            mysql_escape_string($login),
            mysql_escape_string($password)
        );
        
        $res = $database->select($query);
       
        if ($res[0]['is_boss'] == 0){
            return false;
        } else {
            return true;
        }
        
    }
    public static function regUser($login, $password, $confirmPassword){
        if(false === self::checkIsFormCorrect($login, $password) || false == self::checkPasswordConfirm($password, $confirmPassword)){
            return false;
        }
        
        $database = self::connectToDb();
        if(false === $database){
            return false;
        }
        
        if(true === self::ifUserExist($login, $password, $database)){
            self::$error = array('code' => 7, 'message' =>"user with this login existed");
            return false;
        } else{
            if(self::$error['code'] == 5){
                self::$error = null;
                //Логин свободен - можем создавать нового пользователя
                if(false === self::addNewUserInDatabase($login, $password, $database)){
                    return false;
                }
                
                //register OK
                return new User($login, $password, $database);
            } else {
                self::$error = array('code' => 7, 'message' =>"user with this login existed");
                return false;
            }
        }
    }
    public static function checkPasswordConfirm($password, $confirmPassword){
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
    
    public static function addNewUserInDatabase($login, $password, $database){
        $query = sprintf(
            "INSERT INTO user (login , password) values ('%s' , '%s')",
            mysql_escape_string($login),
            mysql_escape_string($password)
        );
        if(false === $database->unselect($query) ){
            self::$error = array('code' => 8, 'message' =>"CANT REGISTER ");
            return false;
        }
        
        return true;
    
    }
    
    public static function ifUserExist($login, $password, $database){   
        $query = "SELECT * FROM user WHERE user.login = '$login' AND user.password = '$password' ";
        if( !$database->select($query)) {
            if( !$database->select("SELECT * FROM user WHERE user.login = '$login' ")) {  
                self::$error = array('code' => 5, 'message' =>"incorrect login");
                return false;
            } else {
                self::$error = array('code' => 6, 'message' =>"error password");
                return false;
            }
        } 
        return true;        
    }
    
    protected static function checkIsFormCorrect($login, $password){
        if ($login == '' and $password == '') {
            self::$error = array('code' => 1, 'message' =>"enter login and password, please");
            return false;
        }
        if ($login == '') 
        {      
            self::$error = array('code' => 2, 'message' =>"enter login, please");
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
    
    public static function getAllKillers(Database $database){
        $query = "SELECT * FROM killer";
        return $database->select($query);
    }
    
    public static function checkSessionData()
    {
        session_start();
        if (null !== $_SESSION['login'] && null !== $_SESSION['password']) {
            $sessionData = array(
                'login' => $_SESSION['login'],
                'password' => $_SESSION['password'],
                'is_boss' => $_SESSION['is_boss']
            );
            
            return $sessionData;
        } else {
            self::$error = array ('code' => 11, 'message' => "Your time is over!" );
            return false;
        }
    }
    
    
    
    
    // *******************************************************************************************
    
    public function setSession()
    {
        session_start();
        $_SESSION['login'] = $this->personalData['login'];
        $_SESSION['password'] = $this->personalData['password'];
        $_SESSION['is_boss'] = $this->getIsBoss();
    }
    
    protected function setPersonalData($login, $password)
    {
        $this->personalData = array(
                                'login' => $login,
                                'password' => $password
                            );
    }
    
    function getPersonalData() {
        return $this->personalData;
    }
    
    function setIsBoss($isBoss) {
        $this->isBoss = $isBoss;
    }
    
    function getIsBoss(){
       return $this->isBoss;
   }
}