<?php
require_once('../Config/main.php');
require_once("../header.php");
$db = connect();

if(isset($_POST['id'],$_POST['first'],$_POST['last'],$_POST['tel'],$_POST['email']))
{
	$client = new Client($_POST['id'], $_POST['first'], $_POST['last'], $_POST['tel'], $_POST['email']);
	if($client->updateInDb($db))
	{
		$notify = new Notification('Zmiany zapisano','View/app.php');
		$notify->send();
	}
	else
	{
		$notify = new Notification('Nie udało się zapisać zmian','View/app.php');
		$notify->send();
	}
}
if(isset($_POST['id'],$_POST['room'],$_POST['name'],$_POST['start_date'],$_POST['end_date']))
{
	$order = new Order($_POST['id'], $_POST['room'], $_POST['name'], $user->getId(), $_POST['start_date'], $_POST['end_date']);
	if($order->updateInDb($db))
	{
		$notify = new Notification('Zmiany zapisano','View/app.php');
		$notify->send();
	}
	else
	{
		$notify = new Notification('Nie udało się zapisać zmian','View/app.php');
		$notify->send();
	}
}


?>
<main>
	<div>
		<?php if (isset($_GET['cid'])): ?>
			<?php
				$client_id = $_GET['cid'];
				$client = new Client($client_id);
				$client->fromDB($db);
			?>
			<div id="editClient" class="form">
				<h2>Edytuj klienta</h2>
				<form method="post" action="<?= $_SERVER['PHP_SELF'] ?>" name="Edytuj klienta" id="dodaj_klienta">
					<p style="display: none;">
						<label for="id">ID: </label>
						<input type="text" name="id" id="id" value="<?= $client->getId(); ?>">
					</p>
					<p>
						<label for="first">Imie: </label>
						<input type="text" name="first" id="first" value="<?= $client->getFName(); ?>">
					</p>

					<p>
						<label for="last">Nazwisko: </label>
						<input type="text" name="last" id="last" value="<?= $client->getLName(); ?>">
					</p>

					<p>
						<label for="tel">Numer tel.: </label>
						<input type="text" name="tel" id="tel" value="<?= $client->getTel(); ?>">
					</p>

					<p>
						<label for="email">E-mail: </label>
						<input type="text" name="email" id="email" value="<?= $client->getEmail(); ?>">
					</p>

					<input type="submit" value="Zapisz">
					<input type="button" onclick="history.back();" value="Anuluj">

				</form>
			</div>
		<?php endif; ?>
		<?php if (isset($_GET['oid'])) : ?>
			<?php
			$order_id = $_GET['oid'];
			$order = new Order($order_id);
			$order->fromDB($db);
			?>
			<div id="editOrder" class="form">
				<h2>Edytuj rezerwacje</h2>
				<form method="post" action="<?= $_SERVER['PHP_SELF'] ?>" name="Edytuj rezerwacje" id="dodaj_klienta">
					<p style="display: none;">
						<label for="id">ID: </label>
						<input type="text" name="id" id="id" value="<?= $order->getId(); ?>">
					</p>
					<p>
						<label for="room">Pokój: </label>
						<select name="room" id="room">
							<?php roomsOptions($db,$order->getRID()) ?>
						</select>
					</p>

					<p>
						<label for="name">Imie i Nazwisko klienta: </label>
						<select name="name" id="name" required>
							<?php clientsOptions($db,$order->getCID()) ?>
						</select>
					</p>

					<p>
						<label for="start_date">Data roz. </label>
						<input type="date" name="start_date" id="start_date" value="<?= $order->getStartDate(); ?>">
					</p>

					<p>
						<label for="end_date">Data zak. </label>
						<input type="date" name="end_date" id="end_date" value="<?= $order->getEndDate(); ?>">
					</p>

					<input type="submit" value="Zapisz">
					<input type="button" onclick="history.back();" value="Anuluj">

				</form>
			</div>
		<?php endif; ?>
		<?php if (isset($_GET['rid'])) : ?>
			<div class="form">
				<form method="post" action="<?php echo ROOT_PATH; ?>/Scripts/change_paswd.php" name="Zmiana hasła" id="zmiana_hasla">
					<h2>Zmiana hasła</h2>
					<p>
						<label for="oldPass">Stare hasło:</label>
						<input type="password" name="oldPass" id="oldPass" required>
					</p>

					<p>
						<label for="newPass">Nowe hasło:</label>
						<input type="password" name="newPass" id="newPass" required>
					</p>

					<p>
						<label for="newPass2">Powtórz hasło</label>
						<input type="password" name="newPass2" id="newPass2" required>
					</p>

					<input type="submit" value="Zmień hasło">

				</form>
			</div>
		<?php endif; ?>
	</div>
</main>
</body>
</html>
