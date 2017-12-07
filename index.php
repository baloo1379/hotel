<?php
require_once ('Classes/Client.php');
require_once ('Classes/Order.php');
require_once ('Classes/Room.php');
require_once ('Classes/Hotel.php');
require_once ('Classes/User.php');
require_once ('Classes/Notification.php');
require_once ('Config/connect.php');
require_once ('Config/functions.php');

sec_session_start();

$hotel = setHotel( connect() );

if(isset($_GET['message'])) $notification = new Notification($_GET['message']);
else $notification = new Notification('');

if(isLogged(connect()))
{
    $notify = new Notification('Jesteś zalogowany', 'View/app.php');
	$notify->send();
}
?>
<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>
			<?= $hotel->getName(); ?>
        </title>
        <link rel="stylesheet" type="text/css" href="style.css" />
    </head>
    <body>
        <header>
            <h1>
				<?= $hotel->getName(); ?>
            </h1>
        </header>
        <main>
            <div class="error">
                <span><?php echo $notification->getMessage() ?></span>
            </div>
            <div class="form">
                <form method="post" action="Scripts/login.php" name="Login" id="zaloguj">

                    <h2>Zaloguj się!</h2>

                    <p><label for="login">Login: </label>
                        <input type="text" name="login" id="login" required></p>

                    <p><label for="password">Hasło: </label>
                        <input type="password" name="password" id="password" required>
                    </p>

                    <input type="submit" value="Zaloguj">

                </form>
            </div>
            <div class="form">
                <form method="post" action="Scripts/register.php" name="Register" id="zarejestruj">

                    <h2>Zarejestruj się!</h2>

                    <p><label for="name">Nazwa: </label>
                        <input type="text" name="name" id="name" required></p>

                    <p><label for="login">Login: </label>
                        <input type="text" name="login" id="login" required></p>

                    <p><label for="password">Hasło: </label>
                        <input type="password" name="password" id="password" required>
                    </p>

                    <p><label for="password">Powtórz hasło: </label>
                        <input type="password" name="password2" id="password2" required></p>

                    <input type="submit" value="Zarejestruj">

                </form>
            </div>
            <div class="bottom"></div>
        </main>
    </body>
</html>
