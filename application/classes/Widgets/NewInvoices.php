<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Widget class "New invoices"
 *
 * @category    Widgets
 * @package     Faktura
 * @author      Leonard Fischer <post@leonardfischer.de>
 * @copyrights  2014 Leonard Fischer
 * @version     1.0
 * @since       1.2
 */
class Widgets_NewInvoices extends Widgets_Base
{
	/**
	 * The widgets name.
	 */
	const NAME = 'New invoices';

	/**
	 * The widgets template
	 */
	const TEMPLATE = 'widgets/new_invoices';

	/**
	 * Defines, if this widget is configurable.
	 */
	const CONFIGURABLE = false;

	/**
	 * Defines, how wide this widget is.
	 */
	const WIDTH = 1;

	/**
	 * Defines the widget color.
	 */
	const COLOR = '#2ecc71';

	/**
	 * Defines the widget font-color.
	 */
	const FONTCOLOR = '#fff';


	/**
	 * Method for returning the widget name.
	 *
	 * @param   boolean $raw
	 * @return  string
	 */
	public function get_name($raw = false)
	{
		if ($raw)
		{
			return parent::get_name();
		} // if

		return __('New invoices in :month', array(':month' => strftime('%B', strtotime('-1 MONTH'))));
	} // function


	/**
	 * Method for initializing the widget.
	 *
	 * @return  self
	 */
	public function init()
	{
		$this->template_data = array(
			'invoices' => ORM::factory('Invoice')->where('created_at', 'BETWEEN', array(date('Y-m-01', strtotime('-1 MONTH')), date('Y-m-00')))->count_all()
		);

		return $this;
	} // function
} // class