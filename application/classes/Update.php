<?php defined('SYSPATH') OR die('No direct script access.');

class Update
{
	/**
	 * This array will hold the available config keys and default values.
	 * @var  array
	 */
	public static $base_vars = array(
		'version' => '#',
		// General configuration.
		'title' => 'Faktura',
		'theme' => 'default',
		// Used for the Kohana bootstrap.
		'timezone' => 'America/Chicago',
		'locale' => 'en_US.utf-8',
		'language' => 'en_US',
		// Mailer configuration, will be used for "reset password" function
		'mail_faktura' => '#',
		'mail_transport' => 'mail',
		'mail_smtp_host' => 'localhost',
		'mail_smtp_port' => 25,
		'mail_smtp_user' => '',
		'mail_smtp_pass' => '',
		'mail_sendmail_command' => '/usr/sbin/sendmail -bs',
		// @see  http://php.net/strftime for more information.
		// 'date_format_list' => '%d.%B %Y',
		// 'date_format_list_with_time' => '%d.%B %Y %H:%m',
		// 'date_format_form' => '%d.%m.%Y',
		// Some search and list options.
		'search_minlength' => 3,
		'search_wordsplit' => ' ',
		'rows_per_page' => 40,
		'invoice_start_no' => 1,
		// Define the user min-length.
		'password_minlength' => 3,
		'password_prevent_copynpaste' => true
	);


	/**
	 * This method will take the "queries" array from the update file and return necessary SQL Queries.
	 *
	 * @param   array  $queries
	 * @return  array
	 * @author  Leonard Fischer <post@leonardfischer.de>
	 */
	public static function process_queries (array $queries)
	{
		$return = array();

		foreach ($queries as $query)
		{
			if (self::process_query_condition($query['if']))
			{
				if (isset($query['then']) && ! empty($query['then']))
				{
					$return[] = $query['then'];
				} // if
			}
			else
			{
				if (isset($query['else']) && ! empty($query['else']))
				{
					$return[] = $query['else'];
				} // if
			} // if
		} // foreach

		return $return;
	} // function


	/**
	 * This method will process a single condition and return a boolean value if it's true or false.
	 *
	 * @param   string  $condition
	 * @return  boolean
	 * @author  Leonard Fischer <post@leonardfischer.de>
	 */
	public static function process_query_condition ($condition)
	{
		$db = Database::instance();
		$conditions = explode(',', $condition);

		switch (strstr($conditions[0], ':', true))
		{
			case 'field-exists':
				// This condition checks, if the given column exists in the given table.
				list($table, $field) = explode('.', substr(strrchr($conditions[0], ':'), 1));

				$result = $db->query(Database::SELECT, 'SHOW COLUMNS FROM ' . $table . ' LIKE ' . $db->escape($field) . ';')->as_array();

				if (count($result) === 0)
				{
					return false;
				} // if

				if (count($conditions) === 1)
				{
					return true;
				} // if

				if (strstr($conditions[1], ':', true) == 'type' && strpos($result[0]['Type'], substr(strrchr($conditions[1], ':'), 1)) === 0)
				{
					return true;
				} // if

				return false;

			case 'table-exists':
				// This condition checks, if the given table exists.
				$table = substr(strrchr($conditions[0], ':'), 1);

				$result = $db->query(Database::SELECT, 'SHOW TABLES LIKE ' . $db->escape($table) . ';')->as_array();

				return (count($result) > 0);
		} // switch
	} // function


	/**
	 * Static method for updating the base configuration file.
	 *
	 * @param  array  $p_data
	 */
	public static function update_base_config (array $p_data = array())
	{
		$config_path = APPPATH . 'config' . DS;

		$search = $replace = array();

		if (empty($p_data['mail_faktura']) || $p_data['mail_faktura'] == '#')
		{
			$p_data['mail_faktura'] = 'noreply@' . $_SERVER['SERVER_NAME'];
		} // if

		foreach ($p_data as $key => $value)
		{
			if (in_array($key, array('password_minlength', 'mail_smtp_port', 'search_minlength', 'rows_per_page', 'invoice_start_no')))
			{
				$value = (int) $value;
			} // if

			if ($key == 'password_prevent_copynpaste')
			{
				$value = $value ? 'true' : 'false';
			} // if

			$search[] = '%' . $key . '%';
			$replace[] = $value;
		} // foreach

		// Writing the base.php with the input data (or default values).
		$base_content = str_replace($search, $replace, file_get_contents($config_path . 'base.tpl'));

		file_put_contents($config_path . 'base.php', $base_content);
	} // function
} // class