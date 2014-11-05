<?php
class Model_User extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'username',
		'password',
		'first_name',
		'last_name',
		'email',
		'group',
		'profile_fields',
		'last_login',
		'login_hash',
		'created_at',
		'updated_at',
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_save'),
			'mysql_timestamp' => false,
		),
	);

	public static function validate($factory, $data = null)
	{
		$val = Validation::forge($factory);
		$val->add_callable('Validation_Rules');
		$val->set_message('required', ':label requires a value.');
		$val->set_message('match_field', ':label should match :param:1.');

		$val->add_field('username', 'Username', 'required|max_length[50]|unique[users.username' . ($factory == 'edit' ? (',' . $data['username']) : '') . ']');
		$val->add_field('password', 'Password', 'required|max_length[255]');
		$val->add_field('password_retype', 'Password Retype', 'match_field[password]|required_with[password]');
		$val->add_field('first_name', 'First Name', 'required|max_length[50]');
		$val->add_field('last_name', 'Last Name', 'required|max_length[50]');
		$val->add_field('email', 'Email', 'required|valid_email|max_length[255]|unique[users.email' . ($factory == 'edit' ? (',' . $data['email']) : '') . ']');
		$val->add_field('group', 'Group', 'required|valid_string[numeric]');

		return $val;
	}

	public function getLastStatus()
	{	
		$result = Model_Log::find('first', [
			'where' 	=> [['user_id' => $this->id]],
			'order_by'	=> ['updated_at' => 'desc']
		]);
		return $result ? $result->status_id : -1;
	}

	public function getLastJobNo()
	{	
		$result = Model_Log::find('first', [
			'where' 	=> [['user_id' => $this->id]],
			'order_by'	=> ['updated_at' => 'desc']
		]);
		return $result ? $result->job_no : null;
	}

	public function getFullName()
	{
		return ucwords($this->first_name . ' ' . $this->last_name);
	}

}
