<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Model class "InvoicePosition"
 *
 * @category    Model
 * @package     Faktura
 * @author      Leonard Fischer <post@leonardfischer.de>
 * @copyrights  2014 Leonard Fischer
 * @version     1.0
 */
class Model_InvoicePosition extends ORM
{
	/**
	 * Table name
	 * @var string
	 */
	protected $_table_name = 'invoice_positions';

	/**
	 * Table columns
	 * @var array
	 */
	protected $_table_columns = array(
		'id' => array(
			'label' => 'ID',
			'type' => ORM_ID,
			'form_type' => null,
			'form' => false
		),
		'invoice_id' => array(
			'label' => 'InvoiceID',
			'type' => ORM_ID,
			'form_type' => null,
			'form' => false
		),
		'description' => array(
			'label' => 'Description',
			'type' => ORM_TEXT,
			'form_type' => FORM_TYPE_TEXTAREA,
			'form' => true
		),
		'amount' => array(
			'label' => 'Amount',
			'type' => ORM_FLOAT,
			'form_type' => FORM_TYPE_TEXT,
			'form' => true
		),
		'price' => array(
			'label' => 'Price',
			'type' => ORM_FLOAT,
			'form_type' => FORM_TYPE_MONEY,
			'form' => true
		)
	);


	protected $_belongs_to = array(
		'invoice' => array(
			'model' => 'invoice'
		)
	);

	/**
	 * Filter definitions for validation
	 *
	 * @return  array
	 */
	public function filters()
	{
		return array(
			'amount' => array(
				array('Num::filter_number')
			),
			'price' => array(
				array('Num::filter_number')
			)
		);
	} // function
} // class