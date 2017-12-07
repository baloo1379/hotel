<?php

function sec_session_start() {
	$session_name = 'sec_session_id';   // Set a custom session name
	$secure = false;
	// This stops JavaScript being able to access the session id.
	$httponly = true;
	// Forces sessions to only use cookies.
	if (ini_set('session.use_only_cookies', 1) === FALSE) {
		header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
		exit();
	}
	// Gets current cookies params.
	$cookieParams = session_get_cookie_params();
	session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
	// Sets the session name to the one set above.
	session_name($session_name);
	session_start();            // Start the PHP session
	session_regenerate_id();    // regenerated the session, delete the old one.
}

function isLogged($db)
    {
        if(isset($_SESSION['user'],$_SESSION['id']))
        {
            $id = $_SESSION['id'];
            $user = $_SESSION['user'];
            $user_login = $user->getLogin();

            if($stmt = $db->prepare("SELECT `user_id`, `user_pass` FROM `users` WHERE user_login = ? "))
            {
                $stmt->bind_param('s', $user_login);
                $stmt->execute();
                $stmt->store_result();
				$stmt->bind_result($user_id, $user_pass);
				$stmt->fetch();

                if($stmt->num_rows == 1)
                {
                    $login_ckeck = hash('sha256', $user_pass.$user_id.$_SERVER['HTTP_USER_AGENT']);

                    if(hash_equals($id, $login_ckeck))
                    {
                        //logged
                        return true;
                    }
                    else
                    {
                        //hash isnt equal
                        return false;
                    }
                }
                else
                {
                    //rows
                    return false;
                }
            }
            else
            {
                //database 1
                return false;
            }
        }
        else
        {
            //session
            return false;
        }
    }

function clearSession()
{
	$_SESSION = array();
    $params = session_get_cookie_params();
    setcookie(session_name(),
        '', time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]);
    session_destroy();
    //header('Location: ../index.php');
}

function setHotel($db)
{
	if(!isset($_SESSION['hotel']))
	{
		$hotel = new Hotel ;
		$hotel->getHotelDetailsFromDB($db);
		$_SESSION['hotel'] = $hotel;
	}
	else $hotel = $_SESSION['hotel'];
	return $hotel;
}

//TODO ogarnąć funkcje do wyświetlania jednego rekordu i całej tabeli

function clientList($db)
{
    echo '<h3>Lista klientów:</h3>';
    echo '<table class="blueTable">
                    <thead>
                        <th>Nr</th>
                        <th>Imię</th>
                        <th>Nazwisko</th>
                        <th>Numer telefonu</th>
                        <th>Adres e-mail</th>
                        <th>Opcje</th>
                    </thead>';

    $stmt = $db->prepare('SELECT `client_id` FROM `clients`');
    $stmt->execute();
	$stmt->bind_result($client_id);

    while($stmt->fetch())
    {
        $client = new Client($client_id);
        $client->fromDB( connect() );

        echo '<tr>';

        echo '<td>';
        echo $client->getId();
        echo '</td>';

        echo '<td>';
        echo $client->getFName();
        echo '</td>';

        echo '<td>';
        echo $client->getLName();
        echo '</td>';

        echo '<td>';
        echo $client->getTel();
        echo '</td>';

        echo '<td>';
        echo $client->getEmail();
        echo '</td>';

        echo '<td>';
        echo $client->delLink().' '.$client->editLink();
        echo '</td>';

        echo "</tr>";
    }
    echo '</table>';
}

function clientsOptions($db, $id=1)
{
    $r = $db->query("SELECT `client_id`, `first_name`, `last_name` FROM `clients`");
    while($row = $r->fetch_assoc())
    {
        if($row['client_id']==$id) echo '<option value="'.$row['client_id'].'" selected>'.$row['first_name'].' '.$row['last_name'].'</option>';
        else echo '<option value="'.$row['client_id'].'">'.$row['first_name'].' '.$row['last_name'].'</option>';
    }
}

