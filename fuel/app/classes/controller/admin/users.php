<?php
class Controller_Admin_Users extends Controller_Admin{


	public function before()
	{
		if (!Auth::has_access('users.read')) {
			Response::redirect('admin');
		}

		parent::before();
	}

	public function action_index()
	{
		$data['users'] = Model_User::find('all', ['where' => [['group', '<', Auth::get('group')]]]);
		$this->template->title = ucwords(Auth::get('first_name') . ' ' . Auth::get('last_name'));
		$this->template->content = View::forge('admin/users/index', $data);

	}

	public function action_view($id = null)
	{
		$data['user'] = Model_User::find($id);

		$this->template->title = ucwords(Auth::get('first_name') . ' ' . Auth::get('last_name'));
		$this->template->content = View::forge('admin/users/view', $data);

	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{
			$val = Model_User::validate('create');

			if ($val->run())
			{
				$user = Model_User::forge(array(
					'username' => Input::post('username'),
					'password' => Auth::hash_password(Input::post('password')),
					'first_name' => Input::post('first_name'),
					'last_name' => Input::post('last_name'),
					'email' => Input::post('email'),
					'group' => Input::post('group'),
					// 'profile_fields' => Input::post('profile_fields'),
					// 'last_login' => Input::post('last_login'),
					// 'login_hash' => Input::post('login_hash'),
				));

				if ($user and $user->save())
				{
					Session::set_flash('success', e('Added user #'.$user->id.'.'));

					Response::redirect('admin/users');
				}

				else
				{
					Session::set_flash('error', e('Could not save user.'));
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = ucwords(Auth::get('first_name') . ' ' . Auth::get('last_name'));
		$this->template->content = View::forge('admin/users/create');

	}

	public function action_edit($id = null)
	{
		$user = Model_User::find($id);
		$username = $user->username;
		$email = $user->email;
		$val = Model_User::validate('edit', compact('username', 'email'));

		if ($val->run())
		{
			$user->username = Input::post('username');
			$user->password = Auth::hash_password(Input::post('password'));
			$user->first_name = Input::post('first_name');
			$user->last_name = Input::post('last_name');
			$user->email = Input::post('email');
			$user->group = Input::post('group');
			// $user->profile_fields = Input::post('profile_fields');
			// $user->last_login = Input::post('last_login');
			// $user->login_hash = Input::post('login_hash');

			if ($user->save())
			{
				Session::set_flash('success', e('Updated user #' . $id));

				Response::redirect('admin/users');
			}

			else
			{
				Session::set_flash('error', e('Could not update user #' . $id));
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$user->username = $val->validated('username');
				$user->password = $val->validated('password');
				$user->first_name = $val->validated('first_name');
				$user->last_name = $val->validated('last_name');
				$user->email = $val->validated('email');
				$user->group = $val->validated('group');
				$user->profile_fields = $val->validated('profile_fields');
				$user->last_login = $val->validated('last_login');
				$user->login_hash = $val->validated('login_hash');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('user', $user, false);
		}

		$this->template->title = ucwords(Auth::get('first_name') . ' ' . Auth::get('last_name'));
		$this->template->content = View::forge('admin/users/edit');

	}

	public function action_delete($id = null)
	{
		if ($user = Model_User::find($id))
		{
			$user->delete();

			Session::set_flash('success', e('Deleted user #'.$id));
		}

		else
		{
			Session::set_flash('error', e('Could not delete user #'.$id));
		}

		Response::redirect('admin/users');

	}


}