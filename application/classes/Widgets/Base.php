<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Widgets interface
 *
 * @category    Widgets
 * @package     Faktura
 * @author      Leonard Fischer <post@leonardfischer.de>
 * @copyrights  2014 Leonard Fischer
 * @version     1.0
 * @since       1.2
 */
interface Widgets_Base
{
	/**
	 * Method for returning the widget name.
	 *
	 * @return  string
	 */
	public function get_name();


	/**
	 * Method for returning the widget template name.
	 * @return  string
	 */
	public function get_template();


	/**
	 * Method for initializing the widget.
	 *
	 * @return  self
	 */
	public function init();


	/**
	 * Method for handling the widget configuration.
	 *
	 * @return  self
	 */
	public function config();


	/**
	 * Method for rendering the complete widget
	 *
	 * @return  string
	 */
	public function render();
} // class