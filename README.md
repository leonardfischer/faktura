# Faktura
This small faktura web-application is built with Kohana in the backend and bootstrap and mootools in the frontend.

## Requirements
 * Enable the PHP short tags in your php.ini (short_open_tag=On)

## Installation
With version 1.1 the Faktura application will bring its own "install wizard". Here you can input your configuration (database, admin user, ...) and let the script do the rest.
Currently it is not (yet) very user-friendly: meaning, while installing you can't "go back" to change a (maybe misspelled) configuration or start the installation new without risking corrupted data.

## To-dos
 * Improve the installer (implement the "base" configuration and add the "Cookie" class + more user-friendly)
 * Use a template engine (Kostache, RainTPL, ...?) for the application and the print views - also implement multiple languages
 * Build in a "master password"
 * More widgets for the dashboard (also refactor the available widgets into single classes and views)
 * Make the dashboard "customizable"
 * Customer filter (inside an invoice) via ajax
 * "forgot password" function
 * Use "master password" to reset the admin user (in case you remove your admin-role)
 * Change the language in the GUI
