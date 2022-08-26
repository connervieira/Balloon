<!DOCTYPE html>
<html style="background:#000000;" lang="en">
    <head>
        <title>Balloon - Receipt</title>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="../files/fonts/lato/latofonts.css">
	    <link rel="stylesheet" href="../assets/css/Projects-Clean.css">
        <?php include "./analytics.php"; ?>

        <style>
        p { font-family:LatoWeb; }
        ol { font-family:LatoWeb; }
        ul { font-family:LatoWeb; }
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

        include './failsafe.php';

        if ($username == "" || $username == null) { // Redirect the user to the login page if they are not signed in.
            header("Location: " . $login_page);
        }


        $product = $_GET["product"]; // Determine the product the user would like to purchase based on it's product ID in the URL.

        // Check to make sure there is actually a product with the selected product ID.
        if (array_key_exists($product, $productsArray[$store_id]) == false) {
            echo "<p>Error: The product selected doesn't exist!</p>";
            echo "<a href='mailto:" . $support_email . "'>Contact Support</a>";
            exit();
        }

        $amount = $productsArray[$store_id][$product]["price"]; // Determine the price of the selected product.


        // Load the store database
        $storeArray = unserialize(file_get_contents('./storedatabase.txt'));

        if ($storeArray[$username][$store_id][$product]["txid"]) { // Check to see if this user already has a transaction ID associated with this product.
            $paymentID = (int)$storeArray[$username][$store_id][$product]["txid"]; // Load this user's transaction ID for the selected product from the store database.
            $alreadyPurchased = (bool)$storeArray[$username][$store_id][$product]["purchased"]; // Load the purchase status from the store database to determine if the user has already purchased this product or not.
        } else {
            $paymentID = rand(1000000, 9999999); // Generate a random transaction ID
            $storeArray[$username][$store_id][$product]["txid"] = $paymentID; // Store the generated transaction ID in the store database
            file_put_contents('./storedatabase.txt', serialize($storeArray)); // Write array changes to disk
        }

        if ($alreadyPurchased == false) {
            echo "<p>You have not yet purchased this product. That means you can't generate a receipt for it!</p>";
            echo "<a href='mailto:" . $support_email . "'>Contact Support</a>";
            exit();
        }


        // Set up Monero library
        // TODO



        echo "<h1>Receipt</h1>";
        echo "<div style='text-align:left;'>";
        echo "<p><b>Product Name</b>: " . $productsArray[$store_id][$product]["name"] . "</p>";
        echo "<p><b>Price</b>: " . $productsArray[$store_id][$product]["price"] . "</p>";
        echo "<p><b>Product ID</b>: " . $product . "</p>";
        echo "<p><b>Payment/Transaction ID</b>: " . $paymentID . "</p>";
        echo "<p><b>Payment Address</b>:</p>";
        echo "</div>";
        ?>
    </body>
</html>
