<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Base extends Controller_Template
{
    /**
     * This variable will hold the global View template.
     * @var  View  page template
     */
    public $template = '_base';

	/**
	 * This variable will hold the content View template.
	 * @var  View  page template
	 */
	public $content = null;

	/**
	 * This will hold the base configuration.
	 * @var  Config_Group
	 */
	protected $config = null;

	/**
	 * This array holds all the user roles.
	 * @var  array
	 */
	public $user_roles = array();

	/**
	 * This will hold our Auth object instance.
	 * @var  Auth
	 */
	public $auth = null;


	/**
	 * Necessary "before" Method, which checks if the user is logged in.
	 */
	public function before()
	{
		parent::before();

		$this->config = Kohana::$config->load('base');

		$this->auth = Auth::instance();

		if (! $this->auth->logged_in())
		{
			if ($this->request->action() != 'login')
			{
				$this->redirect(Route::url('user', array('action' => 'login')));
			} // if
		}
		else
		{
			// This foreach loop will collect all the assigned user-roles.
			$this->user_roles = $this->auth->get_user()->get_roles();

			View::set_global('config', $this->config);
			View::set_global('user', $this->auth->get_user());
			View::set_global('user_roles', $this->user_roles);
		} // if
	} // function


	/**
	 * Ajax list handler for pagination.
	 *
	 * @param  ORM      $orm
	 * @param  integer  $rows_per_page
	 */
	protected function ajax_list (ORM $orm, $rows_per_page = 40)
	{
		$result = array(
			'success' => true,
			'message' => null,
			'data' => null
		);

		try
		{
			$page = $this->request->post('page') ?: 1;
			$rows = array();

			$orm_result = $orm->offset(($page - 1) * $rows_per_page)->limit($rows_per_page)->find_all();

			foreach ($orm_result as $row)
			{
				$rows[] = $row->get_table_data();
			} // foreach

			$result['data'] = $rows;
		}
		catch (Exception $e)
		{
			$result['success'] = false;
			$result['message'] = $e->getMessage();
		} // try

		$this->auto_render = false;

		// Write the JSON directly to the response body.
		$this->response->body(json_encode($result));
	} // function


	/**
	 * Necessary "after" Method, which assigns some stuff to the template.
	 */
	public function after()
    {
        $this->template
	        ->set('content', $this->content)
            ->set('basedir', Kohana::$base_url);

        parent::after();
    } // function
} // class