<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Controller class "Update"
 *
 * @category    Controller
 * @package     Faktura
 * @author      Leonard Fischer <post@leonardfischer.de>
 * @copyrights  2014 Leonard Fischer
 * @version     1.0
 * @since       1.1
 */
class Controller_Update extends Controller_Template
{
	/**
	 * This variable will hold the global View template.
	 * @var  View  page template
	 */
	public $template = 'update/_base';

	/**
	 * This variable will hold the content View template.
	 * @var  View  page template
	 */
	public $content = null;

	/**
	 * This array will hold data, sent from the frontend.
	 * @var  array
	 */
	private $faktura_data = array();

	/**
	 * This array will hold all the errors, which occur during the final update step.
	 * @var  array
	 */
	private $update_errors = array();

	/**
	 * This array will hold informations about the current update (version, requirements, queries, ...).
	 * @var  array
	 */
	private $update = array();


	/**
	 * The index action will start the update process.
	 */
	public function action_index()
	{
		// Only run the update, if the "update.php" exists!
		if (! file_exists(DOCROOT . 'update.php'))
		{
			$this->redirect('/');
		} // if

		$this->update = include DOCROOT . 'update.php';

		View::set_global('update', $this->update);

		$current_step = $this->request->post('step') ?: 1;
		$special_step = $this->request->post('special_step') ?: false;

		// This function works like "http_build_query()" - but backwards.
		parse_str($this->request->post('faktura_data'), $this->faktura_data);

		if ($special_step)
		{
			if (! $this->request->is_ajax())
			{
				throw new HTTP_Exception_403(__('This action may only be called via ajax!'));
			} // if

			$this->response->headers('Content-Type', 'application/json');

			switch ($special_step)
			{
				case 'special_step':
					// $this->special_step();
					break;
			} // switch
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
					$this->changelog();
					break;

				case 3:
					$this->input_user_config();
					break;

				case 4:
					$this->init_update();
					break;
			} // switch
		} // if

		if ($this->request->is_ajax())
		{
			$this->auto_render = false;
		} // if
	} // function


	/**
	 * This is the first step of the update routine.
	 * Here we check some simple stuff, like needed extensions etc.
	 */
	private function intro ()
	{
		$errors = false;
		$requirements = array();

		if (version_compare(phpversion(), '5.3.3', '<'))
		{
			$errors = true;
			$requirements['PHP version'] = '<span class="text-danger"><i class="fa fa-times"></i> At least PHP 5.3.3 is required, you are currently using ' . phpversion() . '</span>';
		}
		else
		{
			$requirements['PHP version'] = '<span class="text-success"><i class="fa fa-check"></i> PHP version >=5.3.3  (you are using ' . phpversion() . ')</span>';
		} // if

		if (! ini_get('short_open_tag'))
		{
			$errors = true;
			$requirements['PHP short tags'] = '<span class="text-danger"><i class="fa fa-times"></i> Switched off. Please update your php.ini!</span>';
		}
		else
		{
			$requirements['PHP short tags'] = '<span class="text-success"><i class="fa fa-check"></i> Switched on</span>';
		} // if

		$this->content = View::factory('update/intro')
			->set('config', Kohana::$config->load('base'))
			->set('requirements', $requirements)
			->set('errors', $errors);
	} // function


	/**
	 * In this second step we simply display the complete changelog.
	 */
	private function changelog ()
	{
		$this->content = View::factory('update/changelog');
	} // function


	/**
	 * This is the third step where the user can update his configuration.
	 */
	private function input_user_config ()
	{
		$l_base_config = Update::$base_vars;

		// Set some generic defaults.
		$l_base_config['mail_faktura'] = 'noreply@' . $_SERVER['SERVER_NAME'];

		$base_config = Kohana::$config->load('base')->as_array();
		$form_config = array();

		foreach ($l_base_config as $key => $default)
		{
			$form_config[$key] = (isset($base_config[$key])) ? $base_config[$key] : $default;
		} // foreach

		$this->content = View::factory('update/input_user_config', array(
			'values' => $form_config,
			'themes' => Model_User::get_themes(),
			'languages' => array(
				'de-DE' => 'German',
				'en_US' => 'English'
			),
			'mail_transports' => array(
				'mail' => 'Standard PHP mail',
				'smtp' => 'SMTP server',
				'sendmail' => 'Sendmail'
			),
			'boolean' => array(
				1 => 'Yes',
				0 => 'No'
			)
		));
	} // function


	/**
	 * This is the final step where we update the database and create clean up the installation.
	 */
	private function init_update ()
	{
		$this
			->update_config()
			->update_database()
			->cleanup_update();

		$this->content = View::factory('update/update_complete')
			->set('errors', $this->update_errors);
	} // function


	/**
	 * This method will be used to update the configuration files.
	 *
	 * @return  Controller_Update
	 */
	private function update_config ()
	{
		$data = array();

		foreach ($this->faktura_data['form_data'] as $key => $value)
		{
			// We use "substr" because our keys look like "inputTitle".
			$data[strtolower(substr($key, 5))] = $value;
		} // foreach

		$data['version'] = $this->update['version'];

		Update::update_base_config($data);

		return $this;
	} // function


	/**
	 * This method will import all necessary tables and data into the database.
	 *
	 * @return  Controller_Update
	 */
	private function update_database ()
	{
		$db = Database::instance();
		$update_queries = Update::process_queries($this->update['queries']);

		if (count($update_queries))
		{
			foreach ($update_queries as $queries)
			{
				$queries = explode(';', $queries);

				foreach ($queries as $query)
				{
					try
					{
						if (! empty($query))
						{
							$db->query(null, $query . ';');
						} // if
					}
					catch (Exception $e)
					{
						$this->update_errors[] = $e->getMessage();
					} // try
				} // foreach
			} // foreach
		} // if

		return $this;
	} // function


	/**
	 * This final step will rename the "update.php" to "_finished_update.php" in case there were no errors.
	 * This prevents the update from loading on every request!
	 *
	 * @return  Controller_Update
	 */
	private function cleanup_update ()
	{
		if (count($this->update_errors) == 0)
		{
			rename(DOCROOT . 'update.php', DOCROOT . '_finished_update.php');
		} // if

		return $this;
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