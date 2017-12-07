<?php
/**
 * Created by PhpStorm.
 * User: barte
 * Date: 05.12.2017
 * Time: 13:34
 */

define('ROOT_PATH', 'http://'.$_SERVER["SERVER_NAME"].'/hotel/');

require_once ('../Classes/Client.php');
require_once ('../Classes/Order.php');
require_once ('../Classes/Room.php');
require_once ('../Classes/Hotel.php');
require_once ('../Classes/User.php');
require_once ('../Classes/Notification.php');
require_once ('connect.php');
require_once ('functions.php');

sec_session_start();

$hotel = setHotel( connect() );

if(isset($_GET['message'])) $notification = new Notification($_GET['message']);
else $notification = new Notification('');

if(!isLogged( connect() ))
{
	clearSession();
	$notify = new Notification('Jesteś niezalogowany', 'index.php');
	$notify->send();
}
else $user=$_SESSION['user'];

?>
