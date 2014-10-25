<?php
class Model_Status extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'name',
		'prompt_time',
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
		$val->add_field('name', 'Name', 'required|max_length[50]');
		$val->add_field('prompt_time', 'Prompt Time', 'required|valid_string[numeric]');

		return $val;
	}

}
