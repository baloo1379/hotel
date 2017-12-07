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
else {
	$room_id = $_POST['room'];
	$client_id = $_POST['name'];
	$s_d = $_POST['start_date'];
	$e_d = $_POST['end_date'];

	$order = new Order( 0, $room_id, $client_id, $_SESSION['user_id'], $s_d, $e_d );

	if ($order->toDB( $db )) {
		$notify = new Notification( 'Pomyślnie dodano rezerwacje', 'View/app.php' );
		$notify->send();
	} else {
		$notify = new Notification( 'Dodawanie nie powiodło się', 'View/app.php' );
		$notify->send();
	}
}
?>
