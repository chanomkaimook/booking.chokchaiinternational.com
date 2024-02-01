<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ctl_user extends MY_Controller
{
    private $model;
    private $title;

    public function __construct()
    {
        parent::__construct();
        $modelname = 'mdl_user';

        $this->middleware();

        $this->load->model('mdl_user');
        $this->load->model('mdl_roles');
        $this->load->model('mdl_role_focus');
        // $this->load->model('mdl_register');
        // $this->load->model('mdl_staff');

        $this->middleware(
            array(
                'need'       => ['administrator'],
            )
        );

        $this->load->library('Permit');

        // set language
        $this->lang->load('user', $this->langs);
        $this->lang->load('roles', $this->langs);

        // setting
        $this->model = $this->$modelname;
        $this->title = $this->lang->line('__menu_users');
    }

    public function index()
    {
        $data['role'] = $this->mdl_roles->get_dataShow();

        // permit variable
        $this->load->library('roles');
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
        $this->template->build('users/index', $data);
    }

    public function fetch_data()
    {
        $this->load->helper('my_date');


        if ($item_id = $this->input->get('id')) {
            $this->load->model('mdl_staff');
            $data[0] = $this->mdl_staff->get_data($item_id);
        } else {
            $data = $this->model->get_data_staff();
        }

        $icon_head = '<i class="mdi mdi-star text-warning mdi-18px" title="verified user"></i>';

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


                $dom_status = status_online($row->STATUS);

                $sub_data = [];
                $date_start = toDateTimeString($row->DATE_STARTS, 'datetime');
                $date_update = textNull($row->DATE_UPDATE) ? toDateTimeString($row->DATE_UPDATE, 'datetime') : null;

                $sub_data['ID'] = $row->ID;
                $sub_data['NAME'] = textLang($row->NAME, $row->NAME_US, false);
                $sub_data['LASTNAME'] = textLang($row->LASTNAME, $row->LASTNAME_US, false);
                $sub_data['USERNAME'] = $row->USERNAME;
                $sub_data['DATE_STARTS'] = array(
                    "display"   => $date_start,
                    "timestamp" => date('Y-m-d H:i:s', strtotime($row->DATE_STARTS))
                );

                $sub_data['DATE_ACTIVE'] = array(
                    "display"   => $date_update,
                    "timestamp" => date('Y-m-d H:i:s', strtotime($row->DATE_UPDATE))
                );

                $sub_data['USER_ACTIVE'] = array(
                    "display"   => $user_active,
                    "data"   => array(
                        'id'    => $user_active_id,
                    ),
                );

                $sub_data['STATUS'] = array(
                    "display"   => $dom_status,
                    "data"   => array(
                        'id'    => $row->STATUS,
                    ),
                );

                $data_result[] = $sub_data;
            }
        }
        $result = array(
            'data' => $data_result
        );

        echo json_encode($result);
    }

    //  *
    //  * CRUD
    //  * read
    //  * 
    //  * get data for item id
    //  *
    public function get_user()
    {
        $this->load->library('roles');

        $user_login = $this->user_login;
        $request = $_REQUEST;

        if ($request['id']) {
            $user_login = $request['id'];
        }

        $array_permit = [];
        $array_permit_only = [];

        $data = $this->model->get_data_staff();
        $user_permit = $this->permit->get_dataPermitSet($user_login);
        // $data_role_focus = $this->mdl_role_focus->get_data();

        $item_id = $user_permit['roles_id_list'];

        $array_permit = $this->roles->get_dataRolesJS($item_id, null, "result_array");
        $array_roles_child = $this->roles->get_dataRolesGroup($item_id, null, "result_array");
print_r($array_roles_child);

        $permit_all = $array_permit;


        // permit id
        $array_permit_only = $this->roles->get_dataPermitOnly($user_login, null, "result_array");

        if ($array_permit_only) {
            foreach ($array_permit_only as $index => $column) {
                $key_name = $column['MENUS_CODE'];
                $permit_all[$key_name][] = $array_permit_only[$index];
            }
        }

        if($data){
            $data->PERMIT = $permit_all;
            $data->PERMIT_HTML = html_roles_jstree($permit_all);
            $data->ROLES = $array_roles_child;
            $data->PERMIT_NOROLE = $array_permit_only;
        }

        $result = $data;
        echo json_encode($result);
    }

    public function update_data()
    {
        $data = $this->model->update_data();
        $result = array(
            'data' => $data
        );

        echo json_encode($result);
    }

    public function delete_user()
    {
        $data = $this->model->delete_user();
        $result = array(
            'data' => $data
        );

        echo json_encode($result);
    }
}
