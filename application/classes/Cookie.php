<?php defined('SYSPATH') OR die('No direct script access.');

class Cookie extends Kohana_Cookie 
{
	/**
	 * @var  string  Magic salt to add to the cookie
	 */
	public static $salt = 'jAuifoA8';
}