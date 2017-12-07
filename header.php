<!DOCTYPE html>
<html lang="pl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $hotel->getName(); ?>
    </title>
    <link rel="stylesheet" type="text/css" href="../style.css" />
</head>

<body>

    <header>

        <h1>
			<?= $hotel->getName(); ?>
        </h1>
        <div class="error">
            <span><?= $notification->getMessage(); ?></span>
        </div>
        <nav>
            <ul>
                <li><a href="<?php echo ROOT_PATH; ?>Scripts/logout.php">Wyloguj</a></li>
                <li><a href="<?php echo ROOT_PATH; ?>View/app.php">Menu główne</a></li>
                <li><a href="<?php echo ROOT_PATH; ?>View/settings.php?type=1">Ustawienia konta</a></li>
            </ul>
        </nav>
        <h2>
            Witaj
            <?php echo $user->getName() ?>
        </h2>
    </header>
