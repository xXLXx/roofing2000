<?php
class Controller_Admin_Statuses extends Controller_Admin{

    public function action_index()
    {
        $data['statuses'] = Model_Status::find('all');
        $this->template->title = "Statuses";
        $this->template->content = View::forge('admin/statuses/index', $data);

    }

    public function action_view($id = null)
    {
        $data['status'] = Model_Status::find($id);

        $this->template->title = "Status";
        $this->template->content = View::forge('admin/statuses/view', $data);

    }

    public function action_create()
    {
        if (Input::method() == 'POST')
        {
            $val = Model_Status::validate('create');

            if ($val->run())
            {
                $status = Model_Status::forge(array(
                    'name' => Input::post('name'),
                    'prompt_time' => Input::post('prompt_time'),
                ));

                if ($status and $status->save())
                {
                    Session::set_flash('success', e('Added status #'.$status->id.'.'));

                    Response::redirect('admin/statuses');
                }

                else
                {
                    Session::set_flash('error', e('Could not save status.'));
                }
            }
            else
            {
                Session::set_flash('error', $val->error());
            }
        }

        $this->template->title = "Statuses";
        $this->template->content = View::forge('admin/statuses/create');

    }

    public function action_edit($id = null)
    {
        $status = Model_Status::find($id);
        $val = Model_Status::validate('edit');

        if ($val->run())
        {
            $status->name = Input::post('name');
            $status->prompt_time = Input::post('prompt_time');

            if ($status->save())
            {
                Session::set_flash('success', e('Updated status #' . $id));

                Response::redirect('admin/statuses');
            }

            else
            {
                Session::set_flash('error', e('Could not update status #' . $id));
            }
        }

        else
        {
            if (Input::method() == 'POST')
            {
                $status->name = $val->validated('name');
                $status->prompt_time = $val->validated('prompt_time');

                Session::set_flash('error', $val->error());
            }

            $this->template->set_global('status', $status, false);
        }

        $this->template->title = "Statuses";
        $this->template->content = View::forge('admin/statuses/edit');

    }

    public function action_delete($id = null)
    {
        if ($status = Model_Status::find($id))
        {
            $status->delete();

            Session::set_flash('success', e('Deleted status #'.$id));
        }

        else
        {
            Session::set_flash('error', e('Could not delete status #'.$id));
        }

        Response::redirect('admin/statuses');

    }

    public function action_mget_all()
    {
        $statuses = [];
        $result = Model_Status::find('all');

        foreach ($result as $status) {
            $statuses[$status->id] = ['name' => $status->name, 'prompt_time' => $status->prompt_time];
        }

        return Format::forge($statuses)->to_json();
    }
}