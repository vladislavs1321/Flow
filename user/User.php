<?php
session_start();
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
    
    protected $usredata = array();
    
    
    public function __construct() {
        $this->usredata['username']=$_POST['username'];
        $this->usredata['password']=$_POST['password'];
       
    }
}
$user= new User;
var_dump($user);
?>
