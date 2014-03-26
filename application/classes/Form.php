<?php defined('SYSPATH') OR die('No direct script access.');

class Form extends Kohana_Form
{
	/**
	 * Creates a form input. If no type is specified, a "text" type input will
	 * be returned.
	 *
	 *     echo Form::input('username', $username);
	 *
	 * @param   string  $name       input name
	 * @param   string  $value      input value
	 * @param   array   $attributes html attributes
	 * @return  string
	 * @uses    HTML::attributes
	 */
	public static function input($name, $value = NULL, array $attributes = NULL)
	{
		// Set the input name.
		$attributes['id'] = $name;

		return parent::input($name, $value, $attributes);
	} // function

	/**
	 * Creates a textarea form input.
	 *
	 *     echo Form::textarea('about', $about);
	 *
	 * @param   string  $name           textarea name
	 * @param   string  $body           textarea body
	 * @param   array   $attributes     html attributes
	 * @param   boolean $double_encode  encode existing HTML characters
	 * @return  string
	 * @uses    HTML::attributes
	 * @uses    HTML::chars
	 */
	public static function textarea($name, $body = '', array $attributes = NULL, $double_encode = TRUE)
	{
		// Set the input name
		$attributes['name'] = $attributes['id'] = $name;

		// Add default rows and cols attributes (required)
		$attributes += array('rows' => 3);

		return parent::textarea($name, $body, $attributes, $double_encode);
	}
} // class