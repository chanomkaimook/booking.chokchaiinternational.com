<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ctl_register extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url', 'form');
    }

    public function index()
    {

        $this->load->view('register');
    }

    /**
     * 
     * * CRUD
     * register staff
     * 
     */
    public function insert_data_staff()
    {
        $array_text_error = array(
            'name_th'  => 'ชื่อ',
            'input_username'  => 'ชื่อรหัสผ่าน',
            'input_password'  => 'รหัสผ่าน'
        );

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $request = $this->input->post();

            if ($request['hidden_form_admin']) {
                $array_text_error = array(
                    'name_th'  => 'ชื่อ th',
                    'name_us'  => 'ชื่อ us',
                    'input_username'  => 'ชื่อรหัสผ่าน',
                    'input_password'  => 'รหัสผ่าน'
                );
            }

            $count_array = count($request);
            if ($count_array) {

                // ตรวจสอบ error
                foreach ($array_text_error as $key => $value) {
                    if (!textNull($request[$key])) {
                        $result = array(
                            'error' => 1,
                            'txt'   => 'โปรดระบุ ' . $array_text_error[$key],
                        );

                        echo json_encode($result);
                        exit;
                    }
                }

                #
                # ตรวจสอบ username
                $sql = $this->db->from('staff')
                    ->where('username', trim($request['input_username']))
                    ->where('verify is not null', null, false)
                    ->where('status', 1)
                    ->get();
                $num = $sql->num_rows();
                if ($num) {
                    $result = array(
                        'error' => 1,
                        'txt'   => 'ไม่สามารถใช้ชื่อรหัสนี้ได้'
                    );

                    echo json_encode($result);
                    exit;
                }

                #
                # setting
                $request = $_REQUEST;
                $data_roles = [];
                $data_permit = [];

                #
                # check value
                

                # roles
                $array_roles = trim($request['group_role']);
                // $sql_roles = $this->db->where('id', $roles_id)->get('roles');

                if ($array_roles) {
                    $array = explode(",", $array_roles);
                    if ($array) {
                        foreach ($array as $value) {
                            $data_roles_sub = array(
                                'permit_id'  => null,
                                'roles_id'  => $value
                            );
                            $data_roles[] = $data_roles_sub;
                        }
                    }
                }

                # roles
                $array_permit = (array)$request['permit_id'];
                if ($array_permit) {
                    foreach ($array_permit as $value) {
                        $data_roles_sub = array(
                            'permit_id'  => $value,
                            'roles_id'  => null
                        );
                        $data_roles[] = $data_roles_sub;
                        
                    }
                }

                $this->db->trans_begin();

                # 
                # employee
                $data_employee = array(
                    'name'          => textNull($request['name_th']),
                    'lastname'      => textNull($request['lastname_th']),
                    'name_us'       => textNull($request['name_us']),
                    'lastname_us'   => textNull($request['lastname_us']),

                );
                $this->db->insert('employee', $data_employee);
                $new_em_id = $this->db->insert_id();

                # level
                $data_staff = array(
                    'employee_id'       => $new_em_id,
                    'username'  => textNull($request['input_username']),
                    'password'  => md5(trim($request['input_password'])),
                );
                $this->db->insert('staff', $data_staff);
                $new_id = $this->db->insert_id();
                if ($new_id) {

                    #
                    # insert user roles
                    if ($data_roles && count($data_roles)) {
                        $this->load->library('permit');
                        $this->permit->insert_batch_data($data_roles, $new_id);
                    }

                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                    } else {
                        $this->db->trans_commit();
                    }

                    #
                    # helpdesk only
                    # insert user for helpdesk
                    if ($request['userfocus'] && !empty($this->session->userdata('user_code'))) {
                        $explode_userfocus = explode(",", $request['userfocus']);
                        if ($explode_userfocus && count($explode_userfocus)) {
                            foreach ($explode_userfocus as $value) {

                                $data_rolefocus = array(
                                    'staff_child'       => trim($value),
                                    'staff_owner'       => $new_id,
                                    'user_starts'       => $this->session->userdata('user_code')
                                );
                                $this->db->insert('roles_focus', $data_rolefocus);
                            }
                        }
                    }

                    $result = array(
                        'error' => 0,
                        'data'  => $this->db->get_where('staff', array('id' => $new_id))->row(),
                        'txt'   => 'ลงทะเบียนสำเร็จ รอเจ้าหน้าที่ยืนยันสถานะเพื่อเข้าใช้งาน'
                    );

                    echo json_encode($result);
                    exit;
                }
            }

            $this->db->free_result();



            $result = array(
                'error' => 1,
                'txt'   => 'ไม่พบข้อมูล'
            );

            echo json_encode($result);
            exit;
        }
    }
    public function update_data()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $returns = $this->mdl_register->update_data_login();

            echo $returns;
        } else {
            echo "no";
        }
    }
    public function delete_data()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $returns = $this->mdl_register->delete_data_login();

            echo $returns;
        } else {
            echo "no";
        }
    }
}
