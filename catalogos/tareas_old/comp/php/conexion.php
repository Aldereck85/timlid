<?php

class conectar{
	private  $_cn;
	private $_dns; 
	private $_username; 
	private $_passwd; 
    
	public function __construct() { 
		$this->_dns = "mysql:host=localhost;dbname=taskmanager2"; 
		$this->_username = "root";
		$this->_passwd = "";

		try{	
			$params = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
			$this->_cn = new PDO($this->_dns, $this->_username, $this->_passwd, $params);
			$this->_cn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
		}catch( PDOException $e){
			echo "Error en la conexión";
		}
	}

	public function getDb(){
		return $this->_cn;
	}
}