<?php
require_once('../Config/main.php');
require_once("../header.php");
$db = connect();
?>
    <main>
        <div>
            <?php if (isset($_GET['type']) && $_GET['type'] == 1) : ?>
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
