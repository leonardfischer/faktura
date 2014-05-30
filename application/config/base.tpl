<?php defined('SYSPATH') OR die('No direct access allowed.');

return array
(
	'version' => '%version%',

    // General configuration.
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

    // Mailer configuration, will be used for "reset password" function
    'mail_faktura' => '%mail_faktura%',
    'mail_transport' => '%mail_transport%', // Possible options are "mail", "sendmail" and "smtp"
    'mail_smtp_host' => '%mail_smtp_host%',
    'mail_smtp_port' => '%mail_smtp_port%',
    'mail_smtp_user' => '%mail_smtp_user%',
    'mail_smtp_pass' => '%mail_smtp_pass%',
    'mail_sendmail_command' => '%mail_sendmail_command%',

	// @see  http://php.net/strftime for more information.
	'date_format_list' => '%d.%B %Y',
	'date_format_list_with_time' => '%d.%B %Y %H:%m',
	'date_format_form' => '%d.%m.%Y',

    // Some search and list options.
	'search_minlength' => %search_minlength%,
	'search_wordsplit' => '%search_wordsplit%',
	'rows_per_page' => %rows_per_page%,
	'invoice_no_start' => %invoice_start_no%,

	// Define the user min-length.
	'password_minlength' => %password_minlength%,
	'password_prevent_copynpaste' => %password_prevent_copynpaste%
);