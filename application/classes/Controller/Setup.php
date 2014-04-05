<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Controller class "Setup"
 *
 * @category    Controller
 * @package     Faktura
 * @author      Leonard Fischer <post@leonardfischer.de>
 * @copyrights  2014 Leonard Fischer
 * @version     1.0
 * @since       1.1
 */
class Controller_Setup extends Controller_Template
{
	/**
	 * This variable will hold the global View template.
	 * @var  View  page template
	 */
	public $template = 'setup/_base';

	/**
	 * This variable will hold the content View template.
	 * @var  View  page template
	 */
	public $content = null;

	/**
	 * This array will hold data, sent from the frontend.
	 * @var  array
	 */
	private $factura_data = array();

	/**
	 * This array will hold all the errors, which occur during the final setup step.
	 * @var  array
	 */
	private $setup_errors = array();


	/**
	 * The index action will start the setup process.
	 */
	public function action_index()
	{
		$current_step = $this->request->post('step') ?: 1;
		$special_step = $this->request->post('special_step') ?: false;

		// This function works like "http_build_query()" - but backwards.
		parse_str($this->request->post('factura_data'), $this->factura_data);

		if ($special_step)
		{
			if (! $this->request->is_ajax())
			{
				throw new HTTP_Exception_403(__('This action may only be called via ajax!'));
			} // if

			$this->response->headers('Content-Type', 'application/json');

			switch ($special_step)
			{
				case 'test_database_connection':
					$this->test_database_connection();
					break;
			}
		}
		else
		{
			switch ($current_step)
			{
				default:
				case 1:
					$this->intro();
					break;

				case 2:
					$this->database_config();
					break;

				case 3:
					$this->define_admin_user();
					break;

				case 4:
					$this->init_setup();
					break;
			} // switch
		} // if

		if ($this->request->is_ajax())
		{
			$this->auto_render = false;
		} // if
	} // function


	/**
	 * This is the first step of the setup routine.
	 * Here we check some simple stuff, like needed extensions etc.
	 */
	private function intro ()
	{
		$this->content = View::factory('setup/intro');

		$errors = false;
		$short_tags_off = null;
		$ok = '<span class="text-success">OK</span>';

		if (! ini_get('short_open_tag'))
		{
			$errors = true;
			$short_tags_off = '<span class="text-danger">No, please update your php.ini</span>';
		} // if

		$this->content
			->set('short_tags', $short_tags_off ?: $ok)
			->set('errors', $errors);
	} // function


	/**
	 * In this step we display a form for the user to input the database configuration.
	 */
	private function database_config ()
	{
		$this->content = View::factory('setup/database_config');
	} // function


	/**
	 * In this step the user needs to input his "administration" user data.
	 */
	private function define_admin_user ()
	{
		$this->content = View::factory('setup/define_admin_user');
	} // function


	/**
	 * This is the final step where we create the config files, create the database, import the database and create an admin-user.
	 */
	private function init_setup ()
	{
		$this
			->create_config_files()
			->create_database()
			->import_database()
			->create_admin_user()
			->cleanup_setup();

		$this->content = View::factory('setup/setup_complete')
			->set('errors', $this->setup_errors);
	} // function


	/**
	 * This method will test the given database credentials!
	 */
	private function test_database_connection ()
	{
		$hostname = $this->request->post('hostname');
		$username = $this->request->post('username');
		$password = $this->request->post('password');
		$database = $this->request->post('database');

		try
		{
			if (empty($hostname))
			{
				throw new Exception('You need to provide a host, for example "localhost" or "127.0.0.1".');
			} // if

			if (empty($database))
			{
				throw new Exception('You need to provide a database name, for example "faktura".');
			} // if

			$mysqli = new mysqli($hostname, $username, $password);

			// Check connection.
			if ($mysqli->connect_errno)
			{
				throw new Exception($mysqli->connect_error);
			} // if

			// Check if server is alive.
			if (! $mysqli->ping())
			{
				throw new Exception($mysqli->connect_error);
			} // if

			// If we have not thrown any exceptions yet, we're good to go!
			$res = $mysqli->query('SHOW DATABASES LIKE "' . $mysqli->escape_string($database) . '";');

			if ($res->num_rows === 0)
			{
				$this->content = json_encode(array(
					'success' => true,
					'data' => array(
						'hostname' => $hostname,
						'username' => $username,
						'password' => $password,
						'database' => $database
					),
					'message' => 'Everything seems okay!'
				));

				// And finally close connection.
				$mysqli->close();
			}
			else
			{
				// And finally close connection.
				$mysqli->close();

				throw new Exception('The given database "' . $database . '" already exists. Please drop it first or choose another name.');
			} // if
		}
		catch (Exception $e)
		{
			$this->content = json_encode(array(
				'success' => false,
				'data' => null,
				'message' => $e->getMessage()
			));
		} // try

		$this->response->headers('Content-Type', 'application/json');
	} // function


