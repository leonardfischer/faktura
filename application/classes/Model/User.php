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
			->rule('password', 'min_length', array(':value', Kohana::$config->load('base')->get('password_minlength', 6)))
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
	 * @return  array
	 */
	public function get_table_data ()
	{
		return array(
			$this->id,
			$this->username,
			(empty($this->email) ? '<i class="icon-envelope-alt"></i>' : '<a href="mailto:' . $this->email . '" target="_blank" title="' . $this->email . '"><i class="icon-envelope"></i></a>'),
			$this->last_login(),
			'<td><div class="btn-group">' .
				'<a class="btn btn-primary btn-sm" href="' . Route::url('user', array('action' => 'edit', 'id' => $this->id)) . '">' . __('Edit') . '</a>' .
				'</div></td>'
		);
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
} // End User Model