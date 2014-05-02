<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Controller class "Dashboard"
 *
 * @category    Controller
 * @package     Faktura
 * @author      Leonard Fischer <post@leonardfischer.de>
 * @copyrights  2014 Leonard Fischer
 * @version     1.0
 */
class Controller_Dashboard extends Controller_Base
{
	/**
	 * Index action.
	 */
	public function action_index()
	{
		$this->content = View::factory('dashboard')
			->set('last_login', strftime('%d.%B %Y um %H:%m Uhr', $this->auth->get_user()->last_login))
			->set('widgets', $this->process_user_widgets());

		$this->template
			->set('content', $this->content);
	} // function


	/**
	 * Ajax action for loading the widgets.
	 */
	public function action_ajax ()
	{
		$return = array(
			'success' => true,
			'data' => null,
			'message' => null
		);

		$this->auto_render = false;

		try
		{
			if (! $this->request->is_ajax())
			{
				throw new HTTP_Exception_304('__This is a AJAX action only!');
			} // if

			$widget_class = $this->request->post('identifier');
			$widget_config = $this->request->post('config') ?: array();

			$return['data'] = trim(utf8_encode(Widgets_Base::factory($widget_class, $widget_config)->init()->render()));
		}
		catch (Exception $e)
		{
			$return['success'] = false;
			$return['message'] = $e->getMessage();
		} // try

		$this->response->body(json_encode($return));
	} // function


	/**
	 * Method for displaying the dashboard configuration.
	 */
	protected function action_dashboard_config ()
	{
		$return = array(
			'success' => true,
			'data' => null,
			'message' => null
		);

		$this->auto_render = false;

		try
		{
			if (! $this->request->is_ajax())
			{
				throw new HTTP_Exception_304('__This is a AJAX action only!');
			} // if

			$available_widgets = array();
			$widgets = $this->process_user_widgets();

			foreach (Widgets_Base::find_all() as $widget)
			{
				$available_widgets[$widget] = Widgets_Base::factory($widget)->get_name(true);
			} // foreach

			$view = View::factory('popups/dashboard_config')
				->set('available_widgets', $available_widgets)
				->set('widgets', $widgets);

			$return['data'] = trim($view);
		}
		catch (Exception $e)
		{
			$return['success'] = false;
			$return['message'] = $e->getMessage();
		} // try

		$this->response->body(json_encode($return));
	} // function


	/**
	 * Method for saving the dashboard configuration
	 */
	public function action_dashboard_save ()
	{
		$return = array(
			'success' => true,
			'data' => null,
			'message' => null
		);

		$this->auto_render = false;

		try
		{
			$widgets = json_decode($this->request->post('widgets'), true);
			$deletions = json_decode($this->request->post('deletions'), true);
			$user_id = (int) $this->auth->get_user()->id;

			if (count($deletions))
			{
				DB::delete('widgets')->where('id', 'IN', $deletions)->execute();
			} // if

			if (count($widgets))
			{
				foreach ($widgets as $i => $widget)
				{
					$sorting = (int) substr($i, 5);

					$widget_orm = ORM::factory('widget');

					if (is_numeric($widget['id']))
					{
						$widget_orm = $widget_orm->where('id', '=', $widget['id'])->find();
					} // if

					$widget_orm->values(array(
						'widget' => $widget['widget'],
						'user_id' => $user_id,
						'sorting' => $sorting
					))->save();
				} // foreach
			} // if
		}
		catch (Exception $e)
		{
			$return['success'] = false;
			$return['message'] = $e->getMessage();
		} // try

		$this->response->body(json_encode($return));
	} // function


	/**
	 * Method for preparing the users widgets.
	 *
	 * @return  array
	 */
	protected function process_user_widgets ()
	{
		$return = array();
		$widgets = $this->auth->get_user()->widgets->order_by('sorting', 'ASC')->find_all();

		foreach ($widgets as $widget)
		{
			$return[] = array(
				'instance' => Widgets_Base::factory($widget->widget, $widget->config),
				'data' => $widget
			);
		} // foreach

		return $return;
	} // function
} // class