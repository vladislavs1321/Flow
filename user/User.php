<?php
require_once 'AbstractUser.php';
require_once '../Flow.php';
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
    protected $userId;
    protected $username;
    protected $password; 
    protected $hash;
    protected $database;
    
    public function __construct(array $userData, $database)
    {
        $this->userId = $userData['id'];
        $this->username = $userData['username'];
        $this->password = $userData['password'];
        $this->hash = $this->generateHash();
        $this->database=$database;
    }
    
    public function generateFlow(){
         
    }

    protected function generateHash()
    {
        return md5($this->generateCode(10));
    }
    
    protected function updateHash()
    {
        $query = "UPDATE user SET user.user_hash= '".$this->hash."' WHERE user.id='".$this->userId."'";
        if(false == $this->database->update($query)){
            return false;
        }
        return true;
    }

    protected function setCookie()
    {
        if ( 
            false === setcookie("id", $this->userId, time()+60*60*24*30, '/') 
            || false === setcookie("hash", $this->hash, time()+60*60*24*30, '/')
        ) {
            return false;
        }
        return true;
    }
    
    public function getUsername(){
        return $this->username;
    }
    
    public function setUsername($username){
        $this->username=$username;
    }
    
    public function authUser()
    {
        if(false === $this->updateHash()){
            var_dump('cant update hash');
            return false;
        }
        if(false === $this->setCookie()){
            var_dump('turn ON cookie');
            return false;
        }
        return true;
    }
    
}

?>
