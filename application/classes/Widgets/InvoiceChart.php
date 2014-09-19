<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Widget class "Invoice chart"
 *
 * @category    Widgets
 * @package     Faktura
 * @author      Leonard Fischer <post@leonardfischer.de>
 * @copyrights  2014 Leonard Fischer
 * @version     1.0
 * @since       1.2
 */
class Widgets_InvoiceChart extends Widgets_Base
{
	/**
	 * The widgets name.
	 */
	const NAME = 'The last half year (chart)';

	/**
	 * The widgets template
	 */
	const TEMPLATE = 'widgets/invoice_chart';

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
	const COLOR = '#9b59b6';

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
		$start = date('n') - 6;
		if ($start < 1)
		{
			$start += 12;
		} // if

		$data = array_fill($start + 1, 6, 0);

		$invoices = ORM::factory('Invoice')->where('paid_on_date', '>', DB::expr('NOW() - INTERVAL 6 MONTH'))->find_all();

		foreach ($invoices as $invoice)
		{
			$data[(int) date('n', strtotime($invoice->paid_on_date))] += $invoice->calculate_total(true, true);
		} // foreach

		$this->template_data = array(
			'data' => json_encode(array_values($data)),
			'months' => json_encode(array_map('strtoupper', array_slice(Date::months(Date::MONTHS_SHORT), $start, 6)))
		);

		return $this;
	} // function
} // class