function clientDetails($db, $client)
{
    echo "<table class=\"blueTable\">
                    <thead>
                        <th>Imię</th>
                        <th>Nazwisko</th>
                        <th>Numer telefonu</th>
                        <th>Adres e-mail</th>
                    </thead>
                    <tr>";
                        echo '<td>';
                        echo $client->getFName();
                        echo '</td>';

                        echo '<td>';
                        echo $client->getLName();
                        echo '</td>';

                        echo '<td>';
                        echo $client->getTel();
                        echo '</td>';

                        echo '<td>';
                        echo $client->getEmail();
                        echo '</td>';
                echo "</tr>
            </table>";
}

function orderList($db)
{
    echo '<h3>Aktualne rezerwacje:</h3>';
    $result = $db->query("SELECT * FROM `orders`");
    echo '<table class="blueTable"><thead><tr><th>Nr rezerwacji</th><th>Klient</th><th>Pokój</th><th>Opcje</th></tr></thead>';

    while($row = $result->fetch_assoc())
    {
        $client = new Client($row['client_id']);
        $client->fromDB($db);

        $room = new Room($row['room_id']);
        $room->fromDB($db);
        $room_nr = $room->getNumber();

        $order = new Order($row['order_id']);

        echo '<tr>';
        echo '<td>'.'<a href="details.php?oid='.$row['order_id'].'">'.$row['order_id'].'</a>'.'</td>';
        echo '<td>'.$client->NameLink().'</td>';
        echo '<td>'.$room_nr.'</td>';
        echo '<td>'.$order->delLink().' '.$order->editLink().'</td>';
        echo '</tr>';
    }
    echo '</table>';
}

function orderDetails($db, $order)
{
    $client = new Client($order->getCID());
    $client->fromDB($db);
    $room = new Room($order->getRID());
    $room->fromDB($db);

    echo "<table class=\"blueTable\">
                    <thead>
                        <th>Nr rezerwacji</th>
                        <th>Klient</th>
                        <th>Pokój</th>
                        <th>Check-In</th>
                        <th>Check-Out</th>
                    </thead>
                    <tr>";
                        echo '<td>';
                        echo $order->getID();
                        echo '</td>';

                        echo '<td>';
                        echo $client->NameLink();
                        echo '</td>';

                        echo '<td>';
                        echo $room->getNumber();
                        echo '</td>';

                        echo '<td>';
                        echo $order->getStartDate();
                        echo '</td>';

                        echo '<td>';
                        echo $order->getEndDate();
                        echo '</td>';
                echo "</tr>
            </table>";
}

function roomDetails($db, $room)
{
    echo "<table class=\"blueTable\">
                    <thead>
                        <th>L.p.</th>
                        <th>Numer</th>
                        <th>Zarezerwowany</th>
                        <th>Ostatnie czyszczenie</th>
                    </thead>
                    <tr>";
                        echo '<td>';
                        echo $room->getID();
                        echo '</td>';

                        echo '<td>';
                        echo $room->getNumber();
                        echo '</td>';

                        echo '<td>';
                        echo $room->isUsed();
                        echo '</td>';

                        echo '<td>';
                        echo $room->lastClean();
                        echo '</td>';
                echo "</tr>
            </table>";
}

function roomNRfromID($db, $id)
{
    $r = $db->query("SELECT `room_nr` FROM `rooms` WHERE `room_id` LIKE '{$id}'");
    $row = $r->fetch_assoc();
    return row['room_nr'];
}

function roomIDfromNR($db, $nr)
{
    $r = $db->query("SELECT `room_id` FROM `rooms` WHERE `room_nr` LIKE '{$nr}'");
    $row = $r->fetch_assoc();
    return row['room_id'];
}

function roomsOptions($db, $id=1)
{
    $r = $db->query("SELECT `room_id`, `room_nr` FROM `rooms`");
    while($row = $r->fetch_assoc())
    {
        if($row['room_id'] == $id) echo '<option value="'.$row['room_id'].'" selected>'.$row['room_nr'].'</option>';
        else echo '<option value="'.$row['room_id'].'">'.$row['room_nr'].'</option>';
    }
}





?>
