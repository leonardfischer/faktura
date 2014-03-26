<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Controller class "User"
 *
 * @category    Controller
 * @package     Faktura
 * @author      Leonard Fischer <post@leonardfischer.de>
 * @copyrights  2014 Leonard Fischer
 * @version     1.0
 */
class Controller_User extends Controller_Base
{
	/**
	 * Login action
	 */
	public function action_login()
	{
		$this->template = View::factory('_login');

		// If a user is already logged in, we redirect him back to the system.
		if ($this->auth->logged_in())
		{
			$this->redirect(Route::url('default'));
		} // if

		$post = $this->request->post();

		if (isset($post['username']) && isset($post['password']))
		{
			$success = $this->auth->login($post['username'], $post['password'], isset($post['remember']));

			if ($success)
			{
				$this->redirect(Route::url('default'));
			}
			else
			{
				$this->template->set('error', __('The username / password combination does not exist!'));
			} // if
		} // if
	} // function


	/**
	 * Logout action
	 */
	public function action_logout()
	{
		$this->auth->logout(true);

		$this->redirect(Route::url('user', array('action' => 'login')));
	} // function


	/**
	 * "New" action for creating a new user.
	 */
	public function action_new()
	{
		$id = $this->request->param('id');

		if ($id !== null)
		{
			$this->redirect(Route::url('user', array('action' => 'edit', 'id' => $id)));
		} // if

		$model = ORM::factory('user');
		$is_admin = in_array('admin', $this->user_roles);

		$this->content = View::factory('user/form', array(
			'title' => __('Create new user'),
			'user_model' => $model,
			'properties' => $model->get_properties(),
			'is_admin' => $is_admin,
			'copy_n_paste' => $this->config->get('password_prevent_copynpaste', true),
			'ajax_url' => Route::url('user', array('action' => 'save'))
		));

		if ($is_admin)
		{
			$this->content->roles = ORM::factory('role')->find_all();
		} // if
	} // function


	/**
	 * "Edit" action for loading the form template and filling it with data.
	 */
	public function action_edit()
	{
		$id = $this->request->param('id');

		if ($id === null)
		{
			$this->redirect(Route::url('user', array('action' => 'new')));
		} // if

		if ($id != $this->auth->get_user()->id && ! in_array('admin', $this->user_roles))
		{
			throw new HTTP_Exception_403('You are not allowed to view the page ":page"', array(':page' => __('User')));
		} // if

		$model = ORM::factory('user')->where('id', '=', $id)->find();
		$is_admin = in_array('admin', $this->user_roles);

		$this->content = View::factory('user/form', array(
			'title' => __('Update user ":username"', array(':username' => $model->username)),
			'user_model' => $model,
			'properties' => $model->get_properties(),
			'is_admin' => $is_admin,
			'copy_n_paste' => $this->config->get('password_prevent_copynpaste', true),
			'ajax_url' => Route::url('user', array('action' => 'save', 'id' => $id))
		));

		if ($is_admin)
		{
			$this->content->roles = ORM::factory('role')->find_all();
		} // if
	} // function


	/**
	 * "List" action for loading the complete user-list.
	 */
	public function action_list()
	{
		if (! in_array('admin', $this->user_roles))
		{
			throw new HTTP_Exception_403('You are not allowed to view the page ":page"', array(':page' => __('User')));
		} // if

		$users = ORM::factory('user')->order_by('username', 'ASC');

		if ($this->request->is_ajax())
		{
			$this->ajax_list($users, $this->config->get('rows_per_page', 40));
		}
		else
		{
			$this->content = View::factory('user/list', array(
				'count' => $users->count_all()
			));
		} // if
	} // function


	/**
	 * "Save" action for creating/updating a user. This will be called via ajax.
	 */
	public function action_save()
	{
		if (! $this->request->is_ajax())
		{
			throw new HTTP_Exception_403(__('This action may only be called via ajax!'));
		} // if

		$model = ORM::factory('user');
		$id = $this->request->param('id', 0);

		if ($id != $this->auth->get_user()->id && ! in_array('admin', $this->user_roles))
		{
			throw new HTTP_Exception_403('You are not allowed to view the page ":page"', array(':page' => __('User')));
		} // if

		try
		{
			$values = array(
				'username' => $this->request->post('inputUsername') ?: '',
				'email' => $this->request->post('inputEmail') ?: '',
				'password' => $this->request->post('inputPassword') ?: '',
				'password_confirm' => $this->request->post('inputPassword_confirm') ?: '',
			);

			if ($id > 0)
			{
				$model
					->where('id', '=', $this->request->param('id'))
					->find()
					->update_user($values);
			}
			else
			{
				// User shall always receive the "login" role.
				$model->create_user($values, null)->add('roles', ORM::factory('role')->where('name', '=', 'login')->find());
			} // if

			$roles = ORM::factory('role')->find_all();

			// We iterate over all roles and add / remove them if needed.
			foreach ($roles as $role)
			{
				if ($role->name == 'login')
				{
					continue;
				} // if

				if ($this->request->post('inputRole' . ucfirst($role->name)) && ! $model->has('roles', $role))
				{
					$model->add('roles', $role);
				} // if

				if (! $this->request->post('inputRole' . ucfirst($role->name)) && $model->has('roles', $role))
				{
					$model->remove('roles', $role);
				} // if
			} // foreach

			$result = array(
				'success' => true,
				'message' => null,
				'data' => null
			);
		}
		catch (ORM_Validation_Exception $e)
		{
			$result = array(
				'success' => false,
				'message' => $e->getMessage(),
				'data' => $e->errors('')
			);
		} // try

		if ($result['success'] && substr($_SERVER['HTTP_REFERER'], -4) == '/new')
		{
			// This will trigger a page reload (enabling the print buttons and stuff...).
			$result['data'] = Route::url('user', array('action' => 'edit', 'id' => $model->id));
		} // if

		$this->auto_render = false;

		// Write the JSON directly to the response body.
		$this->response->body(json_encode($result));
	} // function
} // class