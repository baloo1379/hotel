<?php
require_once('../Config/connect.php');
require_once('../Config/functions.php');
require_once('../Classes/User.php');
require_once('../Classes/Notification.php');

$user_login = htmlentities($_POST['login'], ENT_QUOTES, "UTF-8");
$user_pass = htmlentities($_POST['password'], PASSWORD_DEFAULT, "UTF-8");
$user_pass2 = htmlentities($_POST['password2'], PASSWORD_DEFAULT, "UTF-8");
$user_name = htmlentities($_POST['name'], ENT_QUOTES, "UTF-8");

//header(ROOT_PATH.'/index.php?success=2');

if($user_pass != $user_pass2)
{
    header('Location: '.ROOT_PATH.'/index.php?success=2');
    die('wrong password');
}
else
{
    $newUser = new User(0, $user_login, $user_name, NULL);

    try
    {
        if($newUser->register( connect() , $user_pass ))
        {
			$notification = new Notification;
			$notification->setMessage('Pomyślnie utworzono nowe konto. Zaloguj się.');
			$notification->setWhere('index.php');
			$notification->send();
        }
    }
    catch (Exception $e)
    {
        echo 'Wystąpił wyjątek nr '.$e->getCode().', jego komunikat to: '.$e->getMessage();
    }


}
?>
