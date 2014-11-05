<?php
class Controller_Admin_Logs extends Controller_Admin{

	public function action_index()
	{
		$data['logs'] = Model_Log::find('all', [
			'group_by'	=> [DB::expr('FROM_UNIXTIME(updated_at, "%M")')],
			'order_by'	=> ['updated_at' => 'DESC']
		]);
		$this->template->title = "Logs";
		$this->template->content = View::forge('admin/logs/index', $data);

	}

	public function action_view($updated_at = null)
	{
		// $data['log'] = Model_Log::query()
		// 	->where([
		// 		[(string)DB::expr('FROM_UNIXTIME(updated_at, "%m%Y")') => DB::expr('FROM_UNIXTIME(' . $updated_at .', "%m%Y")')]
		// 	]);
		$results = Model_Log::get_for_month($updated_at)->as_array();
		$data['updated_at'] = $updated_at;

		$data['logs'] = $this->get_logs_from_array($results);

		// var_dump($data['logs']); exit();

		$this->template->title = "Log";
		$this->template->content = View::forge('admin/logs/view', $data);

	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{
			$val = Model_Log::validate('create');

			if ($val->run())
			{
				$log = Model_Log::forge(array(
					'user_id' => Input::post('user_id'),
					'status_id' => Input::post('status_id'),
					'location' => Input::post('location'),
				));

				if ($log and $log->save())
				{
					Session::set_flash('success', e('Added log #'.$log->id.'.'));

					Response::redirect('admin/logs');
				}

				else
				{
					Session::set_flash('error', e('Could not save log.'));
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = "Logs";
		$this->template->content = View::forge('admin/logs/create');

	}

	public function action_edit($id = null)
	{
		$log = Model_Log::find($id);
		$val = Model_Log::validate('edit');

		if ($val->run())
		{
			$log->user_id = Input::post('user_id');
			$log->status_id = Input::post('status_id');
			$log->location = Input::post('location');

			if ($log->save())
			{
				Session::set_flash('success', e('Updated log #' . $id));

				Response::redirect('admin/logs');
			}

			else
			{
				Session::set_flash('error', e('Could not update log #' . $id));
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$log->user_id = $val->validated('user_id');
				$log->status_id = $val->validated('status_id');
				$log->location = $val->validated('location');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('log', $log, false);
		}

		$this->template->title = "Logs";
		$this->template->content = View::forge('admin/logs/edit');

	}

	public function action_delete($id = null)
	{
		if ($log = Model_Log::find($id))
		{
			$log->delete();

			Session::set_flash('success', e('Deleted log #'.$id));
		}

		else
		{
			Session::set_flash('error', e('Could not delete log #'.$id));
		}

		Response::redirect('admin/logs');

	}

	public function action_madd_log()
	{
		$log = Model_Log::forge();
		$log->user_id = Input::post('user_id');
		$log->status_id = Input::post('status_id');
		$log->latitude = Input::post('lat');
		$log->longitude = Input::post('lng');
		$log->job_no = Input::post('job_no');
		$log->location = Input::post('location');

		if (!$log->save()) {
			return new RuntimeException();
		} else {
			return json_encode($log->created_at);
		}
	}

	public function action_get_xls($updated_at = null)
	{
		$logs = [];

		if ($updated_at) {
			$logs = $this->get_logs_from_array(Model_Log::get_for_month($updated_at));
			$month_text = Date::forge($updated_at)->format('%B %Y');
		} else {
			$logs = $this->get_logs_from_array(Model_Log::get_for_all());
			$month_text = 'All months';
		}

		$xls = ExportXLS::forge($month_text . '.xls');
		$xls->addHeader('Logs for ' . $month_text);
		$xls->addHeader(null);
		$xls->addHeader(['Name', 'Username', 'Time In', 'Time Out', 'Job No.', 'Location']);

		$date_format = '%B ' . (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' ? '%#d' : '%e') . ', %Y %I:%M%p';

		foreach ($logs as $item) {
			// var_dump($item);
			// $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $item['latitude'] . ',' . $item['longitude'];

			// $ch = curl_init();
		 //   	curl_setopt($ch, CURLOPT_URL, $url);
		 //   	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		 //   	curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
		 //   	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		 //   	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

			// $location = 'Location not detected.';
			// $data = json_decode(curl_exec($ch), true);
			// curl_close($ch);

			// if ($data and sizeof($data['results']) > 0) {
			// 	$location = $data['results'][0]['formatted_address'];
			// }

			$userParent = isset($item['user']) ? $item['user'] : $item;

			$xls->addRow([
				ucwords($userParent['first_name'] . ' ' . $userParent['last_name']),
				$userParent['username'],
				Date::forge($item['updated_at'])->format($date_format),
				isset($item['timeout']) ? Date::forge($item['timeout'])->format($date_format) : Model_Log::LABEL_NOT_YET_TIMEOUT,
				$item['job_no'],
				$item['location']
			]);
		}

		$xls->sendFile();
	}

	public function get_logs_from_array($results)
	{
		$logs = [];

		foreach ($results as $result) {
			$user_id = $result['user_id'];
			$status_id = $result['status_id'];

		 	if (!isset($insertedKeys[$user_id]) && $status_id == Model_Status::TIME_IN) {
		 		$insertedKeys[$user_id] = sizeof($logs);
		 		$logs[] = $result;
		 	} else if (isset($insertedKeys[$user_id]) && $status_id == Model_Status::TIME_OUT) {
		 		$logs[$insertedKeys[$user_id]]['timeout'] = $result['updated_at'];
		 		unset($insertedKeys[$user_id]);
		 	}
		}

		return $logs;
	}
}