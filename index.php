<?php
function autoload($class) {
	require '/classes/'.$class.'.class.php';
}
spl_autoload_register('autoload');

$user = new User('tima25@tima', 'password');

$db = new DbUser();

//$db->addUser($user);

$db->existUser($user);

//$db->getNameUser($user);

//$db->uploadUser($user);

var_dump($user);