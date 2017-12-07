<?php
require_once('../Config/connect.php');
require_once('../Config/functions.php');
require_once('../Classes/User.php');
require_once('../Classes/Notification.php');

sec_session_start();

if(isset($_POST['login'],$_POST['password']))
{
	$user_login = htmlentities($_POST['login']);
    $user_pass = htmlentities($_POST['password']);

	$user = new User;

    if($user->login( connect(), $user_login, $user_pass))
	{

		$_SESSION['user'] = $user;
		header("Location: ../View/app.php");
	}
    else
	{
		$notification = new Notification;
		$notification->setMessage('Nie udało się zalogować');
		$notification->setWhere('index.php');
		$notification->send();
	}
}
else echo 'You are not authorized to access this page, please login.';
?>
