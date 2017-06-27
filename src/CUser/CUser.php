<?php

class CUser {
	private $db;
	
	private $id;
	private $name;
	private $type;
	private $text;
	private $password;
	private $salt;
	
	public function __construct($db) {
		$this->db = $db;
		if ($this->IsAuthenticated()) {
			$this->id = $_SESSION['user']->id;
			$this->name = $_SESSION['user']->name;
			$this->type = $_SESSION['user']->type;
			$this->text = $_SESSION['user']->text;
			$this->password = $_SESSION['user']->password;
			$this->salt = $_SESSION['user']->salt;
		}
	}
	
	public function Login($user, $password) {
		$sql = "SELECT * FROM kmom07_Users WHERE name = ? AND password = md5(concat(?, salt));";
		$params = array($user, $password);
		$res = $this->db->ExecuteSelectQueryAndFetchAll($sql, $params);
		if(isset($res[0])) {
			$_SESSION['user'] = $res[0];
			header('Location: user_profile.php');
		}
	}
	
	public function Logout() {
		$this->db = null;
		$this->id = null;
		$this->name = null;
		$this->type = null;
		$this->text = null;
		$this->password = null;
		$this->salt = null;
		
		unset($_SESSION['user']);
	}
	
	public function IsAuthenticated() {
		$name = isset($_SESSION['user']) ? $_SESSION['user']->name : null;
		$result;
		if($name) {
			$result = true;
		} else {
			$result = false;
		}
		return $result;
	}
	
	public function IsAdmin() {
		$result = false;
		if ($this->IsAuthenticated() && $this->type == 'admin') {
			$result = true;
		}
		return $result;
	}
	
	public function getType() {
		return $this->type;
	}
	public function getName() {
		return $this->name;
	}
	public function getText() {
		return $this->text;
	}
}
