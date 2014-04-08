<?php defined('SYSPATH') or die('No direct script access.');

// -- Environment setup --------------------------------------------------------

// Load the core Kohana class
require SYSPATH.'classes/Kohana/Core'.EXT;

if (is_file(APPPATH.'classes/Kohana'.EXT))
{
	// Application extends the core
	require APPPATH.'classes/Kohana'.EXT;
}
else
{
	// Load empty core extension
	require SYSPATH.'classes/Kohana'.EXT;
}

/**
 * Enable the Kohana auto-loader.
 *
 * @link http://kohanaframework.org/guide/using.autoloading
 * @link http://www.php.net/manual/function.spl-autoload-register
 */
spl_autoload_register(array('Kohana', 'auto_load'));

/**
 * Enable the Kohana auto-loader for unserialization.
 *
 * @link http://www.php.net/manual/function.spl-autoload-call
 * @link http://www.php.net/manual/var.configuration#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');

// -- Configuration and initialization -----------------------------------------

/**
 * Set Kohana::$environment if a 'KOHANA_ENV' environment variable has been supplied.
 *
 * Note: If you supply an invalid environment name, a PHP warning will be thrown
 * saying "Couldn't find constant Kohana::<INVALID_ENV_NAME>"
 */
Kohana::$environment = Kohana::PRODUCTION;

/**
 * Initialize Kohana, setting the default options.
 *
 * The following options are available:
 *
 * - string   base_url    path, and optionally domain, of your application   NULL
 * - string   index_file  name of your index file, usually "index.php"       index.php
 * - string   charset     internal character set used for input and output   utf-8
 * - string   cache_dir   set the internal cache directory                   APPPATH/cache
 * - integer  cache_life  lifetime, in seconds, of items cached              60
 * - boolean  errors      enable or disable error handling                   TRUE
 * - boolean  profile     enable or disable internal profiling               TRUE
 * - boolean  caching     enable or disable internal caching                 FALSE
 * - boolean  expose      set the X-Powered-By header                        FALSE
 */
Kohana::init(array(
	'base_url' => '/',
	'index_file' => false,
	'errors' => (Kohana::$environment == Kohana::DEVELOPMENT),
	'profile' => (Kohana::$environment == Kohana::DEVELOPMENT),
	'caching' => (Kohana::$environment == Kohana::PRODUCTION),
	'expose' => false
));

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
Kohana::$log->attach(new Log_File(APPPATH.'logs'));

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Config_File);

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules(array(
	'auth' => MODPATH . 'auth', // Basic authentication
	// 'cache' => MODPATH.'cache', // Caching with multiple backends
	'database' => MODPATH . 'database', // Database access
	'orm' => MODPATH . 'orm', // Object Relationship Mapping
));

// Load Base configuration and set some constants.
$config = Kohana::$config->load('base');

// Set the default time zone.
date_default_timezone_set($config['timezone']);
// Set the default locale.
setlocale(LC_ALL, $config['locale']);
// Set the default language.
I18n::lang($config['language']);

// Setting some constants.
define('SYSTEM_VERSION', '1.1.0');
define('DS', DIRECTORY_SEPARATOR);
define('ORM_ID', 'id');
define('ORM_DATE', 'date');
define('ORM_INT', 'integer');
define('ORM_FLOAT', 'float');
define('ORM_STRING', 'string');
define('ORM_TEXT', 'text');
define('FORM_TYPE_TEXT', 'text');
define('FORM_TYPE_TEXTAREA', 'textarea');
define('FORM_TYPE_EMAIL', 'email');
define('FORM_TYPE_MONEY', 'money');
define('FORM_TYPE_CHECKBOX', 'checkbox');
define('FORM_TYPE_DATE', 'date');
define('FORM_TYPE_SELECT', 'select');
define('INVOICE_FILTER_ALL', 'all');
define('INVOICE_FILTER_OPEN', 'open');
define('INVOICE_FILTER_REMINDER', 'reminder');

// Set the routes. Each route must have a minimum of a name, a URI and a set of defaults for the URI.
Route::set('invoice', 'invoice(/<action>(/<id>))')->defaults(array('controller' => 'invoice', 'action' => 'list'))
	->set('customer', 'customer(/<action>(/<id>))')->defaults(array('controller' => 'customer', 'action' => 'list'))
	->set('supplier', 'supplier(/<action>(/<id>))')->defaults(array('controller' => 'supplier', 'action' => 'list'))
	->set('supplier', 'supplier(/<action>(/<id>))')->defaults(array('controller' => 'supplier', 'action' => 'list'))
	->set('print', 'print/<action>(/<id>(/<misc>))', array('misc' => '.*'))->defaults(array('controller' => 'print'))
	->set('user', 'user(/<action>(/<id>))')->defaults(array('controller' => 'user', 'action' => 'login'))
	->set('default', '(<controller>(/<action>(/<id>)))')->defaults(array('controller' => 'dashboard', 'action' => 'index'));

View::set_global('config', $config);