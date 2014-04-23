<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Widgets interface
 *
 * @category    Widgets
 * @package     Faktura
 * @author      Leonard Fischer <post@leonardfischer.de>
 * @copyrights  2014 Leonard Fischer
 * @version     1.0
 * @since       1.2
 */
abstract class Widgets_Base
{
	/**
	 * This variable will hold the widget configuration.
	 * @var  array
	 */
	protected $config = null;

	/**
	 * This variable holds all the necessary template data.
	 * @var  array
	 */
	protected $template_data = array();


	/**
	 * Widget constructor.
	 *
	 * @param   array  $config
	 * @throws  Exception
	 */
	public function __construct($config = null)
	{
		if ($config !== null)
		{
			if (is_string($config))
			{
				$config = json_decode($config, true);
			} // if

			$this->config = $config;
		} // if

		if (! (defined('static::NAME') && defined('static::TEMPLATE') && defined('static::CONFIGURABLE')))
		{
			throw new Exception('Please be sure to implement these constants: NAME, TEMPLATE, CONFIGURABLE.');
		} // if
	} // function


	/**
	 * Widget factory.
	 *
	 * @param   string  $name
	 * @param   array   $config
	 * @return  Widgets_Base
	 * @throws  ErrorException
	 */
	public static function factory($name, $config = null)
	{
		$class = 'Widgets_' . $name;

		if (class_exists($class))
		{
			return new $class($config);
		} // if

		throw new ErrorException('The given widget "' . $name . '" does not exist.');
	} // function


	/**
	 * This method returns all available widgets.
	 *
	 * @return  array
	 */
	public static function find_all ()
	{
		$return = array();
		$widget_classes = glob(APPPATH . 'classes' . DS . 'Widgets' . DS . '*');

		foreach ($widget_classes as $widget_class)
		{
			if (strpos($widget_class, 'Base.php') > 0)
			{
				continue;
			} // if

			$return[] = strstr(substr(strrchr($widget_class, DS), 1), '.', true);
		} // foreach

		return $return;
	} // function


	/**
	 * Method for returning the widget name.
	 *
	 * @return  string
	 */
	public function get_name()
	{
		return __(static::NAME);
	} // function


	/**
	 * Method for returning the widget template name.
	 *
	 * @return  string
	 */
	public function get_template()
	{
		return static::TEMPLATE;
	} // function


	/**
	 * This method returns true, if the current widget is configurable. False if otherwise.
	 *
	 * @return  boolean
	 */
	public function is_configurable()
	{
		return static::CONFIGURABLE;
	} // function


	/**
	 * This method returns the width of the current widget.
	 *
	 * @return  integer
	 */
	public function get_width()
	{
		return (int) static::WIDTH;
	} // function


	/**
	 * Method for initializing the widget.
	 *
	 * @return  self
	 */
	abstract public function init();


	/**
	 * Method for handling the widget configuration.
	 *
	 * @return  self
	 */
	public function config()
	{
		return $this;
	} // function


	/**
	 * Method for rendering the complete widget
	 *
	 * @return  string
	 */
	public function render()
	{
		return View::factory(static::TEMPLATE, $this->template_data)->set('self', $this);
	} // function
} // class