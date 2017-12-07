<?php
require_once('functions.php');

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASS', '');
define('DB_NAME', 'hotel');

function connect()
{
	try{
		$db = new mysqli(DB_SERVER,DB_USERNAME,DB_PASS,DB_NAME);
		if($db->connect_errno)
		{
			throw Exception ($db->connect_errno,$db->connect_error);
			die;
		}
		$db->query('SET NAMES utf8');
		return $db;
	}
	catch (Exception $e)
	{
		echo 'Wystąpił wyjątek< mn> nr '.$e->getCode().', jego komunikat to: '.$e->getMessage();
	}
}

?>
