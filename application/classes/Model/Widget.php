<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Model class "Widget"
 *
 * @category    Model
 * @package     Faktura
 * @author      Leonard Fischer <post@leonardfischer.de>
 * @copyrights  2014 Leonard Fischer
 * @version     1.0
 * @since       1.2
 */
class Model_Widget extends ORM
{
	/**
	 * Table name.
	 * @var  string
	 */
	protected $_table_name = 'widgets';

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
		'widget' => array(
			'label' => 'Widget',
			'type' => ORM_STRING,
			'form_type' => FORM_TYPE_TEXT,
			'form' => false
		),
		'user_id' => array(
			'label' => 'User',
			'type' => ORM_ID,
			'form_type' => FORM_TYPE_TEXT,
			'form' => false
		),
		'config' => array(
			'label' => 'Configuration',
			'type' => ORM_TEXT,
			'form_type' => FORM_TYPE_TEXTAREA,
			'form' => false
		),
		'sorting' => array(
			'label' => 'Sorting',
			'type' => ORM_TEXT,
			'form_type' => FORM_TYPE_TEXT,
			'form' => false
		)
	);
} // class