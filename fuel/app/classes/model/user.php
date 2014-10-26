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

	public static function validate($factory)
	{
		$val = Validation::forge($factory);
		$val->add_field('username', 'Username', 'required|max_length[50]');
		$val->add_field('password', 'Password', 'required|max_length[50]');
		$val->add_field('first_name', 'First Name', 'required|max_length[50]');
		$val->add_field('last_name', 'Last Name', 'required|max_length[50]');
		$val->add_field('email', 'Email', 'required|valid_email|max_length[255]');
		$val->add_field('group', 'Group', 'required|valid_string[numeric]');
		$val->add_field('profile_fields', 'Profile Fields', 'required');
		$val->add_field('last_login', 'Last Login', 'required|valid_string[numeric]');
		$val->add_field('login_hash', 'Login Hash', 'required|max_length[255]');

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
