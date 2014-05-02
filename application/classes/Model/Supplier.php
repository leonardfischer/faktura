<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Model class "Supplier"
 *
 * @category    Model
 * @package     Faktura
 * @author      Leonard Fischer <post@leonardfischer.de>
 * @copyrights  2014 Leonard Fischer
 * @version     1.0
 */
class Model_Supplier extends ORM
{
	/**
	 * Table name.
	 * @var  string
	 */
	protected $_table_name = 'suppliers';

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
		'notice' => array(
			'label' => 'Description',
			'type' => ORM_TEXT,
			'form_type' => FORM_TYPE_TEXTAREA,
			'form' => true
		)
	);


	/**
	 * This method will return an array, which will serve as a HTML row for the frontend.
	 *
	 * @return  array
	 */
	public function get_table_data()
	{
		return array(
			$this->name,
			$this->company,
			$this->street . ' ' . $this->street_no . '<br />' . $this->zip_code . ' ' . $this->city,
			($this->email ? '<a href="mailto:' . $this->email . '" target="_blank" title="' . $this->email . '"><i class="fa fa-envelope"></i></a>' : '<i class="fa fa-envelope-o"></i>'),
			'<div class="btn-group"><a class="btn btn-primary btn-sm" href="' . Route::url('supplier', array('action' => 'edit', 'id' => $this->id)) . '">' . __('Edit') . '</a></div>'
		);
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
				->and_where_close();
		} // foreach

		return $this->find_all();
	} // function
} // class