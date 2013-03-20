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
    protected $userId;
    protected $username;
    protected $password; 
    protected $hash;
    protected $database;
    
    public function __construct($userData, $database) {
        $this->userId = $userData[0];
        $this->username = $userData[1];
        $this->password = $userData[2];
        $this->hash = $userData[3];
        $this->database=$database;
        
//        $this->authUser($database);
    }
    public static function setCookie($id, $hash) {
        parent::setCookie($id, $hash);
    }
//    public function getUsername(){
//        return $this->username;
//    }
//    
//    public function setUsername($username){
//        $this->username=$username;
//    }
//    
//    public function authUser($database, $username, $password)
//    {
//       
//        $query = "SELECT * FROM user WHERE user.username = '$username' AND user.password = '$password' ";
//        $id = $database->select($query);
//        $query = "UPDATE user SET user.user_hash='".AbstractUser::generateHash()."' WHERE user.id='".$id."'";
//        $database->update($query);
//        
//        session_start();
//        $_SESSION['username'] = $this->personalData['username'];
//        $_SESSION['password'] = $this->personalData['password'];
//    }
//    
//    protected function setPersonalData($username, $password)
//    {
//        $this->personalData = array(
//                                'username' => $username,
//                                'password' => $password
//                            );
//    }
//    
//    function getPersonalData() 
//    {
//        return $this->personalData;
//    }
}

?>
