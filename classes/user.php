<?php
class User {
	public $login, $password, $firstname, $lastname, $set;
	function __construct($login, $password, $firstname="", $lastname="") {
		$this->login = $this->validate('login', $login);
		$this->password = $this->validate('password', $password);
		$this->firstname = $this->validate('firstname', $firstname);
		$this->lastname = $this->validate('lastname', $lastname);
		
		if(!$this->login || !$this->password) {
			echo 'Не введен логин или пароль';
		}
	}	
	
	function __destruct(){
	}
	
	function __get($name){
		echo "Для получения данных User используйте соответствующие фунции!";
	}
	
	function __set($name, $value){
		$this->set[$name] = $value;
		print_r($this->set);
	}
	
	private function validate($type, $data){
			switch ($type) {
				case 'login' :
					if(!$data) break;
					$data = trim(strip_tags($data));
					if(strlen($data) >= 3) {
						return $data;
					} else {
						return false;
					}
					break;
				case 'password' : 
					if(!$data) break;
					$data = trim(strip_tags($data));
					if(strlen($data) >= 5) {
						return $data;
					} else {
						return false;
					}
					break;
				case 'firstname' : 
					return trim(strip_tags($data));
					break;
				case 'lastname' : 
					return trim(strip_tags($data));
					break;
				default : 
					try {
						throw new Exception('Не определен тип валидации');
					} catch (Exception $e) {
						echo $e->getMessage()." In file: ".$e->getFile()." On line: ".$e->getLine(), "\n";
					}
			}  
		}
	

}
/*$user = new User('Tima', 'testiruem', 'Тимофей', 'Шевченко');
$user->getData('full');*/