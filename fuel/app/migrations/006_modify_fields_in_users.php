<?php

namespace Fuel\Migrations;

class Modify_fields_in_users
{
	public function up()
	{
		\DBUtil::modify_fields('users', array(
		    'profile_fields' => array('type' => 'text', 'null' => true),
			'last_login' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'login_hash' => array('constraint' => 255, 'type' => 'varchar', 'null' => true),
		));
	}

	public function down()
	{
		\DBUtil::modify_fields('users', array(
		    'profile_fields' => array('type' => 'text'),
			'last_login' => array('constraint' => 11, 'type' => 'int'),
			'login_hash' => array('constraint' => 255, 'type' => 'varchar'),
		));
	}
}