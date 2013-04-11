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
class User extends AbstractUser
{

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

    public function generateFlow($f1 = 0, $f2 = 0)
    {
        $flow = new Flow(
                $_GET['w0'], $_GET['z0'], $_GET['startTime'], $_GET['endTime'], $_GET['diffusion'], $_GET['Brightness'], $_GET['Neff'], $_GET['F'], $_GET['ka'], $_GET['kb']
        );
        if ($f1 == 'f1' && $f2 == 'f2') {
            if (false === $dataUrl = $flow->simu4()) {
                return false;
            }
        }
        if ($f1 == 'f1' && $f2 == 0) {
            if (false === $dataUrl = $flow->simu5()) {
                return false;
            }
        }
        if ($f1 == 0 && $f2 == 'f2') {
            if (false === $dataUrl = $flow->simu3()) {
                return false;
            }
        }
        if ($f1 == 0 && $f2 == 0) {
            if (false === $dataUrl = $flow->simu()) {
                return false;
            }
        }
        if (false === $this->insertFlowData($dataUrl)) {
            return false;
        }
        return true;
    }

    public function viewHistory()
    {
        $query = "SELECT * FROM flow_data WHERE flow_data.user_id ='$this->userId'";
        if (false === $respounce = $this->database->select($query)) {
            return false;
        }
        return $respounce;
    }

    public function generateDescription()
    {
        $d = array(
            w0 => $_GET['w0'],
            z0 => $_GET['z0'],
            startTime => $_GET['startTime'],
            endTime => $_GET['endTime'],
            Intensity => $_GET['diffusion'],
            Brightness => $_GET['Brightness'],
            Neff => $_GET['Neff'],
            F => $_GET['F']
        );
        return $d = json_encode($d);
    }

    public function insertFlowData($dataUrl)
    {
        $db = $this->getDatabase();
        $query = sprintf("INSERT INTO flow.flow_data (data_url, user_id, description_url   ) VALUES ('%s', %d, '%s'  )", $dataUrl, $this->userId, $this->generateDescription()
        );

        if (false === $db->unselect($query)) {
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
        $query = "UPDATE user SET user.user_hash= '" . $this->hash . "' WHERE user.id='" . $this->userId . "'";
        if (false == $this->database->update($query)) {
            return false;
        }
        return true;
    }

    protected function setCookie()
    {
        if (
                false === setcookie("id", $this->userId, time() + 60 * 60 * 24 * 30, '/') || false === setcookie("hash", $this->hash, time() + 60 * 60 * 24 * 30, '/')
        ) {
            return false;
        }
        return true;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getUserId()
    {
        return $this->usernId;
    }

    public function getDatabase()
    {
        return $this->database;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function authUser()
    {
        if (false === $this->updateHash()) {
            var_dump('cant update hash');
            return false;
        }
        if (false === $this->setCookie()) {
            var_dump('turn ON cookie');
            return false;
        }
        return true;
    }

}

?>
