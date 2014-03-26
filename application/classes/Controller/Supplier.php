<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Controller class "Supplier"
 *
 * @category    Controller
 * @package     Faktura
 * @author      Leonard Fischer <post@leonardfischer.de>
 * @copyrights  2014 Leonard Fischer
 * @version     1.0
 */
class Controller_Supplier extends Controller_Base
{
	/**
	 * Necessary "before" Method, which checks if the user is allowed to execute this controller.
	 */
	public function before()
	{
		parent::before();

		if (! (in_array('admin', $this->user_roles) || in_array('suppliers', $this->user_roles)))
		{
			throw new HTTP_Exception_403('You are not allowed to view the page ":page"', array(':page' => __('Suppliers')));
		} // if
	} // function


	/**
	 * "New" action for creating a new supplier.
	 */
	public function action_new()
	{
		$id = $this->request->param('id');

		if ($id !== null)
		{
			$this->redirect(Route::url('supplier', array('action' => 'edit', 'id' => $id)));
		} // if

		$model = ORM::factory('supplier');

		$this->content = View::factory('supplier/form', array(
			'title' => __('Create new supplier'),
			'supplier' => $model,
			'properties' => $model->get_properties(),
			'ajax_url' => Route::url('supplier', array('action' => 'save'))
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
			$this->redirect(Route::url('supplier', array('action' => 'new')));
		} // if

		$model = ORM::factory('supplier')->where('id', '=', $id)->find();

		$this->content = View::factory('supplier/form', array(
			'title' => __('Edit supplier ":supplier"', array(':supplier' => $model->company)),
			'supplier' => $model,
			'properties' => $model->get_properties(),
			'ajax_url' => Route::url('supplier', array('action' => 'save', 'id' => $id))
		));
	} // function


	/**
	 * "List" action for loading the complete supplier-list.
	 */
	public function action_list()
	{
		$suppliers = ORM::factory('supplier')->order_by('company', 'ASC');

		if ($this->request->is_ajax())
		{
			$this->ajax_list($suppliers, $this->config->get('rows_per_page', 40));
		}
		else
		{
			$this->content = View::factory('supplier/list', array(
				'count' => $suppliers->count_all()
			));
		} // if
	} // function


	/**
	 * "Save" action for creating/updating a supplier. This will be called via ajax.
	 */
	public function action_save()
	{
		if (! $this->request->is_ajax())
		{
			throw new HTTP_Exception_403(__('This action may only be called via ajax!'));
		} // if

		$values = array();
		$model = ORM::factory('supplier');

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
			$result['data'] = Route::url('supplier', array('action' => 'edit', 'id' => $model->id));
		} // if

		$this->auto_render = false;

		// Write the JSON directly to the response body.
		$this->response->body(json_encode($result));
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

		$result = array(
			'success' => true,
			'message' => null,
			'data' => null
		);

		if (! empty($search) && strlen($search) >= $minlength)
		{
			// Here we collect the data from all necessary tables.
			$suppliers = ORM::factory('supplier')
				->where('name', 'LIKE', '%' . $search . '%')
				->or_where('company', 'LIKE', '%' . $search . '%')
				->or_where('email', 'LIKE', '%' . $search . '%')
				->find_all();

			foreach ($suppliers as $supplier)
			{
				// We only want the related invoice.
				$result['data'][] = $supplier->get_table_data();
			} // foreach
		} // if

		$this->auto_render = false;

		// Write the JSON directly to the response body.
		$this->response->body(json_encode($result));
	} // function
} // class