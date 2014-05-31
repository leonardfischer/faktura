<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Widget class "Open reminders"
 *
 * @category    Widgets
 * @package     Faktura
 * @author      Leonard Fischer <post@leonardfischer.de>
 * @copyrights  2014 Leonard Fischer
 * @version     1.0
 * @since       1.2
 */
class Widgets_OpenReminders extends Widgets_Base
{
	/**
	 * The widgets name.
	 */
	const NAME = 'Open reminders';

	/**
	 * The widgets template
	 */
	const TEMPLATE = 'widgets/open_reminders';

	/**
	 * Defines, if this widget is configurable.
	 */
	const CONFIGURABLE = false;

	/**
	 * Defines, how wide this widget is.
	 */
	const WIDTH = 1;

	/**
	 * Method for initializing the widget.
	 *
	 * @return  self
	 */
	public function init()
	{
		$this->template_data = array(
			'invoices' => ORM::factory('Invoice')->get_reminder_invoices()->find_all()
		);

		return $this;
	} // function
} // class