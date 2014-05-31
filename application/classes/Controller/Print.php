<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Controller class "Print"
 *
 * @category    Controller
 * @package     Faktura
 * @author      Leonard Fischer <post@leonardfischer.de>
 * @copyrights  2014 Leonard Fischer
 * @version     1.0
 */
class Controller_Print extends Controller_Base
{
	/**
	 * This variable will hold the global View template.
	 * @var  View  page template
	 */
	public $template = '_print';

	/**
	 * This constant defines the amount of printable lines on the first page.
	 * @var  integer
	 */
	const LINES_FIRST_PAGE = 12;

	/**
	 * This constant defines the amount of printable lines on the following pages (pages > 1).
	 * @var  integer
	 */
	const LINES_FOLLOWING_PAGES = 34;


	/**
	 * Necessary "before" Method, which checks if the user is allowed to execute this controller.
	 */
	public function before()
	{
		parent::before();

		if (! (in_array('admin', $this->user_roles) || in_array('invoices', $this->user_roles)))
		{
			throw new HTTP_Exception_403('You are not allowed to view the print-view for ":page"', array(':page' => __('Invoices')));
		} // if
	} // function


	/**
	 * This action will display the "invoice" print template.
	 */
	public function action_invoice()
	{
		$id = $this->request->param('id');

		if ($id === null)
		{
			throw new HTTP_Exception_404('No invoice ID was given!');
		} // if

		$model = ORM::factory('Invoice')->where('id', '=', $id)->find();

		$pages = $carryover = array();
		$page = 1;
		$total = $lines = 0;

		foreach ($model->positions->find_all() as $position)
		{
			$pages[$page][] = array(
				'amount' => $position->amount,
				'description' => nl2br($position->description),
				'ep' => number_format($position->price, 2, '.', ' '),
				'gp' => number_format($position->amount * $position->price, 2, '.', ' ')
			);

			$total += $position->amount * $position->price;
			$lines += (2 + substr_count(nl2br($position->description), '<br />'));

			if ($lines >= self::LINES_FOLLOWING_PAGES || ($page == 1 && $lines >= self::LINES_FIRST_PAGE))
			{
				$page ++;
				$lines = 0;

				$carryover[$page] = number_format($total, 2, '.', ' ');
			} // if
		} // foreach

		$smarty = TPL::instance();

		$smarty->assign(array(
			'invoice' => $model,
			'customer' => $model->customer,
			'pages' => $pages,
			'carryover' => $carryover,
			'total' => $model->calculate_total(true)
		));

		$this->content = $smarty->fetch('print/invoice.tpl');
	} // function


	/**
	 * This action will display the "delivery note" print template.
	 */
	public function action_delivery_note()
	{
		$id = $this->request->param('id');

		if ($id === null)
		{
			throw new HTTP_Exception_404('No invoice ID was given!');
		} // if

		$model = ORM::factory('Invoice')->where('id', '=', $id)->find();

		$pages = array();
		$page = 1;
		$lines = 0;

		foreach ($model->positions->find_all() as $position)
		{
			$pages[$page][] = array(
				'amount' => $position->amount,
				'description' => nl2br($position->description)
			);

			$lines += (2 + substr_count(nl2br($position->description), '<br />'));

			if ($lines >= (self::LINES_FOLLOWING_PAGES + 3) || ($page == 1 && $lines >= (self::LINES_FIRST_PAGE + 3)))
			{
				$page ++;
				$lines = 0;
			} // if
		} // foreach

		$smarty = TPL::instance();

		$smarty->assign(array(
			'invoice' => $model,
			'customer' => $model->customer,
			'pages' => $pages
		));

		$this->content = $smarty->fetch('print/delivery_note.tpl');
	} // function


