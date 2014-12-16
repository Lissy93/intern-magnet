<?php

class Db
{

    private $_username = "root";
    private $_password = "";
    private $_hostname = "localhost";
    private $_database = "internmagnet";

    private $_conn;

     function connect()
    {
	//$this->_conn =  new PDO("mysql:host=".$this->_hostname.";dbname= ".$this->_database, $this->_username, $this->_password);
     $i=0;  

    } 


    function query_insert($sql)
    {
        $con=mysqli_connect($this->_hostname,$this->_username,$this->_password,$this->_database);
        if (mysqli_connect_errno()){  echo "Failed to connect to MySQL: " . mysqli_connect_error();  }
        mysqli_query($con,$sql);
        mysqli_close($con);
    }


    function query_get($sql) {		
				
        try {
		    $this->_conn = new PDO("mysql:host=localhost;dbname=".$this->_database, $this->_username, $this->_password);
        	//$r_do = $this->_conn->prepare($sql);
        	//$r_do->execute();
        	//$f2 = $r_do->fetch();
			$j = $this->_conn->query($sql);
			if(!$j){$j = array();}
		   $results = array();
	   	   foreach ($j as $row) {
				$results[]=$row;
        	}  
		   return $results;
		    }
         catch(PDOException $e) {
    		echo $e->getMessage();
		}
		return array(); 		
		
    }

}