<?php
session_start();
require_once "../Database.php";
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author vladislav
 */
 class User {
    
    protected $username;
    protected $password;
    //protected $database;
    static $error;
    
    public function __construct() {
        $this->username=$_POST['username'];
        $this->password=$_POST['password'];
    }
    
    public static function createUser( $username, $password){
        
        if(false === checkIsFormCorrect($username,$password) ){
            return false;
        }
        
        $database = self::connectToDb();
        if(false === $database){
            return false;
        }
        
        if(false === self::ifUserExist($username, $password, $database)) {
            return false;
        }
        
        return new User($username, $password, $database);
    }
    
    
    protected static function checkIsFormCorrect($username, $password){
        if ($username == '' and $password == '') {
            self::$error = array('code' => 1, 'message' =>"enter login and password, please");
            return false;
        }
        if ($username == '') 
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
    
    
    
    public static function ifUserExist($username, $password, $database){   
        $query = "SELECT * FROM user WHERE user.username = '$username' AND user.password = '$password' ";
        if( !$database->select($query)) {
            if( !$database->select("SELECT * FROM user WHERE user.username = '$username' ")) {  
                $error = array('code' => 5, 'message' =>"incorrect login");
                return false;
            } else {
                $error = array('code' => 6, 'message' =>"error password");
                return false;
            }
        } 
        return true;        
    }
    
    public static function connectToDb(){
        $database = new Database(); 
        if(false === $database->connect()){        
            self::$error = array ('code' => 4, 'message' => "oops, try later, cannot connect to database" );
            return false;
        }
        return $database;
    }
}

$user = new User();
var_dump($user);
?>
