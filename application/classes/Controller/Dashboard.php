<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Dashboard extends Controller_Base
{
	public function action_index()
	{
		$this->content = View::factory('dashboard')
			->set('last_month', strftime('%B', strtotime('-1 MONTH')))
			->set('last_login', strftime('%d.%B %Y um %H:%m Uhr', $this->auth->get_user()->last_login));

		$this
			->new_customers_and_invoices_last_month()
			->open_invoices_and_reminders()
			->invoice_value_last_month()
			->last_invoices()
			->system_status();

		$this->template
			->set('content', $this->content);
	} // function


	/**
	 * Retrieves the number of new customers and invoices of the last month.
	 *
	 * @return  Controller_Dashboard
	 */
	protected function new_customers_and_invoices_last_month()
	{
		$this->content
			->set('new_customers', ORM::factory('customer')->where('created_at', 'BETWEEN', array(date('Y-m-01', strtotime('-1 MONTH')), date('Y-m-00')))->count_all())
			->set('new_invoices', ORM::factory('invoice')->where('created_at', 'BETWEEN', array(date('Y-m-01', strtotime('-1 MONTH')), date('Y-m-00')))->count_all());

		return $this;
	} // function


	/**
	 * Method for summing up all open invoices and reminders
	 *
	 * @return  Controller_Dashboard
	 */
	protected function open_invoices_and_reminders ()
	{
		$this->content
			->set('open_invoices', ORM::factory('invoice')->where('paid_on_date', '=', null)->order_by('id', 'DESC')->count_all())
			->set('reminder_invoices', ORM::factory('invoice')->get_reminder_invoices()->count_all());

		return $this;
	} // function


	/**
	 * Method for calculating the value of the paid invoices, which were created last month.
	 *
	 * @return  Controller_Dashboard
	 */
	protected function invoice_value_last_month ()
	{
		$l_value_last_month = 0;

		// Get all invoices of the last month, which have been paid.
		$l_invoices = ORM::factory('invoice')
			->where('paid_on_date', '!=', null)
			->where('invoice_date', 'BETWEEN', array(date('Y-m-01', strtotime('-1 MONTH')), date('Y-m-00')))
			->find_all();

		foreach ($l_invoices as $l_invoice)
		{
			$l_value_last_month += $l_invoice->calculate_total(true, true);
		} // foreach

		$this->content
			->set('money', '&euro; ' . number_format(round($l_value_last_month, 2), 2, ',', ''));

		return $this;
	} // function


	/**
	 * Method for displaying the last few invoices.
	 *
	 * @return  Controller_Dashboard
	 */
	protected function last_invoices ()
	{
		$this->content
			->set('invoices', ORM::factory('invoice')->order_by('id', 'DESC')->limit(5)->find_all());

		return $this;
	} // function


	/**
	 * Method for displaying some basic system status numbers.
	 *
	 * @return  Controller_Dashboard
	 */
	protected function system_status()
	{
		$profiler = Profiler::application();

		$this->content
			->set('request_time', $profiler['average']['time'])
			->set('request_memory', $profiler['average']['memory']);

		return $this;
	} // function
} // class