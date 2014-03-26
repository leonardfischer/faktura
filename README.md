# Faktura
This small faktura web-application is built with Kohana in the backend and bootstrap and mootools in the frontend.

## Installation
 * Enable the PHP short tags in your php.ini (short_open_tag=On)
 * Import the "setup.sql" to your database
 * Configure your database connection inside application/config/database.php
 * Set the "hash_key" option inside application/config/auth.php (and **DO NOT** change it, once a user is created)
 * Set the Cookie SALT in application/classes/Cookie.php
 * Insert a new user in the database -> This may be a bit tricky at the moment, because there is no "install script".

## To-dos
 * Use a template engine (Kostache?)
 * Use templates and multi-language print templates
 * More widgets for the dashboard (also refactor the available widgets into single classes and views)
 * Customer filter (inside an invoice) via ajax?
 * "forgot password" function
 * Change the language in the GUI
