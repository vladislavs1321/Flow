<?php

class Database
{
    private $connection; 
    private $hostname      = 'localhost';
    private $username      = 'root';
    private $password      = '';
    public $databaseName  = 'flow';
    public $error;
    
    function connect()
    {    
        $this->connection = mysql_connect($this->hostname, $this->username, $this->password);
        
        if(!$this->connection) {
            $this->error = mysql_error();
            return false;
        }
        
        if(!mysql_select_db($this->databaseName, $this->connection)) {
            $this->error = mysql_error();
            return false;
        }
        
        if(!mysql_set_charset('utf8', $this->connection)) {
            $this->error = mysql_error();
            return false;
        }
        
        return true;
    }

    function select($sql)
    {
        if(!$resault = mysql_query($sql, $this->connection)) {
            $this->error = mysql_error();
            return false;
        }
        if(mysql_num_rows($resault) == 0) {
            return null;
        }
        while ($row = mysql_fetch_assoc($resault))
		{
			$respounce[] = $row;
		}
        
        return $respounce;
    }
    
    function unselect($sql)
    {
        if(!$resault = mysql_query($sql, $this->connection)) {
            $this->error = mysql_error();
            return false;
        }        
        return true;
    } 
    
    function update($sql)
    {
        if(!$resault = mysql_query($sql, $this->connection)) {
            $this->error = mysql_error();
            return false;
        }        
        return mysql_affected_rows();
    } 
    
}