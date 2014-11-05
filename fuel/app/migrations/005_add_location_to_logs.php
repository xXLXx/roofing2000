<?php

namespace Fuel\Migrations;

class Add_location_to_logs
{
	public function up()
	{
		\DBUtil::add_fields('logs', array(
			'location' => array('constraint' => 255, 'type' => 'varchar'),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('logs', array(
			'location'

		));
	}
}