	/**
	 * This action will display the "order confirmation" print template.
	 */
	public function action_order_confirmation()
	{
		$id = $this->request->param('id');

		if ($id === null)
		{
			throw new HTTP_Exception_404('No invoice ID was given!');
		} // if

		$model = ORM::factory('Invoice')->where('id', '=', $id)->find();

		$pages = $carryover = array();
		$page = 1;
		$total = $lines = 0;

		foreach ($model->positions->find_all() as $position)
		{
			$pages[$page][] = array(
				'amount' => $position->amount,
				'description' => nl2br($position->description),
				'ep' => number_format($position->price, 2, '.', ' '),
				'gp' => number_format($position->amount * $position->price, 2, '.', ' ')
			);

			$total += $position->amount * $position->price;
			$lines += (2 + substr_count(nl2br($position->description), '<br />'));

			if ($lines >= self::LINES_FOLLOWING_PAGES || ($page == 1 && $lines >= self::LINES_FIRST_PAGE))
			{
				$page ++;
				$lines = 0;

				$carryover[$page] = number_format($total, 2, '.', ' ');
			} // if
		} // foreach

		$smarty = TPL::instance();

		$smarty->assign(array(
			'invoice' => $model,
			'customer' => $model->customer,
			'pages' => $pages,
			'carryover' => $carryover,
			'total' => $model->calculate_total(true)
		));

		$this->content = $smarty->fetch('print/order_confirmation.tpl');
	} // function


	/**
	 * This action will display the "credit" print template.
	 */
	public function action_credit()
	{
		$id = $this->request->param('id');

		if ($id === null)
		{
			throw new HTTP_Exception_404('No invoice ID was given!');
		} // if

		$position_ids = $this->request->param('misc');

		if (empty($position_ids))
		{
			throw new HTTP_Exception_404('Please select some positions!');
		} // if

		$position_ids = explode(',', $position_ids);

		$model = ORM::factory('Invoice')->where('id', '=', $id)->find();

		$pages = $carryover = array();
		$page = 1;
		$total = $lines = 0;

		foreach ($model->positions->where('id', 'IN', $position_ids)->find_all() as $position)
		{
			$pages[$page][] = array(
				'amount' => $position->amount,
				'description' => nl2br($position->description),
				'ep' => number_format($position->price, 2, '.', ' '),
				'gp' => number_format($position->amount * $position->price, 2, '.', ' ')
			);

			$total += $position->amount * $position->price;
			$lines += (2 + substr_count(nl2br($position->description), '<br />'));

			if ($lines >= self::LINES_FOLLOWING_PAGES || ($page == 1 && $lines >= self::LINES_FIRST_PAGE))
			{
				$page ++;
				$lines = 0;

				$carryover[$page] = number_format($total, 2, '.', ' ');
			} // if
		} // foreach

		$smarty = TPL::instance();

		$smarty->assign(array(
			'invoice' => $model,
			'customer' => $model->customer,
			'pages' => $pages,
			'carryover' => $carryover,
			'total' => $model->calculate_total(true)
		));

		$this->content = $smarty->fetch('print/credit.tpl');
	} // function


	/**
	 * This action will display the "reminder" print template.
	 */
	public function action_reminder()
	{
		$id = $this->request->param('id');

		if ($id === null)
		{
			throw new HTTP_Exception_404('No invoice ID was given!');
		} // if

		$model = ORM::factory('Invoice')->where('id', '=', $id)->find();

		$pages = $carryover = array();
		$page = 1;
		$total = $lines = 0;

		foreach ($model->positions->find_all() as $position)
		{
			$pages[$page][] = array(
				'amount' => $position->amount,
				'description' => nl2br($position->description),
				'ep' => number_format($position->price, 2, '.', ' '),
				'gp' => number_format($position->amount * $position->price, 2, '.', ' ')
			);

			$total += $position->amount * $position->price;
			$lines += (2 + substr_count(nl2br($position->description), '<br />'));

			if ($lines >= self::LINES_FOLLOWING_PAGES || ($page == 1 && $lines >= self::LINES_FIRST_PAGE))
			{
				$page ++;
				$lines = 0;

				$carryover[$page] = number_format($total, 2, '.', ' ');
			} // if
		} // foreach

		$smarty = TPL::instance();

		$smarty->assign(array(
			'invoice' => $model,
			'customer' => $model->customer,
			'pages' => $pages,
			'carryover' => $carryover,
			'total' => $model->calculate_total(true)
		));

		$this->content = $smarty->fetch('print/reminder.tpl');
	} // function
} // class