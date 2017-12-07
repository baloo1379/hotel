<?php
require_once('../Config/connect.php');
require_once('../Config/functions.php');
sec_session_start();

$oldPass = $db->real_escape_string(htmlentities($_POST['oldPass'], ENT_QUOTES, "UTF-8"));
$user_pass = $db->real_escape_string(htmlentities($_POST['newPass'], ENT_QUOTES, "UTF-8"));
$user_pass2 = $db->real_escape_string(htmlentities($_POST['newPass2'], ENT_QUOTES, "UTF-8"));

if($user_pass != $user_pass2)
{
	header('Location: '.ROOT_PATH.'/index.php?success=2');
	die('wrong password');
}
else
{
    try
    {
        if($user->change_password($db, $user_pass, $user_pass2))
        {
			header('Location: '.ROOT_PATH.'/index.php?chg=1');
        }
    }
    catch (Exception $e)
    {
		echo 'Wystąpił wyjątek nr '.$e->getCode().', jego komunikat to: '.$e->getMessage();
    }
}

?>
    //$2y$10$bpR/fTNKZdvJzalaJcic1Oi0.l5iHOYxXHHxglwzup7GY6jLAd5NW
