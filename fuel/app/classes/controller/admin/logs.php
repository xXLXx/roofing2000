<?php
class Controller_Admin_Logs extends Controller_Admin{
    private $accessibleActions = ['madd_log'];

    public function before()
    {
        if (!Auth::has_access('logs.read') && !in_array(Request::active()->action, $this->accessibleActions)) {
            Response::redirect('admin');
        }

        parent::before();
    }

    public function action_index()
    {
        $data['logs'] = Model_Log::find('all', [
            'group_by'  => [DB::expr('FROM_UNIXTIME(updated_at, "%M")')],
            'order_by'  => ['updated_at' => 'DESC']
        ]);
        $this->template->title = "Logs";
        $this->template->content = View::forge('admin/logs/index', $data);

    }

    public function action_view($updated_at = null)
    {
        // $data['log'] = Model_Log::query()
        //  ->where([
        //      [(string)DB::expr('FROM_UNIXTIME(updated_at, "%m%Y")') => DB::expr('FROM_UNIXTIME(' . $updated_at .', "%m%Y")')]
        //  ]);
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

        $author_name = ucwords(Auth::get('first_name') . ' ' . Auth::get('last_name'));

        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        // Set document properties
        $objPHPExcel->getProperties()->setCreator($author_name)
            ->setLastModifiedBy($author_name)
            ->setTitle($month_text)
            ->setSubject($month_text)
            ->setDescription('List of logs for ' . $month_text)
            ->setKeywords('')
            ->setCategory('');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $currentSheet = $objPHPExcel->setActiveSheetIndex(0);

        $time_format = '%I:%M%p';
        $header_labels = ['Name', 'Username', 'Date', 'Time In', 'Time Out', 'Hours Worked', 'Job No.', 'Location'];
        $x = 'A';
        $y = 1;
        foreach ($header_labels as $value) {
            $currentSheet->setCellValue($x++ . $y, $value);
        }

        foreach ($logs as $item) {
            $userParent = isset($item['user']) ? $item['user'] : $item;
            $y++;

            $fields = [
                ucwords($userParent['first_name'] . ' ' . $userParent['last_name']),
                $userParent['username'],
                Date::forge($item['updated_at'])->format('%B ' . (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' ? '%#d' : '%e') . ', %Y'),
                Date::forge($item['updated_at'])->format($time_format),
                isset($item['timeout']) ? Date::forge($item['timeout'])->format($time_format) : Model_Log::LABEL_NOT_YET_TIMEOUT,
                isset($item['timeout']) ? round(($item['timeout'] - $item['updated_at']) / 3600, 3) : 'Cannot determine.',
                $item['job_no'],
                $item['location']
            ];

            $x = 'A';
            foreach ($fields as $field) {
                $currentSheet->setCellValue($x++ . $y, $field);
            }
        }

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($month_text);

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $month_text . '.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
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