<?php

namespace Fuel\Migrations;

class Add_job_no_to_logs
{
	public function up()
	{
		\DBUtil::add_fields('logs', array(
			'job_no' => array('constraint' => 50, 'type' => 'varchar'),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('logs', array(
			'job_no'

		));
	}
}