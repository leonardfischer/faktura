<?php defined('SYSPATH') OR die('No direct script access.');

class TPL extends Smarty
{
	/**
	 * This variable will hold the TPL instance.
	 * @var  TPL
	 */
	protected static $instance = null;


	/**
	 * Overwrite the smarty constructor and set some configurations.
	 */
	public function __construct()
	{
		parent::__construct();

		$this->setTemplateDir(APPPATH . 'views' . DS);
		$this->setCompileDir(APPPATH . 'cache' . DS . 'tpl_c' . DS);
		$this->setConfigDir(APPPATH . 'config' . DS);
		$this->setCacheDir(APPPATH . 'cache' . DS . 'tpl' . DS);

		$this->registerPlugin('function', 'form', array(&$this, 'process_form'));

		$this->caching = Smarty::CACHING_LIFETIME_CURRENT;
	} // function


	/**
	 * Static instance method. Use this instead the constructor!
	 *
	 * @return  TPL
	 */
	public static function instance ()
	{
		if (self::$instance === null)
		{
			self::$instance = new self;
		} // if

		return self::$instance;
	} // function


	/**
	 * This method will process forms.
	 *
	 * @param   array  $params
	 * @return  string
	 */
	protected function process_form ($params = array())
	{
		switch ($params['type'])
		{
			case FORM_TYPE_TEXTAREA:
				return Form::textarea('input' . ucfirst($params['name']), $params['value'], array('placeholder' => $params['placeholder'], 'class' => 'form-control'));

			case FORM_TYPE_TEXT:
				return Form::input('input' . ucfirst($params['name']), $params['value'], array('type' => 'text', 'placeholder' => $params['placeholder'], 'class' => 'form-control'));
		} // switch
	} // function
} // class