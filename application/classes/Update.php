<?php defined('SYSPATH') OR die('No direct script access.');

class Update
{
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
				if (! empty($query['then']))
				{
					$return[] = $query['then'];
				} // if
			}
			else
			{
				if (! empty($query['else']))
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
} // class