	/**
	 * This method will create the "auth.php" and "database.php".
	 *
	 * @todo    Also create the "base.php"
	 * @return  Controller_Setup
	 */
	private function create_config_files ()
	{
		$config_path = APPPATH . 'config' . DS;

		// Writing the auth.php with a random "hash_key".
		$auth_tpl = file_get_contents($config_path . 'auth.tpl');
		$auth_content = str_replace('%hash_key%', Text::random(null, 8), $auth_tpl);
		file_put_contents($config_path . 'auth.php', $auth_content);

		// Writing the database.php with the user input.
		$database_tpl = file_get_contents($config_path . 'database.tpl');
		$database_content = str_replace(array(
			'%hostname%',
			'%database%',
			'%username%',
			'%password%'
		), array(
			$this->factura_data['db_config']['hostname'],
			$this->factura_data['db_config']['database'],
			$this->factura_data['db_config']['username'],
			$this->factura_data['db_config']['password']
		), $database_tpl);
		file_put_contents($config_path . 'database.php', $database_content);

		return $this;
	} // function


	/**
	 * This method will create the core dabase.
	 *
	 * @return  Controller_Setup
	 */
	private function create_database ()
	{
		$mysqli = $this->connect_db();

		// If we have not thrown any exceptions yet, we're good to go!
		$success = $mysqli->query('CREATE DATABASE IF NOT EXISTS ' . $mysqli->escape_string($this->factura_data['db_config']['database']) . ' CHARACTER SET utf8 COLLATE utf8_general_ci;;');

		if (! $success)
		{
			$this->setup_errors[] = $mysqli->error;
		} // if

		$mysqli->close();

		return $this;
	} // function


	/**
	 * This method will import all necessary tables and data into the database.
	 *
	 * @return  Controller_Setup
	 */
	private function import_database ()
	{
		$mysqli = $this->connect_db();

		$mysqli->query('USE ' . $mysqli->escape_string($this->factura_data['db_config']['database']) . ';');

		$sql_dump = file_get_contents(DOCROOT . 'setup.sql');

		$queries = explode(";\r\n", $sql_dump);

		if (count($queries) <= 1)
		{
			$queries = explode(";\n", $sql_dump);
		} // if

		foreach ($queries as $query)
		{
			$result = $mysqli->query($query . ';');

			if (! $result && ! in_array($mysqli->error, $this->setup_errors))
			{
				$this->setup_errors[] = $mysqli->error;
			} // if
		} // foreach

		$mysqli->close();

		return $this;
	} // function


	/**
	 * This step will create the admin user and add two roles: "login" and "admin".
	 *
	 * @return  Controller_Setup
	 */
	private function create_admin_user ()
	{
		try
		{
			$admin = ORM::factory('user')->create_user(array(
				'username' => $this->factura_data['adminuser']['username'],
				'password' => $this->factura_data['adminuser']['password'],
				'password_confirm' => $this->factura_data['adminuser']['password'],
				'email' => $this->factura_data['adminuser']['email']
			), array(
				'username',
				'password',
				'email'
			));

			// After the user has been created, we add the login and admin role.
			$admin
				->add('roles', ORM::factory('role')->where('name', '=', 'login')->find())
				->add('roles', ORM::factory('role')->where('name', '=', 'admin')->find());
		}
		catch (ORM_Validation_Exception $e)
		{
			$this->setup_errors[] = $e->getMessage() . ': ' . implode(',', $e->errors(''));
		}
		catch (Database_Exception $e)
		{
			$this->setup_errors[] = $e->getMessage();
		} // try

		return $this;
	} // function


	/**
	 * This final step will rename the "setup.php" to "_setup.php" in case there were no errors.
	 * This prevents the setup from loading!
	 *
	 * @return  Controller_Setup
	 */
	private function cleanup_setup ()
	{
		if (count($this->setup_errors) == 0)
		{
			rename(DOCROOT . 'setup.php', DOCROOT . '_setup.php');
		} // if

		return $this;
	} // function


	/**
	 * This small helper prevents writing the same code multiple times - DRY.
	 *
	 * @return  mysqli
	 */
	private function connect_db ()
	{
		$mysqli = new mysqli($this->factura_data['db_config']['hostname'], $this->factura_data['db_config']['username'], $this->factura_data['db_config']['password']);

		// Check connection.
		if ($mysqli->connect_errno)
		{
			$this->setup_errors[] = $mysqli->connect_error;
		} // if

		// Check if server is alive.
		if (! $mysqli->ping())
		{
			$this->setup_errors[] = $mysqli->connect_error;
		} // if

		return $mysqli;
	} // function


	/**
	 * Necessary "after" Method, which assigns some stuff to the template.
	 */
	public function after()
	{
		View::set_global('basedir', Kohana::$base_url);

		if ($this->auto_render)
		{
			$this->template->set('content', $this->content);
		}
		else
		{
			$this->response->body($this->content);
		} // if

		parent::after();
	} // function
} // class