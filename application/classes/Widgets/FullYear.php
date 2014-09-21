<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Widget class "Full Year"
 *
 * @category    Widgets
 * @package     Faktura
 * @author      Leonard Fischer <post@leonardfischer.de>
 * @copyrights  2014 Leonard Fischer
 * @version     1.0
 * @since       1.2
 */
class Widgets_FullYear extends Widgets_Base
{
	/**
	 * The widgets name.
	 */
	const NAME = 'Full year (chart)';

	/**
	 * The widgets template
	 */
	const TEMPLATE = 'widgets/full_year';

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
	const COLOR = '#34495e';

	/**
	 * Defines the widget font-color.
	 */
	const FONTCOLOR = '#fff';


	/**
	 * Method for initializing the widget.
	 *
	 * @return  self
	 */
	public function init()
	{
		$current_year_data = array_fill(1, 12, 0);

		$invoices = ORM::factory('Invoice')->where(DB::expr('year(paid_on_date)'), '=', date('Y'))->find_all();

		foreach ($invoices as $invoice)
		{
			$current_year_data[(int) date('n', strtotime($invoice->paid_on_date))] += $invoice->calculate_total(true, true);
		} // foreach

		$months = array_map('strtoupper', Date::months(Date::MONTHS_SHORT));

		$this->template_data = array(
			'data' => json_encode(array_values($current_year_data)),
			'months' => json_encode(array_values($months))
		);

		return $this;
	} // function
} // class