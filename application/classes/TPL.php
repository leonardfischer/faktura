<?php defined('SYSPATH') OR die('No direct script access.');

class TPL extends Smarty
{
	/**
	 * Overwrite the smarty constructor and set some configurations.
	 */
	function __construct()
	{
		parent::__construct();

		$this->setTemplateDir(APPPATH . 'views' . DS);
		$this->setCompileDir(APPPATH . 'cache' . DS . 'tpl_c' . DS);
		$this->setConfigDir(APPPATH . 'config' . DS);
		$this->setCacheDir(APPPATH . 'cache' . DS . 'tpl' . DS);

		$this->caching = Smarty::CACHING_LIFETIME_CURRENT;
	} // function
} // class