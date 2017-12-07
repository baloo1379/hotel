<?php
require_once('../Config/main.php');
require_once("../header.php");
$db = connect();
//TODO dodać opcje wyśywienia, ilość osób, zwierzęta

?>
        <main>
            <div id="newClient" class="form">
                <h2>Dodaj klienta</h2>
                <form method="post" action="../Scripts/add_client.php" name="Dodaj klienta" id="dodaj_klienta">
                    <p>
                        <label for="first">Imie: </label>
                        <input type="text" name="first" id="first" required>
                    </p>

                    <p>
                        <label for="last">Nazwisko: </label>
                        <input type="text" name="last" id="last" required>
                    </p>

                    <p>
                        <label for="tel">Numer tel.: </label>
                        <input type="text" name="tel" id="tel" required>
                    </p>

                    <p>
                        <label for="email">E-mail: </label>
                        <input type="text" name="email" id="email" required>
                    </p>

                    <input type="submit" value="Dodaj klienta">

                </form>
            </div>
            <div id="newOrder" class="form">
                <h2>Dodaj rezerwacje</h2>
                <form method="post" action="../Scripts/add_order.php" name="Dodaj klienta" id="dodaj_klienta">
                    <p>
                        <label for="room">Pokój: </label>
                        <select name="room" id="room" required>
                                <?php roomsOptions($db) ?>
                            </select>
                    </p>

                    <p>
                        <label for="name">Imie i Nazwisko klienta: </label>
                        <select name="name" id="name" required>
                                <?php clientsOptions($db) ?>
                            </select>
                        <button type="button" href="app.php?form=1">Dodaj nowego klienta</button>
                    </p>

                    <p>
                        <label for="start_date">Data roz. </label>
                        <input type="date" name="start_date" id="start_date" required>
                    </p>

                    <p>
                        <label for="end_date">Data zak. </label>
                        <input type="date" name="end_date" id="end_date" required>
                    </p>

                    <input type="submit" value="Dodaj rezerwacje">

                </form>
            </div>
            <div id="reservationTable" class="form">
                <?php orderList( $db ); ?>
            </div>

            <div id="clientTable" class="form">
                <?php clientList($db); ?>
            </div>
            <div class="bottom"></div>
        </main>
    </body>
</html>


