<?php
require_once 'AbstractUser.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author vladislav
 */
class User extends AbstractUser {
    protected $username;
    protected $password; 
    protected $database;
    
    public function __construct($username,$password,$database) {
        $this->username=$username;
        $this->password=$password;
        $this->database=$database;
    }
    public function getUsername(){
        return $this->username;
    }
    public function setUsername($username){
        $this->username=$username;
    }
    
   
}

?>
