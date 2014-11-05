<?php
class Model_Log extends \Orm\Model
{
	const LABEL_NOT_YET_TIMEOUT = 'Not yet timed-out.';

	protected static $_properties = array(
		'id',
		'user_id',
		'status_id',
		'latitude',
		'longitude',
		'job_no',
		'location',
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

	protected static $_belongs_to = array(
		'user' => array(
	        'key_from' => 'user_id',
	        'model_to' => 'Model_User',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => false,
	    )
	);

	public static function validate($factory)
	{
		$val = Validation::forge($factory);
		$val->add_field('user_id', 'User Id', 'required|valid_string[numeric]');
		$val->add_field('status_id', 'Status Id', 'required|valid_string[numeric]');
		$val->add_field('location', 'Location', 'required|max_length[255]');

		return $val;
	}

	public static function get_for_month($updated_at)
	{
		return DB::query('SELECT ' . Model_Log::table() . '.*, ' .
					Model_User::table() . '.`username`, ' .
					Model_User::table() . '.`first_name`, ' .
					Model_User::table() . '.`last_name`, ' .
					Model_User::table() . '.`email`' .
					' FROM ' . Model_Log::table() .
					' LEFT JOIN ' . Model_User::table() . ' ON `user_id` = ' . Model_User::table() . '.`id`' . 
					' WHERE FROM_UNIXTIME(' . Model_Log::table() . '.`updated_at`, "%m%Y") = FROM_UNIXTIME(' . $updated_at .', "%m%Y")' . 
					' ORDER BY ' . Model_Log::table() . '.`updated_at` ASC')
					->execute();
	}

	public static function get_for_all()
	{
		return Model_Log::find('all', [
			'related'	=> ['user'],
			'order_by'	=> ['updated_at' => 'ASC']
		]);
	}

}
