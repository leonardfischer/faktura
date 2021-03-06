<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Widget class "Open invoices sum"
 *
 * @category    Widgets
 * @package     Faktura
 * @author      Leonard Fischer <post@leonardfischer.de>
 * @copyrights  2014 Leonard Fischer
 * @version     1.0
 * @since       1.2
 */
class Widgets_OpenInvoicesSum extends Widgets_Base
{
	/**
	 * The widgets name.
	 */
	const NAME = 'Summed up costs of open invoices';

	/**
	 * The widgets template
	 */
	const TEMPLATE = 'widgets/open_invoices_sum';

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
	const COLOR = '#e67e22';

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
		$total_money_with_tax = $total_money_without_tax = 0;

		foreach (ORM::factory('Invoice')->where('paid_on_date', '=', null)->find_all() as $invoice)
		{
			$total_money_with_tax += $invoice->calculate_total(true, true);
			$total_money_without_tax += $invoice->calculate_total(true, false);
		} // foreach

		$this->template_data = array(
			'money_amount' => number_format(round($total_money_with_tax, 2), 2, ',', ''),
			'tax_amount' => number_format(round($total_money_with_tax - $total_money_without_tax, 2), 2, ',', '')
		);

		return $this;
	} // function
} // class