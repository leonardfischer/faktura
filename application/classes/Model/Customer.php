<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Model class "Customer"
 *
 * @category    Model
 * @package     Faktura
 * @author      Leonard Fischer <post@leonardfischer.de>
 * @copyrights  2014 Leonard Fischer
 * @version     1.0
 */
class Model_Customer extends ORM
{
	/**
	 * Table name.
	 * @var  string
	 */
	protected $_table_name = 'customers';

	/**
	 * Auto-update columns for updates
	 * @var  array
	 */
	protected $_updated_column = array('column' => 'updated_at', 'format' => 'Y-m-d H:i:s');

	/**
	 * Auto-update columns for creation
	 * @var  array
	 */
	protected $_created_column = array('column' => 'created_at', 'format' => 'Y-m-d H:i:s');

	/**
	 * Array of all our model properties.
	 * @var  array
	 */
	protected $properties = array(
		'id' => array(
			'label' => 'ID',
			'type' => ORM_ID,
			'form_type' => null,
			'form' => false
		),
		'name' => array(
			'label' => 'Contact person',
			'type' => ORM_STRING,
			'form_type' => FORM_TYPE_TEXT,
			'form' => true
		),
		'company' => array(
			'label' => 'Company',
			'type' => ORM_STRING,
			'form_type' => FORM_TYPE_TEXT,
			'form' => true
		),
		'addition' => array(
			'label' => 'Addition',
			'type' => ORM_STRING,
			'form_type' => FORM_TYPE_TEXT,
			'form' => true
		),
		'addition2' => array(
			'label' => 'Addition 2',
			'type' => ORM_STRING,
			'form_type' => FORM_TYPE_TEXT,
			'form' => true
		),
		'street' => array(
			'label' => 'Street',
			'type' => ORM_STRING,
			'form_type' => FORM_TYPE_TEXT,
			'form' => true
		),
		'street_no' => array(
			'label' => 'Street no',
			'type' => ORM_STRING,
			'form_type' => FORM_TYPE_TEXT,
			'form' => true
		),
		'zip_code' => array(
			'label' => 'Zipcode',
			'type' => ORM_STRING,
			'form_type' => FORM_TYPE_TEXT,
			'form' => true
		),
		'city' => array(
			'label' => 'City',
			'type' => ORM_STRING,
			'form_type' => FORM_TYPE_TEXT,
			'form' => true
		),
		'country' => array(
			'label' => 'Country',
			'type' => ORM_STRING,
			'form_type' => FORM_TYPE_TEXT,
			'form' => true
		),
		'telephone' => array(
			'label' => 'Telephone',
			'type' => ORM_STRING,
			'form_type' => FORM_TYPE_TEXT,
			'form' => true
		),
		'cellphone' => array(
			'label' => 'Cellphone',
			'type' => ORM_STRING,
			'form_type' => FORM_TYPE_TEXT,
			'form' => true
		),
		'fax' => array(
			'label' => 'FAX',
			'type' => ORM_STRING,
			'form_type' => FORM_TYPE_TEXT,
			'form' => true
		),
		'email' => array(
			'label' => 'Email',
			'type' => ORM_STRING,
			'form_type' => FORM_TYPE_EMAIL,
			'form' => true,
			'rules' => array(
				array('email')
			)
		),
		'allowance' => array(
			'label' => 'Allowance',
			'type' => ORM_INT,
			'form_type' => FORM_TYPE_CHECKBOX,
			'form' => true
		),
		'engineer_hour_price' => array(
			'label' => 'Engineer hour price',
			'type' => ORM_FLOAT,
			'form_type' => FORM_TYPE_MONEY,
			'form' => true,
			'filters' => array(
				array('Num::filter_number')
			)
		),
		'call_out_price' => array(
			'label' => 'Call out price',
			'type' => ORM_FLOAT,
			'form_type' => FORM_TYPE_MONEY,
			'form' => true,
			'filters' => array(
				array('Num::filter_number')
			)
		),
		'notice' => array(
			'label' => 'Description',
			'type' => ORM_TEXT,
			'form_type' => FORM_TYPE_TEXTAREA,
			'form' => true
		)
	);

