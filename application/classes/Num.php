<?php defined('SYSPATH') OR die('No direct script access.');

class Num extends Kohana_Num
{
	/**
	 * This helper will parse a string like "1.000,95 Bla" to float "1000.95" (with up to four digits after the point).
	 *
	 * @param   string  $p_string
	 * @return  float
	 * @author  Leonard Fischer <post@leonardfischer.de>
	 */
	public static function filter_number ($p_string)
	{
		// First we strip the currency ("GHZ", "Euro", "$", ...) including spaces.
		$p_string = self::strip_non_numeric($p_string);

		// Check if someone wrote a string like "1.000.000".
		if (substr_count($p_string, '.') > 1)
		{
			$p_string = str_replace('.', '', $p_string);
		} // if

		// Check if someone wrote a string like "1,000,000".
		if (substr_count($p_string, ',') > 1)
		{
			$p_string = str_replace(',', '', $p_string);
		} // if

		// If we find a single point and a single comma, we use the last found one as decimal point.
		if (strpos($p_string, '.') !== false && strpos($p_string, ',') !== false)
		{
			if (strpos($p_string, '.') > strpos($p_string, ','))
			{
				$p_string = str_replace(',', '', $p_string);
			}
			else
			{
				$p_string = str_replace('.', '', $p_string);
			} // if
		} // if

		// Now we replace commas with dots: "1000,10" to "1000.10" and return the rounded value.
		return (float) round(str_replace(',', '.', $p_string), 2);
	} // function


	/**
	 * Strips everything "not-number"-like.
	 *
	 * @param   string  $p_data
	 * @return  string
	 * @author  Leonard Fischer <post@leonardfischer.de>
	 */
	public static function strip_non_numeric ($p_data)
	{
		return preg_replace('/([^,\.\d])*/i', '', $p_data);
	} // function
} // class