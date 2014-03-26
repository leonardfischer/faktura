<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Invoice extends Controller_Base
{
	/**
	 * Necessary "before" Method, which checks if the user is allowed to execute this controller.
	 */
	public function before()
	{
		parent::before();

		if (! (in_array('admin', $this->user_roles) || in_array('invoices', $this->user_roles)))
		{
			throw new HTTP_Exception_403('You are not allowed to view the page ":page"', array(':page' => __('Invoices')));
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
			$this->redirect(Route::url('invoice', array('action' => 'edit', 'id' => $id)));
		} // if

		$model = ORM::factory('invoice')->set('customer_id', $this->request->query('customer_id'));

		// Simulate auto increment.
		$model->invoice_no = ORM::factory('invoice')->order_by('id', 'DESC')->find()->id + $this->config->get('invoice_no_start', 1);

		// Set the "created at" date to today.
		$model->invoice_date = date('Y-m-d');

		$this->content = View::factory('invoice/form', array(
			'title' => __('Create new invoice'),
			'invoice' => $model,
			'customers' => ORM::factory('customer')->get_customers_for_selection(),
			'properties' => $model->get_properties(),
			'ajax_url' => Route::url('invoice', array('action' => 'save')),
			'credit_popup_ajax_url' => Route::url('invoice', array('action' => 'get_positions', 'id' => $id))
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
			$this->redirect(Route::url('invoice', array('action' => 'new')));
		} // if

		$model = ORM::factory('invoice')->where('id', '=', $id)->find();

		$this->content = View::factory('invoice/form', array(
			'title' => __('Create new invoice'),
			'invoice' => $model,
			'customers' => ORM::factory('customer')->get_customers_for_selection(),
			'properties' => $model->get_properties(),
			'ajax_url' => Route::url('invoice', array('action' => 'save', 'id' => $id)),
			'credit_popup_ajax_url' => Route::url('invoice', array('action' => 'get_positions', 'id' => $id))
		));
	} // function


	/**
	 * List method for displaying the invoice list (depending on the given filter.
	 *
	 * @param  Model_Invoice  $orm_result
	 * @param  string         $filter_type
	 */
	public function action_list(Model_Invoice $orm_result = null, $filter_type = INVOICE_FILTER_ALL)
	{
		if ($orm_result === null)
		{
			$orm_result = ORM::factory('invoice')
				->order_by('id', 'DESC');
		} // if

		$types = ORM::factory('invoice')->get_types();
		$selected_filter = $types[$filter_type];

		if ($this->request->is_ajax())
		{
			$this->ajax_list($orm_result, $this->config->get('rows_per_page', 40));
		}
		else
		{
			$this->content = View::factory('invoice/list', array(
				'count' => $orm_result->count_all(),
				'selected_filter' => $selected_filter,
				'filter' => $filter_type
			));
		} // if
	} // function


	/**
	 * This method will handle the three filter states and call the "index" action with a predefined Database_Result.
	 */
	public function action_filter()
	{
		$filter_type = Request::$current->param('id', INVOICE_FILTER_ALL);

		switch ($filter_type)
		{
			case INVOICE_FILTER_OPEN:
				$orm_result = ORM::factory('invoice')
					->where('paid_on_date', '=', null)
					->order_by('id', 'DESC');
				break;

			case INVOICE_FILTER_REMINDER:
				$orm_result = ORM::factory('invoice')
					->get_reminder_invoices()
					->order_by('id', 'DESC');
				break;

			default:
			case INVOICE_FILTER_ALL:
				$orm_result = null;
				break;
		} // switch

		$this->action_list($orm_result, $filter_type);
	} // function


	/**
	 * "Save" action for creating/updating a invoice. This will be called via ajax.
	 */
	public function action_save()
	{
		if (! $this->request->is_ajax())
		{
			throw new HTTP_Exception_403(__('This action may only be called via ajax!'));
		} // if

		$values = array();
		$model = ORM::factory('invoice');

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

		// If the invoice was saved successfully, we remove all positions and add them new.
		if ($result['success'])
		{
			// This is used for the positions.
			$posts = $this->request->post();
			$position_model = ORM::factory('invoicePosition');

			// First we delete all positions - they will be added in the next step.
			DB::delete('invoice_positions')->where('invoice_id', '=', $model->id)->execute();

			// (Re-) create all found positions.
			foreach ($posts as $key => $value)
			{
				if (strpos($key, 'inputPositionText-') === 0)
				{
					$no = substr($key, 18);

					$position_model
						->clear()
						->values(array(
							'invoice_id' => $model->id,
							'description' => $this->request->post('inputPositionText-' . $no, null),
							'amount' => $this->request->post('inputPositionAmount-' . $no, null),
							'price' => $this->request->post('inputPositionPrice-' . $no, null)
						))->save();
				} // if
			} // foreach

			if (substr($_SERVER['HTTP_REFERER'], -4) == '/new')
			{
				// This will trigger a page reload (enabling the print buttons and stuff...).
				$result['data'] = Route::url('invoice', array('action' => 'edit', 'id' => $model->id));
			} // if
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
	public function action_search ()
	{
		if (! $this->request->is_ajax())
		{
			throw new HTTP_Exception_403(__('This action may only be called via ajax!'));
		} // if

		$minlength = $this->config->get('search_minlength', 3);
		$search = $this->request->post('search');
		$filter = $this->request->post('filter');

		$result = array(
			'success' => true,
			'message' => null,
			'data' => null
		);

		if (! empty($search) && strlen($search) >= $minlength)
		{
			$invoice_result = array();

			// Here we collect the data from all necessary tables.
			$invoices = ORM::factory('invoice')->where('invoice_no', 'LIKE', '%' . $search . '%')->find_all();
			$customer_invoices = ORM::factory('customer')->where('company', 'LIKE', '%' . $search . '%')->find_all();
			$positions = ORM::factory('invoicePosition')->where('description', 'LIKE', '%' . $search . '%')->find_all();

			foreach ($invoices as $invoice)
			{
				$invoice_result[] = $invoice;
			} // foreach

			foreach ($customer_invoices as $customer_invoice)
			{
				$related_invoices = $customer_invoice->invoices->find_all();

				foreach ($related_invoices as $invoice)
				{
					$invoice_result[] = $invoice;
				} // foreach
			} // foreach

			foreach ($positions as $position)
			{
				$invoice_result[] = $position->invoice;
			} // foreach

			foreach ($invoice_result as $invoice)
			{
				// Saves a bit of performance... We already found this invoice, so we skip the rest.
				if (isset($result['data'][$invoice->id]))
				{
					continue;
				} // if

				if ($filter == INVOICE_FILTER_OPEN && $invoice->paid_on_date !== null)
				{
					continue;
				} // if

				if ($filter == INVOICE_FILTER_REMINDER && ($invoice->paid_on_date !== null || strtotime($invoice->invoice_date) > strtotime('-2 weeks')))
				{
					continue;
				} // if

				// We only want the related invoice.
				$result['data'][] = $invoice->get_table_data();
			} // foreach
		} // if

		$this->auto_render = false;

		// Write the JSON directly to the response body.
		$this->response->body(json_encode($result));
	} // function


	/**
	 * Get_positions action for loading the necessary information for the credit-print template.
	 *
	 * @throws  HTTP_Exception_403
	 * @throws  HTTP_Exception_404
	 */
	public function action_get_positions ()
	{
		if (! $this->request->is_ajax())
		{
			throw new HTTP_Exception_403(__('This action may only be called via ajax!'));
		} // if

		$id = $this->request->param('id');

		try
		{
			if ($id === null)
			{
				throw new HTTP_Exception_404(__('No invoice ID was given!'));
			} // if

			$result = array(
				'success' => true,
				'message' => null,
				'data' => array()
			);

			$positions = ORM::factory('invoice')->where('id', '=', $id)->find()->positions->find_all();

			foreach ($positions as $position)
			{
				$result['data'][] = array(
					'id' => $position->id,
					'description' => nl2br($position->description),
					'amount' => $position->amount,
					'price' => $position->price
				);
			} // foreach
		}
		catch (Exception $e)
		{
			$result = array(
				'success' => false,
				'message' => $e->getMessage(),
				'data' => null
			);
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
		if ($this->auto_render)
		{
			$this->content
				->set('open_invoices', ORM::factory('invoice')->where('paid_on_date', '=', null)->order_by('id', 'DESC')->count_all())
				->set('reminder_invoices', ORM::factory('invoice')->get_reminder_invoices()->count_all());
		} // if

		parent::after();
	} // function
} // class