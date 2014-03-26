<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Model class "Invoice"
 *
 * @category    Model
 * @package     Faktura
 * @author      Leonard Fischer <post@leonardfischer.de>
 * @copyrights  2014 Leonard Fischer
 * @version     1.0
 */
class Model_Invoice extends ORM
{
	/**
	 * Table name.
	 * @var  string
	 */
	protected $_table_name = 'invoices';

	/**
	 * Auto-update columns for updates
	 * @var  array
	 */
	protected $_updated_column = array('column' => 'updated_at', 'format' => 'Y-m-d H:i:s');

	/**
	 * Auto-update columns for creation
	 * @var  array
	 */
	protected $_created_column = array('column' => 'created_at', 'format' => 'Y-m-d H:i:s');

	/**
	 * Table columns.
	 * @var  array
	 */
	protected $properties = array(
		'id' => array(
			'label' => 'ID',
			'type' => ORM_ID,
			'form_type' => null,
			'form' => false
		),
		'invoice_no' => array(
			'label' => 'Invoice no.',
			'type' => ORM_STRING,
			'form_type' => FORM_TYPE_TEXT,
			'form' => true
		),
		'invoice_date' => array(
			'label' => 'Created at',
			'type' => ORM_DATE,
			'form_type' => FORM_TYPE_DATE,
			'form' => true
		),
		'customer_id' => array(
			'label' => 'Customer',
			'type' => ORM_ID,
			'form_type' => null,
			'form' => true
		),
		'shipping_address' => array(
			'label' => 'Different shipping address',
			'type' => ORM_TEXT,
			'form_type' => FORM_TYPE_TEXTAREA,
			'form' => true
		),
		'paid_on_date' => array(
			'label' => 'Paid at',
			'type' => ORM_DATE,
			'form_type' => FORM_TYPE_DATE,
			'form' => true
		)
	);

	/**
	 * "Belongs to" relationships
	 * @var array
	 */
	protected $_belongs_to = array(
		'customer' => array(
			'model' => 'Customer'
		)
	);

	/**
	 * "Has many" relationships
	 * @var array
	 */
	protected $_has_many = array(
		'positions' => array(
			'model' => 'InvoicePosition'
		)
	);


	/**
	 * Filter definitions for validation
	 *
	 * @return  array
	 */
	public function filters()
	{
		return array(
			'invoice_date' => array(
				array(function ($value) {
					if (! Valid::date($value))
					{
						return null;
					} // if

					return date('Y-m-d', strtotime($value));
				})
			),
			'paid_on_date' => array(
				array(function ($value) {
					if (! Valid::date($value))
					{
						return null;
					} // if

					return date('Y-m-d', strtotime($value));
				})
			)
		);
	} // function


	/**
	 * Returns the amount of money, the current invoice is worth.
	 *
	 * @param   boolean  $raw
	 * @param   boolean  $with_tax
	 * @return  string
	 */
	public function calculate_total ($raw = false, $with_tax = false)
	{
		$amount = 0;

		foreach ($this->positions->find_all() as $position)
		{
			$amount += $position->price * $position->amount;
		} // foreach

		if ($with_tax)
		{
			$amount *= 1.19;
		} // if

		if ($raw)
		{
			return round($amount, 2);
		} // if

		return '&euro; ' . number_format(round($amount, 2), 2, ',', '');
	} // function


	/**
	 * Returns the formatted invoice-date.
	 *
	 * @return  string
	 */
	public function invoice_date ()
	{
		if (!empty($this->invoice_date))
		{
			return strftime(Kohana::$config->load('base')->get('date_format_list'), strtotime($this->invoice_date));
		} // if

		return '';
	} // function


	/**
	 * Returns the formatted paid on-date.
	 *
	 * @return  string
	 */
	public function paid_on_date ()
	{
		if (!empty($this->paid_on_date))
		{
			return strftime(Kohana::$config->load('base')->get('date_format_list'), strtotime($this->paid_on_date));
		} // if

		return '';
	} // function


	/**
	 * Method for returning the defined invoice types.
	 *
	 * @return  array
	 */
	public static function get_types ()
	{
		return array(
			INVOICE_FILTER_ALL => __('All'),
			INVOICE_FILTER_OPEN => __('Open'),
			INVOICE_FILTER_REMINDER => __('Reminder'),
		);
	} // function


	/**
	 * This method will return an array, which will serve as a HTML row for the frontend.
	 *
	 * @param   array  $exclude
	 * @return  array
	 */
	public function get_table_data ($exclude = array())
	{
		$return = array(
			'invoice_no' => $this->invoice_no,
			'customer' => '<a href="' . Route::url('customer', array('action' => 'edit', 'id' => $this->customer->id)) . '">' . ($this->customer->company ?: $this->customer->name) . '</a>',
			'invoice_date' => $this->invoice_date(),
			'paid_on_date' => $this->paid_on_date(),
			'value' => $this->calculate_total(false, true),
			'action' => '<div class="btn-group">' .
				'<a class="btn btn-primary btn-sm" href="' . Route::url('invoice', array('action' => 'edit', 'id' => $this->id)) . '">' . __('Edit') . '</a>' .
				'</div>'
		);

		foreach ($exclude as $key)
		{
			unset($return[$key]);
		} // foreach

		return $return;
	} // function


	/**
	 * This method will return all "reminder" invoices: Older than 1 month and not (yet) paid.
	 *
	 * @return  Model_Invoice
	 */
	public function get_reminder_invoices ()
	{
		return $this->where('paid_on_date', '=', null)->where('invoice_date', '<', DB::expr('NOW() - INTERVAL 1 MONTH'));
	} // function
} // class