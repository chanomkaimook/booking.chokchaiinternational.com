<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ctl_roles extends MY_Controller
{
    private $model;
    private $title;

    public function __construct()
    {
        parent::__construct();
        $modelname = 'mdl_roles';

        $this->load->model('admin/mdl_roles');
        $this->load->model('mdl_roles_control');
        // $this->load->model($modelname,'admin');
        // $this->load->model('mdl_user');
        // $this->load->model('mdl_register');
        // $this->load->model('mdl_staff');

        $this->middleware(
            array(
                'need'       => ['administrator'],
            )
        );

        // set language
        $this->lang->load('roles', $this->langs);

        // setting
        $this->model = $this->$modelname;
        $this->title = $this->lang->line('__menu_settingroles');
    }

    public function index()
    {
        $this->load->library('roles');
        // permit variable
        $array_permit = $this->roles->get_dataJS();
        $data['permit'] = $array_permit;

        $this->template->set_layout('lay_datatable');
        $this->template->title($this->title);
        $this->template->set_partial(
            'headlink',
            'partials/link/page',
            array(
                'data'  => array(
                    '<link href="' . base_url('') . 'asset/libs/treeview/style.css" rel="stylesheet" type="text/css" />',
                )
            )
        );
        $this->template->set_partial(
            'footerscript',
            'partials/script/page',
            array(
                'data'  => array(
                    '<script src="' . base_url('') . 'asset/libs/treeview/jstree.min.js"></script>',
                )
            )
        );
        $this->template->build('roles/index', $data);
    }

    /**
     *
     * get data to datatable
     * non-severside (load all data before display)
     *
     * # whois() = my_sql_helper
     * # textShow() = my_text_helper
     * # workstatus() = my_html_helper
     * # status_offview() = my_html_helper
     * # toThaiDateTimeString() = my_date_helper
     * 
     * @return void
     */
    public function get_dataTable()
    {
        $this->load->helper('my_date');

        $request = $_REQUEST;

        $data = $this->model->get_dataShowForEdit();
        $count = $this->model->get_data_all();

        $data_result = [];

        if ($data) {
            foreach ($data as $row) {

                $user_active_id = $row->USER_STARTS ? $row->USER_STARTS : $row->USER_UPDATE;

                if ($row->DATE_UPDATE) {
                    $query_date = $row->DATE_UPDATE;
                    $user_active = "(แก้) " . whois($row->USER_UPDATE);
                } else {
                    $query_date = $row->DATE_STARTS;
                    $user_active =  whois($row->USER_STARTS);
                }

                // $dom_workstatus = workstatus($row->WORKSTATUS, 'status');
                $dom_workstatus = "--";
                $dom_status = status_offview($row->STATUS_OFFVIEW);

                $sub_data = [];

                $sub_data['ID'] = $row->ID;
                $sub_data['CODE'] = textNull($row->CODE);
                $sub_data['NAME'] = textLang($row->NAME, $row->NAME_US);

                $sub_data['WORKSTATUS'] = array(
                    "display"   => $dom_workstatus,
                    "data"      =>  array(
                        'id'    => 0,
                    ),
                );

                $sub_data['STATUS'] = array(
                    "display"   => $dom_status,
                    "data"   => array(
                        'id'    => $row->STATUS_OFFVIEW,
                    ),
                );

                $sub_data['USER_ACTIVE'] = array(
                    "display"   => $user_active,
                    "data"   => array(
                        'id'    => $user_active_id,
                    ),
                );

                $sub_data['DATE_ACTIVE'] = array(
                    "display"   => toThaiDateTimeString($query_date, 'datetime'),
                    "timestamp" => date('Y-m-d H:i:s', strtotime($query_date))
                );

                $data_result[] = $sub_data;
            }
        }

        $result = array(
            "recordsTotal"      =>     count($data),
            "recordsFiltered"   =>     $count,
            "data"              =>     $data_result
        );

        echo json_encode($result);
    }

    //  *
    //  * CRUD
    //  * read
    //  * 
    //  * get data for item id
    //  *
    public function get_data(int $id = null)
    {
        $this->load->library('roles');

        $request = $_REQUEST;
        $item_id = $id ? $id : $request['id'];
        $array_permit = $this->roles->get_dataRolesJS($item_id, null, "result_array");
        $array_roles_child = $this->roles->get_dataRolesChild($item_id, null, "result_array");
        $array_permit_inchild = $this->roles->get_dataRolesChildJS($item_id, null, "result_array");
        
        $permit_all = array_merge($array_permit,$array_permit_inchild);

        $data = $this->model->get_data($item_id);
        $data->PERMIT = $permit_all;
        $data->PERMIT_HTML = html_roles_jstree($permit_all);
        $data->ROLES = $array_roles_child;

        // echo html_roles_jstree($array_permit);die;
        $result = $data;
        echo json_encode($result);
    }

    /**
     * get data role
     *
     * @return void
     */
    public function get_dataRole()
    {
        $data = $this->model->get_dataShow();

        $result = $data;
        echo json_encode($result);
    }

    public function get_dataPermitFromRole()
    {
        $this->load->library('roles');

        $request = $_REQUEST;
        $item_id = $request['id'];

        $explode = explode(",", $item_id);

        if ($explode) {
            $permit_all = [];
            foreach ($explode as $row_id) {
                $array_permit = $this->roles->get_dataRolesJS($row_id, null, "result_array");

                if(count($permit_all) == 0){
                    $permit_all = $array_permit;
                }else{
                    $pre_permit_all = $array_permit;
                    $permit_all = array_merge($permit_all, $pre_permit_all);
                }
            }
        }

        $data = $this->model->get_data($item_id);
        $data->PERMIT = $permit_all;
        $data->PERMIT_HTML = html_roles_jstree($permit_all);

        // echo html_roles_jstree($array_permit);die;
        $result = $data;
        echo json_encode($result);
    }

    //  *
    //  * CRUD
    //  * insert
    //  * 
    //  * insert data
    //  *
    public function insert_data()
    {
        # code...
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $returns = $this->model->insert_data();
            echo json_encode($returns);
        }
    }

    //  *
    //  * CRUD
    //  * update
    //  * 
    //  * update data
    //  *
    public function update_data()
    {
        # code...
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            /* print_r($this->input->post());

            die; */
            $returns = $this->model->update_data();
            echo json_encode($returns);
        }
    }


    //  *
    //  * CRUD
    //  * delete
    //  * 
    //  * delete data
    //  *
    public function delete_data()
    {
        # code...
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $returns = $this->model->delete_data();
            echo json_encode($returns);
        }
    }
}
