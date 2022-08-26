<!DOCTYPE html>
<html style="background:#000000;" lang="en">
    <head>
        <title>Balloon - Purchase Product</title>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="../files/fonts/lato/latofonts.css">
	    <link rel="stylesheet" href="../assets/css/Projects-Clean.css">
        <?php include "./analytics.php"; ?>

        <style>
        p { font-family:LatoWeb; }
        ol { font-family:LatoWeb; }
        h1 { font-family:LatoWebBold; }
        h3 { font-family:LatoWeb; }
        a { color:white;text-decoration:underline;font-family:LatoWeb; }
        </style>
    </head>
    <body style="color:#ffffff;text-align:center;padding:5%;background:inherit;">
        <div style="text-align:left;">
            <a class="btn btn-light" role="button" href="../store/index.php" style="margin:8px;padding:9px;background-color:#888888;border-color:#333333;border-radius:10px;">Back</a>
        </div>
        <?php


        include './payments.php'; // Monero library
        include './authentication.php'; // Balloon authentication system
        include './config.php'; // Balloon configuration file
        include './products.php'; // Balloon product's list

        if ($panic_switch == true) { // Check if the panic switch is active
            echo "<p style='margin:15%;'>The owner of this store has temporarily disabled it. This might mean something has gone wrong, or the server is being maintained. Please check back later. If you have any questions, contact customer support at <a style='color:white;text-decoration:underline;' href='";
            echo $support_email;
            echo "'>";
            echo $support_email;
            echo "</a></p>";
                                        
            exit(); // Stop loading the rest of the page.
        }

        include './failsafe.php'; // Run the failsafe script to attempt to detect any critical configuration problems.

        if ($username == "" || $username == null) { // Redirect the user to the login page if they are not signed in.
            header("Location: " . $login_page);
        }


        $productToPurchase = $_GET["product"]; // Determine the product the user would like to purchase based on it's product ID in the URL.

        // Check to make sure there is actually a product with the selected product ID.
        if (array_key_exists($productToPurchase, $productsArray[$store_id]) == false) {
            echo "<p>Error: The product selected doesn't exist!</p>";
            echo "<a href='mailto:" . $support_email . "'>Contact Support</a>";
            exit();
        }

        $amount = $productsArray[$store_id][$productToPurchase]["price"]; // Determine the price of the selected product.


        // Load the store database
        $storeArray = unserialize(file_get_contents('./storedatabase.txt'));

        if ($storeArray[$username][$store_id][$productToPurchase]["txid"]) { // Check to see if this user already has a transaction ID associated with this product.
            $paymentID = (int)$storeArray[$username][$store_id][$productToPurchase]["txid"]; // Load this user's transaction ID for the selected product from the store database.
            $alreadyPurchased = (bool)$storeArray[$username][$store_id][$productToPurchase]["purchased"]; // Load the purchase status from the store database to determine if the user has already purchased this product or not.
        } else {
            $paymentID = rand(1000000, 9999999); // Generate a random transaction ID
            $storeArray[$username][$store_id][$productToPurchase]["txid"] = $paymentID; // Store the generated transaction ID in the store database
            file_put_contents('./storedatabase.txt', serialize($storeArray)); // Write array changes to disk
        }


        // Set up Monero library TODO
        $plain_address = str_replace("monero:", "", $address->cashAddress);


        if ($plain_address == "" or $plain_address == null) { // Check to see if the payment address loaded properly.
            echo "<p style='margin:15%;color:red;'>Error: The payment address failed to load. This is likely a problem with the payment API. considering contacting the administrator of this instance to make them aware of the problem: <a href='mailto:";
            echo $support_email;
            echo "'>";
            echo $support_email;
            echo "</a></p>";
        }


        // Display the main webpage content
        echo "
        <h1>Disclaimers</h1>
        <p>";

        echo $disclaimers; // Show the disclaimers as defined in the Balloon configuration.

        echo "</p>

        <hr>

        <h1>Instructions</h1>
        <ol style='text-align:left;'>
            <li>Open your Monero wallet.</li>
            <li>Scan the QR code below.</li>
            <li>Verify that the auto-filled values look right.</li>
            <li>Press send!</li>
            <li>Wait for the transaction to verify. Feel free to close the window while you wait.</li>
            <li>Come back in a few minutes and re-open/refresh the page.</li>
            <li>If you see 'Confirmed' under 'Payment Status', your product has been added to your account!</li>
            <li>If instead your see 'Verifying', don't worry! Sometimes it takes longer for the transaction to confirm. Just check back later!</li>
        </ol>

        <hr>
        ";


        echo "<h1>Automatic Payment</h1>";
        echo "<h3>The following QR code should automatically set the address and amount.</h3>";
        echo '<img src="../store/qrgenerate.php?address=' . $plain_address .'&amount=' . $amount . '" alt="Payment QR code">' . "\n";

        echo "<hr>";

        echo "<h1>Payment Information</h1>";
        echo "<h3>Alternatively, you can make a payment manually using the information from the summary below.</h3>
        <p>You should also use this information to verify that the values automatically filled out by the QR code above are correct.</p>";
        echo "<div style='text-align:left;padding:5%;'>";
        echo "<p><b>Price:</b> " . $amount . " XMR</p>";
        echo "<p><b>Address:</b> " . $plain_address . "</p>";
        echo "<p><b>Purchase ID:</b> " . $paymentID . "</p>";
        echo "<p><b>Product Name:</b> " . $productsArray[$store_id][$productToPurchase]["name"] . "</p>";
        echo "<p><b>Current User:</b> " . $username . "</p>";
        echo "</div>";


        // Check the address balance (inlcuding TX)

        // TODO

        echo "<hr>";
        echo "<h1>Payment Status</h1>";


        if (confirmed) { // TODO
            echo "<p><b>Confirmed</b><br><br>You should now be able to access your purchased product on the Store page! Now is also a good time to view and save your receipt <a href='receipt.php?product=" . $productToPurchase . "'>here</a>.</p>";
            $storeArray[$username][$store_id][$productToPurchase]["purchased"] = true; // The user has successfully purchased this product, so indicate this in the database.
            file_put_contents('./storedatabase.txt', serialize($storeArray)); // Write array changes to disk.
        }
        ?>
    </body>
</html>
