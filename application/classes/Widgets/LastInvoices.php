<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Widget class "Last invoices"
 *
 * @category    Widgets
 * @package     Faktura
 * @author      Leonard Fischer <post@leonardfischer.de>
 * @copyrights  2014 Leonard Fischer
 * @version     1.0
 * @since       1.2
 */
class Widgets_LastInvoices extends Widgets_Base
{
	/**
	 * The widgets name.
	 */
	const NAME = 'The last invoices';

	/**
	 * The widgets template
	 */
	const TEMPLATE = 'widgets/last_invoices';

	/**
	 * Defines, if this widget is configurable.
	 */
	const CONFIGURABLE = false;

	/**
	 * Defines, how wide this widget is.
	 */
	const WIDTH = 2;


	/**
	 * Method for initializing the widget.
	 *
	 * @return  self
	 */
	public function init()
	{
		$this->template_data = array(
			'invoices' => ORM::factory('invoice')->order_by('id', 'DESC')->limit(5)->find_all()
		);

		return $this;
	} // function
} // class