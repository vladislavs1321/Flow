<?php
require_once 'AbstractUser.php';
require_once __DIR__ . '/../Flow.php';
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
        $this->database = $database;
    }
    
    public function generateFlow()
    {
//        $flow = new Flow(0.3e-6, 0.9e-6, 0, 0.1, 0.4,  0.0000000028, 100000, 0.01);
        $flow = new Flow(
                    $_GET['w0'] = 3e-6,
                    $_GET['z0'] = 3e-6,
                    $_GET['startTime'] = 0,
                    $_GET['endTime'] = 0.1,
                    $_GET['F'] = 0,
                    $_GET['diffusion']=2.8e-10,
                    $_GET['brightness']=100000,
                    $_GET['Neff']=0.01
                );
        $dataUrl = $flow->simu();
        if(false === $this->insertFlowData($dataUrl)){
            return false;
        }
        return true;
    }
    
    public function insertFlowData($dataUrl)
    {
        $db = $this->getDatabase();
        $query = sprintf("INSERT INTO flow.flow_data (data_url, user_id  ) VALUES ('%s', %d)",
            $dataUrl,
            $this->userId
        );
        if(false === $db->unselect($query)){
            var_dump($db->error);
            return false;
        };
        return true;
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
    
    public function getDatabase()
    {
        return $this->database;
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
