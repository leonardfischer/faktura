<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Controller class "Customer"
 *
 * @category    Controller
 * @package     Faktura
 * @author      Leonard Fischer <post@leonardfischer.de>
 * @copyrights  2014 Leonard Fischer
 * @version     1.0
 */
class Controller_Customer extends Controller_Base
{
	/**
	 * Necessary "before" Method, which checks if the user is allowed to execute this controller.
	 */
	public function before()
	{
		parent::before();

		if (! (in_array('admin', $this->user_roles) || in_array('customers', $this->user_roles)))
		{
			throw new HTTP_Exception_403('You are not allowed to view the page ":page"', array(':page' => __('Customers')));
		} // if
	} // function

	/**
	 * "New" action for creating a new customer.
	 */
	public function action_new()
	{
		$id = $this->request->param('id');

		if ($id !== null)
		{
			$this->redirect(Route::url('customer', array('action' => 'edit', 'id' => $id)));
		} // if

		$model = ORM::factory('customer');

		$this->content = View::factory('customer/form', array(
			'title' => __('Create new customer'),
			'customer' => $model,
			'properties' => $model->get_properties(),
			'ajax_url' => Route::url('customer', array('action' => 'save'))
		));
	} // function


	/**
	 * "Edit" action for loading the form template and filling it with data.
	 */
	public function action_edit()
	{
		$id = $this->request->param('id');

		if ($id === null)
		{
			$this->redirect(Route::url('customer', array('action' => 'new')));
		} // if

		$model = ORM::factory('customer')->where('id', '=', $id)->find();

		$this->content = View::factory('customer/form', array(
			'title' => __('Edit customer ":customer"', array(':customer' => $model->company ?: $model->name)),
			'customer' => $model,
			'properties' => $model->get_properties(),
			'ajax_url' => Route::url('customer', array('action' => 'save', 'id' => $id)),
			'invoices' => $model->invoices->order_by('id', 'DESC')->limit(20)->find_all()
		));
	} // function


	/**
	 * "List" action for loading the complete customer-list.
	 */
	public function action_list()
	{
		$customers = ORM::factory('customer')->order_by('company', 'ASC');

		if ($this->request->is_ajax())
		{
			$this->ajax_list($customers, $this->config->get('rows_per_page', 40));
		}
		else
		{
			$this->content = View::factory('customer/list', array(
				'count' => $customers->count_all()
			));
		} // if
	} // function


	/**
	 * "Save" action for creating/updating a customer. This will be called via ajax.
	 */
	public function action_save()
	{
		if (! $this->request->is_ajax())
		{
			throw new HTTP_Exception_403(__('This action may only be called via ajax!'));
		} // if

		$values = array();
		$model = ORM::factory('customer');

		if ($this->request->param('id', 0) > 0)
		{
			$model->where('id', '=', $this->request->param('id'))->find();
		} // if

		$properties = $model->get_properties();

		foreach ($properties as $key => $property)
		{
			if ($property['form'])
			{
				$values[$key] = $this->request->post('input' . ucfirst($key), null);
			} // if
		} // foreach

		try
		{
			$model->values($values)->save();

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
			$result['data'] = Route::url('customer', array('action' => 'edit', 'id' => $model->id));
		} // if

		$this->auto_render = false;

		// Write the JSON directly to the response body.
		$this->response->body(json_encode($result));
	} // function


	public function action_browser ()
	{
		$selection = $this->request->post('selection') ?: 0;
		$receiver = $this->request->post('receiver') ?: false;

		$this->auto_render = false;

		$orm = ORM::factory('customer');
		$labels = $orm->labels();

		$browser_content = View::factory('popups/browser', array(
			'title' => __('Customer browser'),
			'receiver' => $receiver,
			'selection' => $selection,
			'table_header' => array($labels['name'], $labels['company'], __('Address')),
			'exclude' => array('action', 'allowance', 'email'),
			'search_url' => Route::url('customer', array('action' => 'search')),
			'data' => $orm->find_all(),
			'data_count' => $orm->count_all(),
		));

		$this->response->body($browser_content);
	} // function


	/**
	 * Search action, works with a search string and given filter.
	 *
	 * @throws  HTTP_Exception_403
	 */
	public function action_search()
	{
		if (! $this->request->is_ajax())
		{
			throw new HTTP_Exception_403(__('This action may only be called via ajax!'));
		} // if

		$minlength = $this->config->get('search_minlength', 3);
		$search = $this->request->post('search');
		$exclude = $this->request->post('exclude') ?: array();

		$result = array(
			'success' => true,
			'message' => null,
			'data' => null
		);

		if (! empty($search) && strlen($search) >= $minlength)
		{
			// Here we collect the data from all necessary tables.
			$customers = ORM::factory('customer')
				->where('name', 'LIKE', '%' . $search . '%')
				->or_where('company', 'LIKE', '%' . $search . '%')
				->or_where('email', 'LIKE', '%' . $search . '%')
				->or_where('street', 'LIKE', '%' . $search . '%')
				->or_where('zip_code', 'LIKE', '%' . $search . '%')
				->or_where('city', 'LIKE', '%' . $search . '%')
				->find_all();

			foreach ($customers as $customer)
			{
				// We only want the related invoice.
				$result['data'][] = $customer->get_table_data($exclude);
			} // foreach
		} // if

		$this->auto_render = false;

		// Write the JSON directly to the response body.
		$this->response->body(json_encode($result));
	} // function
} // class