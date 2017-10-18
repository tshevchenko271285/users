<?php

class User {
	private $data = []; //$id, $email, $password, $firstname, $lastname;
	

	function __construct( $email, $pass, $firstname = '', $lastname = '' ) {
		$this->id = NULL;
		$this->email = $this->validDate( $email, 'email' );
		$this->password = $this->validDate( $pass, 'password');
		$this->firstname = $this->validDate( $firstname );
		$this->lastname = $this->validDate( $lastname );
		/*
		$this->data['id'] = NULL;
		$this->data['email'] = $this->validDate( $email, 'email' );
		$this->data['password'] = $this->validDate( $pass, 'password');
		$this->data['firstname'] = $this->validDate( $firstname );
		$this->data['lastname'] = $this->validDate( $lastname );*/
	}
	
	function __get($name){
		if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
	}
	
	function __set ($name, $value) {
		
		switch($name) {
			case 'id' :
				$this->id = $value;
				break;
			case 'email' :
				$this->email = $value;
				break;
			case 'password' :
				$this->password = $value;
				break;
			case 'firstname' :
				$this->firstname = $value;
				break;
			case 'lastname' :
				$this->lastname = $value;
				break;
			default : 
				$this->data[$name] = $value;
		}
	}
	/* Валидация данных */
	private function validDate( $data, $type='' ) {
		
		$data = strip_tags(trim($data));
			
		switch($type) {

			case "email" :
				if(preg_match("/@/", $data)) {
					return $data;
				} else {
					return false;
				}
			break;

			case "password" :
				if(strlen($data) >= 6) {
					return $data;
				} else {
					return false;
				}	
			break;

			default : 
				if(!$data) return '';
				return $data;
		}
	}

}