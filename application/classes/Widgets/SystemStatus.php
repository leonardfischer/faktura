<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Widget class "System status"
 *
 * @category    Widgets
 * @package     Faktura
 * @author      Leonard Fischer <post@leonardfischer.de>
 * @copyrights  2014 Leonard Fischer
 * @version     1.0
 * @since       1.2
 */
class Widgets_SystemStatus extends Widgets_Base
{
	/**
	 * The widgets name.
	 */
	const NAME = 'System status';

	/**
	 * The widgets template
	 */
	const TEMPLATE = 'widgets/system_status';

	/**
	 * Defines, if this widget is configurable.
	 */
	const CONFIGURABLE = false;

	/**
	 * Method for initializing the widget.
	 *
	 * @return  self
	 */
	public function init()
	{
		$profiler = Profiler::application();

		$this->template_data = array(
			'request_time' => $profiler['average']['time'],
			'request_memory' => $profiler['average']['memory']
		);

		return $this;
	} // function


	/**
	 * Method for handling the widget configuration.
	 *
	 * @return  self
	 */
	public function config()
	{
		;
	} // function
} // class