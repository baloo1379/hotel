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
	$first_name = htmlentities( $_POST['first'], ENT_QUOTES, "UTF-8" );
	$last_name = htmlentities( $_POST['last'], ENT_QUOTES, "UTF-8" );
	$tel = htmlentities( $_POST['tel'], ENT_QUOTES, "UTF-8" );
	$email = htmlentities( $_POST['email'], ENT_QUOTES, "UTF-8" );

	$client = new Client( 0, $first_name, $last_name, $tel, $email );
	if($client->toDB($db))
	{
		$notify = new Notification('Pomyślnie dodano klienta', 'View/app.php');
		$notify->send();
	}
	else {
		$notify = new Notification('Dodawanie nie powiodło się', 'View/app.php');
		$notify->send();
	}
}
?>
