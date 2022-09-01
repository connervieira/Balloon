<?php

use MoneroIntegrations\MoneroPhp\daemonRPC;
use MoneroIntegrations\MoneroPhp\walletRPC;

// Make sure to display all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load the Monero PHP libraries.
require_once('../../../monerophp/src/jsonRPCClient.php');
require_once('../../../monerophp/src/daemonRPC.php');
require_once('../../../monerophp/src/walletRPC.php');

$daemonRPC = new daemonRPC('127.0.0.1', 18081, false); // Connect to the Monero daemon.

$walletRPC = new walletRPC('127.0.0.1', 18083, false); // Connect to the Monero RPC wallet.

if (isset($_POST["password"])) { // Check to see if the user supplied a wallet password.
    $password = $_POST["password"]; // Set the password to the password entered by the user.
} else {
    $password = ""; // No password was supplied, so set the password to an empty string.
}


if ($password !== "") { // If the user entered a password, attempt to open the wallet.
    $open_wallet = $walletRPC->open_wallet('balloon_monero_wallet', $password); // Open the wallet using the user-supplied password.
    $get_address = $walletRPC->get_address();
    $get_accounts = $walletRPC->get_accounts();
    $get_balance = $walletRPC->get_balance();
}

?>
<html lang="en">
    <body>
        <?php
        if ($password =="") {
            echo '
            <form method="POST">
                <input type="password" placeholder="Wallet Password" id="password" name="password"><br>
                <input type="submit">
            </form>
            ';
        } else {
            echo'
            <ul>
                <li><b>Balance</b></li>
                <ul>
                <li><b>Balance:</b> ' . $get_balance['balance'] / pow(10, 12) . '</li>
                <li><b>Unlocked Balance:</b> ' . $get_balance['unlocked_balance'] / pow(10, 12) . '</li>
                </ul>

                <li><b>Accounts:</b> ' . count($get_accounts['subaddress_accounts']) . '</li>
                <ul>
            ';
            foreach ($get_accounts['subaddress_accounts'] as $account) {
                echo '<li><b>Account ' . $account['account_index'] . ':</b> ' . $account['base_address'] . '</li>';
                echo '<ul>';
                echo '<li><b>Balance:</b> ' . $account['balance'] / pow(10, 12) . ' (' . $account['unlocked_balance'] / pow(10, 12) . ' unlocked)</li>';
                echo '<li><b>Label:</b> ' . $account['label'] . '</li>';
                echo '<li><b>Tag:</b> ' . $account['tag'] . '</li>';
                echo '</ul>';
            }
            echo'</ul></ul>';
        }
        ?>
    </body>
</html>
 
