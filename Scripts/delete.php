<?php
require_once ('../Classes/Client.php');
require_once ('../Classes/Order.php');
require_once ('../Classes/Room.php');
require_once ('../Classes/Hotel.php');
require_once ('../Classes/User.php');
require_once ('../Classes/Notification.php');
require_once('../Config/connect.php');
require_once('../Config/functions.php');
$db = connect();
sec_session_start();

if(!isLogged( connect() ))
{
	clearSession();
	$notify = new Notification('Jesteś niezalogowany', 'index.php');
	$notify->send();
}
else
{
	if(isset($_GET['cid']))
	{
		$id = $_GET['cid'];
		$client  = new Client($id);
		if($client->remove($db))
		{
			$notify = new Notification('Pomyslnie usunięto klienta', 'View/app.php');
			$notify->send();
		}
		else {
			$notify = new Notification('Usuwanie nie powiodło się', 'View/app.php');
			$notify->send();
		}
	}
	if(isset($_GET['oid']))
	{
		$id = $_GET['oid'];
		$order  = new Order($id);
		if($order->remove($db))
		{
			$notify = new Notification('Pomyslnie usunięto rezerwacje', 'View/app.php');
			$notify->send();
		}
		else {
			$notify = new Notification('Usuwanie nie powiodło się', 'View/app.php');
			$notify->send();
		}
	}
	if(isset($_GET['rid']))
	{
		$id = $_GET['rid'];
		$room  = new Room($id);
		$room->remove($db);
	}
}
?>