	/**
	 * "Has many" relationships
	 * @var array
	 */
	protected $_has_many = array(
		'invoices' => array(
			'model' => 'Invoice'
		)
	);


	/**
	 * Method for retrieving a "Form::select()" ready list of customers.
	 *
	 * @return  array
	 */
	public function get_customers_for_selection ()
	{
		$return = array();
		$customers = $this->find_all();

		// Load the customers.
		foreach ($customers as $customer)
		{
			$return[$customer->id] = $customer->company ?: __('Private');

			if (! empty($customer->name))
			{
				$return[$customer->id] .= ' - ' . $customer->name;
			} // if
		} // foreach

		asort($return);

		return array(0 => __('N/A')) + $return;
	} // function


	/**
	 * This method will return an array, which will serve as a HTML row for the frontend.
	 *
	 * @param   array  $exclude
	 * @return  array
	 */
	public function get_table_data ($exclude = array())
	{
		$return = array(
			'_id' => $this->id,
			'name' => $this->name,
			'company' => $this->company,
			'address' => $this->street . ' ' . $this->street_no . '<br />' . $this->zip_code . ' ' . $this->city,
			'email' => ($this->email ? '<a href="mailto:' . $this->email . '" target="_blank" title="' . $this->email . '"><i class="fa fa-envelope"></i></a>' : '<i class="fa fa-envelope-o"></i>'),
			'allowance' => ($this->allowance > 0 ? '<i class="fa fa-check"></i> ' . $this->allowance . '%' : '<i class="fa fa-times"></i>'),
			'action' => '<div class="btn-group">' .
				'<a class="btn btn-primary btn-sm" href="' . Route::url('customer', array('action' => 'edit', 'id' => $this->id)) . '">' . __('Edit') . '</a>' .
				'<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>' .
				'<ul class="dropdown-menu">' .
				'<li><a href="' . Route::url('invoice', array('action' => 'new')) . '?customer_id=' . $this->id . '"><i class="fa fa-plus"></i>&nbsp;&nbsp;' . __('Create new invoice') . '</a></li>' .
				'</ul>' .
				'</div>'
		);

		foreach ($exclude as $key)
		{
			unset($return[$key]);
		} // foreach

		return $return;
	} // function


	/**
	 * This method will perform a search for the given searchphrase.
	 *
	 * @param   string  $searchphrase
	 * @param   mixed   $wordsplit
	 * @return  Database_Result
	 */
	public function search ($searchphrase, $wordsplit = false)
	{
		if ($wordsplit === false)
		{
			$searchwords = array($searchphrase);
		}
		else if ($wordsplit === true)
		{
			$searchwords = explode(Kohana::$config->load('base')->get('search_wordsplit', ' '), $searchphrase);
		}
		else
		{
			// If we get a something else than a string, we use the parameter as splitter.
			$searchwords = explode($wordsplit, $searchphrase);
		} // if

		foreach ($searchwords as $searchword)
		{
			$searchword = trim($searchword);

			if (empty($searchword))
			{
				continue;
			} // if

			// Here we work with brackets for matching.
			$this->and_where_open()
				->where('name', 'LIKE', '%' . $searchword . '%')
				->or_where('company', 'LIKE', '%' . $searchword . '%')
				->or_where('email', 'LIKE', '%' . $searchword . '%')
				->or_where('street', 'LIKE', '%' . $searchword . '%')
				->or_where('zip_code', 'LIKE', '%' . $searchword . '%')
				->or_where('city', 'LIKE', '%' . $searchword . '%')
				->and_where_close();
		} // foreach

		return $this->find_all();
	} // function
} // class