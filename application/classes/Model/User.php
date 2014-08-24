<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Model class "User"
 *
 * @see         Model_Auth_User  The extended Auth model class.
 * @category    Model
 * @package     Faktura
 * @author      Leonard Fischer <post@leonardfischer.de>
 * @copyrights  2014 Leonard Fischer
 * @version     1.0
 */
class Model_User extends Model_Auth_User
{
	/**
	 * A user has many tokens and roles
	 *
	 * @var array Relationhips
	 */
	protected $_has_many = array(
		'user_tokens' => array('model' => 'User_Token'),
		'roles' => array('model' => 'Role', 'through' => 'roles_users'),
		'widgets' => array('model' => 'Widget')
	);

	/**
	 * Array of all our model properties.
	 * @var  array
	 */
	protected $properties = array(
		'id' => array(
			'label' => 'ID',
			'type' => ORM_ID,
			'form_type' => null,
			'form' => false
		),
		'username' => array(
			'label' => 'Username',
			'type' => ORM_STRING,
			'form_type' => FORM_TYPE_TEXT,
			'form' => true
		),
		'password' => array(
			'label' => 'Password',
			'type' => ORM_STRING,
			'form_type' => FORM_TYPE_TEXT,
			'form' => true
		),
		'email' => array(
			'label' => 'Email',
			'type' => ORM_STRING,
			'form_type' => FORM_TYPE_TEXT,
			'form' => true
		),
		'logins' => array(
			'label' => 'Logins',
			'type' => ORM_INT,
			'form_type' => FORM_TYPE_TEXT,
			'form' => false
		),
		'last_login' => array(
			'label' => 'Last login',
			'type' => ORM_DATE,
			'form_type' => FORM_TYPE_DATE,
			'form' => false
		),

		'theme' => array(
			'label' => 'Selected theme',
			'type' => ORM_STRING,
			'form_type' => FORM_TYPE_SELECT,
			'form' => true
		),
		'theme_options' => array(
			'label' => 'Theme options',
			'type' => ORM_TEXT,
			'form_type' => FORM_TYPE_TEXT,
			'form' => false
		)
	);

	public function get_roles ()
	{
		$roles = array();

		foreach ($this->roles->find_all() as $role)
		{
			$roles[] = $role->name;
		} // foreach

		return $roles;
	} // function


	/**
	 * Password validation for plain passwords.
	 *
	 * @param   array  $values
	 * @return  Validation
	 */
	public static function get_password_validation($values)
	{
		return Validation::factory($values)
			->rule('password', 'min_length', array(':value', Kohana::$config->load('base')->get('password_minlength', 3)))
			->rule('password_confirm', 'matches', array(':validation', ':field', 'password'));
	} // function


	/**
	 * Returns the formatted last login date.
	 *
	 * @return  string
	 */
	public function last_login ()
	{
		if (!empty($this->last_login))
		{
			return strftime(Kohana::$config->load('base')->get('date_format_list_with_time'), $this->last_login);
		} // if

		return '';
	} // function


	/**
	 * This method will return an array, which will serve as a HTML row for the frontend.
	 *
	 * @param   array  $exclude
	 * @return  array
	 */
	public function get_table_data($exclude = array())
	{
		$return = array(
			'_id' => $this->id,
			'id' => $this->id,
			'username' => $this->username,
			'email' => ($this->email ? '<a href="mailto:' . $this->email . '" target="_blank" title="' . $this->email . '"><i class="fa fa-envelope"></i></a>' : '<i class="fa fa-envelope-o"></i>'),
			'last_login' => $this->last_login(),
			'action' => '<div class="btn-group">' .
				'<a class="btn btn-primary btn-sm" href="' . Route::url('user', array('action' => 'edit', 'id' => $this->id)) . '">' . __('Edit') . '</a>' .
				'</div>'
		);

		foreach ($exclude as $key)
		{
			unset($return[$key]);
		} // foreach

		return $return;
	} // function


	/**
	 * This method will perform a search for the given searchphrase.
	 *
	 * @param   string  $searchphrase
	 * @param   mixed   $wordsplit
	 * @return  Database_Result
	 */
	public function search ($searchphrase, $wordsplit = false)
	{
		if ($wordsplit === false)
		{
			$searchwords = array($searchphrase);
		}
		else if ($wordsplit === true)
		{
			$searchwords = explode(Kohana::$config->load('base')->get('search_wordsplit', ' '), $searchphrase);
		}
		else
		{
			// If we get a something else than a string, we use the parameter as splitter.
			$searchwords = explode($wordsplit, $searchphrase);
		} // if

		foreach ($searchwords as $searchword)
		{
			$searchword = trim($searchword);

			if (empty($searchword))
			{
				continue;
			} // if

			// Here we work with brackets for matching.
			$this->and_where_open()
				->where('email', 'LIKE', '%' . $searchword . '%')
				->or_where('username', 'LIKE', '%' . $searchword . '%')
				->and_where_close();
		} // foreach

		return $this->find_all();
	} // function


	/**
	 * Label definitions for validation.
	 * We need to overwrite this, because of the Model_Auth_User class.
	 *
	 * @return  array
	 */
	public function labels()
	{
		$labels = array();
		$properties = $this->get_properties();

		foreach ($properties as $key => $property)
		{
			$labels[$key] = $property['label'];
		} // foreach

		return $labels;
	} // function


	/**
	 * This method will return all selectable themes.
	 *
	 * @return  array
	 */
	public static function get_themes()
	{
		$return = array();
		$paths = glob(DOCROOT . 'assets' . DS . 'css' . DS . 'themes' . DS . '*');

		foreach ($paths as $path)
		{
			$path = substr(strrchr($path, DS), 1);
			$return[$path] = ucfirst($path);
		} // foreach

		return $return;
	} // function
} // End User Model