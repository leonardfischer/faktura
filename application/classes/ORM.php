<?php defined('SYSPATH') or die('No direct script access.');

class ORM extends Kohana_ORM
{
	/**
	 * Array of all our model properties.
	 * @var  array
	 */
	protected $properties = array();


	/**
	 * Constructs a new model and loads a record if given
	 *
	 * @param   mixed $id Parameter for find or object to load
	 */
	public function __construct($id = NULL)
	{
		$this->_table_columns = array_flip(array_keys($this->get_properties()));

		// We need this for translations.
		foreach ($this->properties as &$property)
		{
			$property['label'] = __($property['label']);
		} // foreaech

		parent::__construct($id);
	} // function


	/**
	 * Returns the models properties.
	 *
	 * @return  array
	 */
	public function get_properties ()
	{
		return $this->properties;
	} // function


	/**
	 * Label definitions for validation
	 *
	 * @return  array
	 */
	public function labels()
	{
		$labels = array();
		$properties = $this->get_properties();

		foreach ($properties as $key => $property)
		{
			$labels[$key] = $property['label'];
		} // foreach

		return $labels;
	} // function


	/**
	 * Rule definitions for validation
	 *
	 * @return array
	 */
	public function rules()
	{
		$validation = array();
		$properties = $this->get_properties();

		foreach ($properties as $key => $property)
		{
			if (isset($property['rules']) && $property['rules'])
			{
				$validation[$key] = $property['rules'];
			} // if
		} // foreach

		return $validation;
	} // function


	/**
	 * Returns, if the given property is mandatry.
	 *
	 * @param   string  $field
	 * @return  boolean
	 */
	public function is_mandatory ($field)
	{
		$properties = $this->rules();

		if (! isset($properties[$field]))
		{
			return false;
		} // if

		foreach ($properties[$field] as $rules)
		{
			if (in_array('not_empty', $rules))
			{
				return true;
			} // if
		} // forach

		return false;
	} // function
} // class