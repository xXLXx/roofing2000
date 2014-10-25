<?php
class Controller_Admin_Logs extends Controller_Admin{

	public function action_index()
	{
		$data['logs'] = Model_Log::find('all');
		$this->template->title = "Logs";
		$this->template->content = View::forge('admin\logs/index', $data);

	}

	public function action_view($id = null)
	{
		$data['log'] = Model_Log::find($id);

		$this->template->title = "Log";
		$this->template->content = View::forge('admin\logs/view', $data);

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
		$this->template->content = View::forge('admin\logs/create');

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
		$this->template->content = View::forge('admin\logs/edit');

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

		if (!$log->save()) {
			return new RuntimeException();
		} else {
			return json_encode($log->created_at);
		}
	}
}