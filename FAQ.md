# Frequently Asked Questions

This document contains the answers to frequently asked questions concerning Balloon.


### Is Balloon a payment processor or a store?

It's kind of a mix between the two. It's got all the infrastructure you need to set up a store, but it's modular enough to be adapted to other uses, just like a standalone payment processor.


### Is it possible to receive refunds for purchases made through Balloon?

Not really. There is nothing stopping the store owner from sending the XMR a user made a purchase with back to the user, but there is no system to do this automatically or through Balloon.


### Does Balloon need to be hosted at the default Apache location? (/var/www/html/)

Theoretically, no. The only moderately important fact is that the 'balloon' folder be at the root of the web server directory. If it's not, then the style sheets won't be loaded properly (though the website should still be fully functional). However, it should be noted that Balloon has only been tested at /var/www/html/, and bugs are highly likely. If you encounter one, you're encouraged to contact V0LT using the information at <https://v0lttech.com/contact.php> so I can fix the issue!


### Does Balloon have an authentication/account system built in?

It does. Balloon uses DropAuth, a 'drag and drop' authentication system for PHP. However, you are encouraged to replace this system using the `balloon/store/authentication.php` script so that Balloon works more continuously with your existing site account system (if you have one).


### Does Balloon have an analytics system built in?

Since Balloon is designed first and foremost with privacy and freedom in mind, Balloon only has very limited analytics system built in. Tools located in the `balloon/store/tools/` folder allow you to get basic analytic information about your store. However, if you need more in-depth analytics, the `balloon/store/analytics.php` script allows you to embed your own analytics system into each page.


### I have a question or suggestion regarding Balloon. Where do I go?

If you've got a comment or question related to Balloon, you're encouraged to get in touch with V0LT at <https://v0lttech.com/contact.php>!


### Do the prices in Balloon automatically fluctate based on XMR to USD conversion rates?

They do not. I currently don't know any way to implement a feature like this that wouldn't require contacting external, proprietary services. To preserve the freedom, privacy, and transparency of Balloon, I've chosen to keep prices as static XMR values.


### What can you sell on Balloon?

Since Balloon is very open a modular, theoretically, you can see anything you want! However, Balloon is designed around the assumption that it's users will be distributing digital content like photos, videos, audio, software, and documents, since these are easy to distribute automatically. If you're willing to sit down and spend some time modifying the 'download' system at `balloon/store/download.php`, it's not impossible to use Balloon to distribute physical products as well!


### Can Balloon give me the contact information of my customers?

Since Balloon just uses your existing account system, information in it's databases can be cross referenced with your main website's user account information. By making some modifications to `balloon/store/tools/get_customers.php`, it shouldn't be too complicated to cross reference the usernames in Balloon's database with your main account information system in order to get customer contact information.


### I don't have the technical skills and/or equipment to self host Balloon. Is there a hosting provider I can go to to create a Balloon instance?

At V0LT, I offer paid services to setting up a Balloon instance. However, this service currently does not including hosting, so you'll either need to purchase your own server, or rent one from a VPS service. If this interests you, you are encouraged to get in touch with V0LT to work out details and ask any questions you may have!
