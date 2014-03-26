<?php defined('SYSPATH') OR die('No direct access allowed.');

return array
(
	'title' => 'faktura dev',

	// Used for the Kohana bootstrap.
	'timezone' => 'Europe/Berlin',
	'locale' => 'de_DE.utf-8',
	'language' => 'de-DE',

	// @see  http://php.net/strftime for more information.
	'date_format_list' => '%d.%B %Y',
	'date_format_list_with_time' => '%d.%B %Y %H:%m',
	'date_format_form' => '%d.%m.%Y',

	'search_minlength' => 3,
	'rows_per_page' => 40,
	'invoice_no_start' => 22500,

	// Define the user min-length.
	'password_minlength' => 5,
	'password_prevent_copynpaste' => true
);