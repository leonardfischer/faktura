<?php defined('SYSPATH') OR die('No direct script access.');

class HTTP_Exception_404 extends Kohana_HTTP_Exception_404
{
	/**
	 * Generate a Response for the 403 Exception.
	 *
	 * @return  Response
	 */
	public function get_response()
	{
		$content = View::factory('_error')
			->set('code', $this->getCode())
			->set('message', $this->getMessage());

		$view = View::factory('_base')
			->set('content', $content)
			->set('user_roles', Auth::instance()->get_user()->get_roles())
			->set('basedir', Kohana::$base_url);

		return Response::factory()
			->status(404)
			->body($view->render());
	} // function
} // class