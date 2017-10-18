<?php
define('DB_HOST', 'mysql:dbname=users;host=localhost'); 
define('DB_LOGIN', 'user'); 
define('DB_PASS', 't12qm'); 
define('DB_NAME', 'users'); 
class DbUser {
	public $db;
	function __construct() {
		try {
			$this->db = new PDO( DB_HOST, DB_LOGIN, DB_PASS );
			$this->db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
	function __destruct() {
		unset($this->db);
	}
	public function createDb() {
		$sql = "DROP TABLE IF EXISTS `info_users`;
				DROP TABLE IF EXISTS `name_users`;
				DROP TABLE IF EXISTS `login_users`;
				CREATE TABLE IF NOT EXISTS `users`.`login_users` (
				  `idlogin_users` INT NOT NULL AUTO_INCREMENT,
				  `email` VARCHAR(255) NOT NULL,
				  `password` VARCHAR(255) NOT NULL,
				  PRIMARY KEY (`idlogin_users`),
				  UNIQUE KEY (`email`)
				  )
				ENGINE = InnoDB;

				CREATE TABLE IF NOT EXISTS `users`.`name_users` (
				  `idname_users` INT NOT NULL AUTO_INCREMENT,
				  `idlogin_users` INT NOT NULL,
				  `firstname` VARCHAR(45) NULL,
				  `lastname` VARCHAR(45) NULL,
				  PRIMARY KEY (`idname_users`),
				  UNIQUE KEY (`idlogin_users`),
				  INDEX `fk_name_users_login_users_idx` (`idlogin_users` ASC),
				  CONSTRAINT `fk_name_users_login_users`
					FOREIGN KEY (`idlogin_users`)
					REFERENCES `users`.`login_users` (`idlogin_users`))
				ENGINE = InnoDB;

				CREATE TABLE IF NOT EXISTS `users`.`info_users` (
				  `idinfo_users` INT NOT NULL AUTO_INCREMENT,
				  `idlogin_users` INT NOT NULL,
				  `work` VARCHAR(100) NULL,
				  `status` VARCHAR(45) NULL,
				  `children` INT NULL,
				  PRIMARY KEY (`idinfo_users`),
				  UNIQUE KEY (`idlogin_users`),
				  INDEX `fk_more_users_login_users1_idx` (`idlogin_users` ASC),
				  CONSTRAINT `fk_more_users_login_users1`
					FOREIGN KEY (`idlogin_users`)
					REFERENCES `users`.`login_users` (`idlogin_users`))
				ENGINE = InnoDB;";
	
		$this->db->exec($sql);
	}
	public function addUser(User $user) {
		$email = $this->db->quote($user->email);
		
		$password = password_hash( $user->password, PASSWORD_BCRYPT );//PASSWORD_DEFAULT );
		$password = $this->db->quote($password);
		
		$sql = "INSERT INTO login_users (email, password) VALUES ($email, $password)";
		$this->db->exec($sql);
		
		if($this->existUser($user)) {
			$id = $user->id;
		}
		
		if($user->firstname) {
			$firstname = $this->db->quote($user->firstname);
		} else {
			$firstname = '';
		}
		
		if($user->lastname) {
			$lastname = $this->db->quote($user->lastname);
		} else {
			$lastname = '';	
		}
		
		$sql = "INSERT INTO name_users (idlogin_users, firstname, lastname) 
				VALUES ($id, $firstname, $lastname)";
		var_dump($sql);
		$this->db->exec($sql);
		
	}
	
	public function uploadUser(User $user){
		$loadUser = $this->getNameUser($user);
		var_dump($loadUser);
		if($loadUser->email == $user->email) {
			echo "Hello";
		}
	}
	
	public function existUser(User $user){
		
		$email = $this->db->quote($user->email);
		
		$sql = "SELECT idlogin_users, password 
		FROM login_users 
		WHERE login_users.email = ".$email;
		
		$res = $this->db->query($sql);
		
		if($data = $res->fetch(PDO::FETCH_ASSOC)) {
			
			if(password_verify($user->password, $data['password'])) {
				$user->id = $data['idlogin_users'];
			} else {
				$user = false;
			}
			
		}
		//return $user;
	}
	
	public function getNameUser(User $user){
		
		if(!$user->id) {
			$user = $this->existUser($user);
		}
		
		if(!$user) {
			return false;
		}
		
		$sql = "SELECT firstname, lastname FROM name_users WHERE idlogin_users = ".$user->id;
		
		$fullname = $this->db->query($sql);
		
		$fullname = $fullname->fetch(PDO::FETCH_ASSOC);
		
		$user->firstname = $fullname['firstname'];
		$user->lastname = $fullname['lastname'];
	}
}
