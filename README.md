# Faktura
This small faktura web-application is built with Kohana in the backend and bootstrap and mootools in the frontend.

## Requirements
 * Enable the PHP short tags in your php.ini (short_open_tag=On)

## Installation
With version 1.1 the Faktura application will bring its own "install wizard". Here you can input your configuration (database, admin user, ...) and let the script do the rest.
Currently it is not (yet) very user-friendly: meaning, while installing you can't "go back" to change a (maybe misspelled) configuration or start the installation new without risking corrupted data.

## To-dos (create issues for these)
 * Customer filter (inside an invoice) via ajax
 * Use "master password" to reset the admin user (in case you remove your admin-role)
 * Change the language in the GUI

## Framework usage
The faktura web-application makes use of the following frameworks:
 * The [Kohana PHP framework](http://kohanaframework.org/) v3.3.1
 * The [Boostrap CSS framework](http://getbootstrap.com/) v3.1.1 (+ some themes from [Bootswatch](http://bootswatch.com))
 * The [MooTools Core javascript framework](http://mootools.net/) v1.4.5
 * The [MooTools More javascript framework](http://mootools.net/more/) v1.4.0.1
 * The [MooTools Datepicker](http://mootools.net/forge/p/mootools_datepicker) v1.6.0
 * [Respond.js](https://github.com/scottjehl/Respond)
 * The [FontAwesome icon font](http://fortawesome.github.io/Font-Awesome/) v4.0.3