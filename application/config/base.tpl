<?php defined('SYSPATH') OR die('No direct access allowed.');

return array
(
	'version' => '%version%',

	'title' => '%title%',
	'theme' => '%theme%',
	'theme_options' => array(
		'table_transparency' => true,
		'popup_blur' => true
	),

	// Used for the Kohana bootstrap.
	'timezone' => '%timezone%',
	'locale' => '%locale%',
	'language' => '%language%',

	// @see  http://php.net/strftime for more information.
	'date_format_list' => '%d.%B %Y',
	'date_format_list_with_time' => '%d.%B %Y %H:%m',
	'date_format_form' => '%d.%m.%Y',

	'search_minlength' => 3,
	'search_wordsplit' => '%search_wordsplit%',
	'rows_per_page' => %rows_per_page%,
	'invoice_no_start' => %invoice_start_no%,

	// Define the user min-length.
	'password_minlength' => 5,
	'password_prevent_copynpaste' => true
);