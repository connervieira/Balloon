<?php

use MoneroIntegrations\MoneroPhp\daemonRPC;
use MoneroIntegrations\MoneroPhp\walletRPC;

// Make sure to display all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Load the Monero PHP library.
require_once('../../../monerophp/src/jsonRPCClient.php');
require_once('../../../monerophp/src/daemonRPC.php');
require_once('../../../monerophp/src/walletRPC.php');

$daemonRPC = new daemonRPC('127.0.0.1', 18081, false); // Connect to the Monero daemon.
$walletRPC = new walletRPC('127.0.0.1', 18083, false); // Connect to the Monero wallet interface.


// Grab the user input from POST data, if applicable.
if (isset($_POST["password1"]) and isset($_POST["password2"])) {
    $password1 = $_POST["password1"];
    $password2 = $_POST["password2"];
} else {
    $password1 = "";
    $password2 = "";
}



// Generate a Monero wallet using the user's specified password.
if ($password1 !== null and $password1 !== "") {
    if ($password1 == $password2) {
        $create_wallet = $walletRPC->create_wallet('balloon_monero_wallet', $password1); // Creates a new wallet named balloon_monero_wallet with the user-specified password.
        $address = $walletRPC->get_address();
    } else {
        echo "<p style='margin:15%;color:red;'>Error: The passwords entered do not match. Wallet creation has been aborted.</p>";
        exit();
    }
}

?>
<html lang="en">
    <body>
        <?php

        if (isset($address)) {
            echo "<p>Wallet successfully generated</p>";
        } else {
            echo '
            <form method="POST">
                <input type="password" placeholder="Wallet Password" id="password1" name="password1">
                <input type="password" placeholder="Password Confirmation" id="password2" name="password2">
                <input type="submit" id="submit" name="submit">
            </form>
            ';
        }
        ?>
    </body>
</html>
 
