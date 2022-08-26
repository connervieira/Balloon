# Documentation

This document contains everything you need to know to get Balloon up and running.

## Quick Setup

*Warning: This method of setup isn't tested, and is likely to not work. It is highly recommended that you complete the full setup explained in the 'Typical Installation and Setup' section.*

1. To quickly set up Balloon, copy Balloon's root directory into your Download's folder, such that it can be accessed at `~/Downloads/balloon`.
2. Next, run this command. You may need to enter your root password several times.
    - `sudo apt install apache2 php7.4 php-gd; sudo a2enmod php7.4; mv ~/Downloads/balloon /var/www/html/; sudo chmod 777 /var/www/html/balloon/dropauth/ /var/www/html/balloon/store/storedatabase.txt /var/www/html/balloon/store/productsdatabase.txt /var/www/html/balloon/store/configurationdatabase.txt; sudo apache2ctl restart`
3. Finally, finish setting up Balloon using the GUI by starting at step 5 in the 'Configuration' section below

## Typical Installation and Setup

To download and install to your webserver, use the following instructions. Please note that you should prevent users from accessing your website while configuring and setting up Balloon for security reasons. For sake of testing, you may want to leave your web server up on LAN, and simply block it's port on your router.

### Installation

This installation process assumes you are using a Raspberry Pi or another Debian based GNU/Linux distribution. However, the general steps will be the same across other platforms.

1. Install Apache if you haven't already
    - Example: `sudo apt install apache2`
2. Install PHP if you haven't already
    - Example: `sudo apt install php7.4`
3. If the PHP Apache module doesn't automatically get enabled, enable it.
    - Example: `sudo a2enmod php7.4`
4. Install modules required by Balloon's QR code library.
    - Example: `sudo apt-get install php-gd`
5. Restart Apache.
    - Example: `sudo apache2ctl restart`
6. Copy or download Balloon to the root of your webserver.
    - Example: `mv ~/Downloads/balloon /var/www/html/`
7. If successful, you should be able to access Balloon (with placeholder settings) by going to <http://localhost/balloon/store/> (Substituting `localhost` with a server IP address if you're working on a remote server)

### Configuration

1. Ensure the DropAuth folder at `balloon/dropauth` is writable to PHP. Alternatively, replace the DropAuth authentication in `balloon/store/authentication.php` with your own authentication system.
    - `sudo chmod 777 balloon/dropauth/` will accomplish this, but will also allow other programs to write to the DropAuth folder.
2. Make sure that the store database (`store/storedatabase.txt`), product database (`store/productsdatabase.txt`), and configuration database (`store/configurationdatabase.txt`) are all writable. In other words, you should run `sudo chmod 777 storedatabase.txt; sudo chmod 777 productsdatabase.txt; sudo chmod 777 configurationdatabase.txt` to ensure they can be written to by the PHP scripts. However, keep in mind that these commands will set permissions that allow any program to write to these databases, so ensure that you trust the system you are working with.
    - If you open the Purchase page for a given product, and the `Purchase ID` changes every time you refresh the page, this is most likely the issue. It is critical that the `Purchase ID` remains the same every time the page is loaded for a given user and product. This is what ensures the user can close the webpage and come back later and still have their transaction approved.
3. Make sure that the store database (`store/storedatabase.txt`), products database (`store/productsdatabase.txt`), and authentication database (`dropauth/accountDatabase.txt`) only contain `a:0:{}`, and nothing else. This is what an empty serialized array should look like. If the file is empty, or contains other characters, Balloon may not be able to write to it properly.
4. Sign up for an account on your Balloon instance with the username 'admin'. This is the default admin account username.
5. After signing into your admin account, open the main page of your Balloon instance, and click the "Configuration" button at the top of the page to be brought to 'balloon/store/configure.php'. From here, you'll be able to configure your Balloon instance. You can learn what all of these settings do in the CONFIGURATION.md document. The following settings should be changed. Other setting changes are optional.
    - `Store Title`
    - `Store Tagline`
    - `Store Description`
    - `Extended Public Key`
    - `Login Page`
    - `Disclaimers`
    - `Support Email`
6. Configure the products you want to display in your Balloon store. Sign up for an account on your Balloon store's DropAuth page using the same username that you specified as your `$admin_account` earlier. After logging in to this account, you should see a button on the main Balloon store page titled 'Edit Products'. You can click this button to open a page that allows you to create, edit, and delete products.
7. Determine what you want to happen when someone tries to access a product they've successfully purchased. To do this, edit `store/download.php` and edit the code accordingly. You'll find the functions that run when someone tries to download a product they've purchased at the very top of the script. For example, you might insert a short script that redirects the user to a download page, or shows them a product key they can use to access their download.
8. Optionally, add your analytics system to `balloon/store/analytics.php`. This could be as simple as an `echo` statement containing the code required to load a script, or as complicated as creating your own in-house analytics system. It should be clarified that `analytics.php` itself offers no analytical functions. It's simply there to allow you to easily use analytics with Balloon. This script is run inside the `<head>` tag of all Balloon pages that a user would access, so it's the perfect place to add a script import, like in the following example:
    - `echo '<script async defer data-domain="server.com" src="/js/analyticsscript.js"></script>';`

### Testing

A good first step in testing would be to load <http://localhost/balloon/store/failsafe.php>. This should indicate any glaring issues. If you don't see anything, then no issues were found. However, this **does not** mean for certain that everything is working. This script just runs some basic checks to detect common configuration problems.

Balloon should now be fully functional. However, it's highly suggested that you test everything from start to finish to make sure that it all works how you are expecting it to. To do this, you may want to temporarily modify one of your products to make it extremely inexpensive (A few cents or so). Then, run through the system from start to finish to see if you successfully get your product.

Provided that everything works as expected, you can revert the price back to normal, and proceed with the setup!


## Help

If you need help setting up Balloon, be sure to get in touch using the information found at <https://v0lttech.com/contact.php>. I'd be happy to give you a hand. For more in-depth technical assistance, I offered paid support, which you can learn more about at <https://v0lttech.com/balloonpro.php>
