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
			(empty($this->email) ? '<i class="icon-envelope-alt"></i>' : '<a href="mailto:' . $this->email . '" target="_blank" title="' . $this->email . '"><i class="icon-envelope"></i></a>'),
			'<div class="btn-group"><a class="btn btn-primary btn-sm" href="' . Route::url('supplier', array('action' => 'edit', 'id' => $this->id)) . '">' . __('Edit') . '</a></div>'
		);
	} // function
} // class