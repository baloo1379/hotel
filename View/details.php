<?php
require_once('../Config/main.php');
require_once("../header.php");
$db = connect();

if(isset($_GET['dbe'])) $error_msg = 'Błąd bazy danych.';

if(isset($_GET['cid']))
{
    $id = $_GET['cid'];
    $client  = new Client($id);
    $client->fromDB($db);
}
if(isset($_GET['oid']))
{
    $id = $_GET['oid'];
    $order  = new Order($id);
    $order->fromDB($db);
}
if(isset($_GET['rid']))
{
    $id = $_GET['rid'];
    $room  = new Room($id);
    $room->fromDB($db);
}
require_once("../header.php");
?>

    <main>
        <div class="form">
            <?php if (isset($_GET['cid'])) : ?>
            <?php clientDetails($db, $client); ?>
            <?php endif; ?>
            <?php if (isset($_GET['oid'])) : ?>
            <?php orderDetails($db, $order); ?>
            <?php endif; ?>
            <?php if (isset($_GET['rid'])) : ?>
            <?php roomDetails($db, $room); ?>
            <?php endif; ?>
        </div>
    </main>

    </body>

    </html>
