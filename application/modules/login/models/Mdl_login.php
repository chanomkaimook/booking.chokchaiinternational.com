<?php
defined('BASEPATH') or exit('No direct script access allowed');

class mdl_login extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Authorization_token');
        $this->load->library('Permit');
    }

    public function check_login()
    {
        if (trim($this->input->post('user_name')) && trim($this->input->post('user_password'))) {
            $user_name = trim($this->input->post('user_name'));
            $user_password = md5(trim($this->input->post('user_password')));

            $sql = $this->db->select('
                employee.ID as EMPLOYEE_ID,
                employee.NAME as NAME,
                employee.LASTNAME as LASTNAME,
                employee.DEPARTMENT as DEPARTMENT,
                employee.SECTION as SECTION,

                staff.ID as ID,
                staff.USERNAME as USERNAME,
                staff.DATE_STARTS as DATE_STARTS,
                section.ID as SECTION_ID,
                department.ID as DEPARTMENT_ID,
            ')
                ->join('employee', 'staff.employee_id = employee.id', 'left')
                ->join('section', 'section.name = employee.section', 'left')
                ->join('department', 'department.name = employee.department', 'left')
                ->where('staff.username', $user_name)
                ->where('staff.password', $user_password)
                ->where('staff.verify is not null', null, false)
                ->where('staff.status in(1,6,7,8,9)',null,false)
                ->get('staff');
            $number = $sql->num_rows();  //num_rows() นับจำนวนแถว

            if ($number == 1) {
                $row = $sql->row();

                $staff_id = $row->ID;

                if (strnatcmp($user_name, $row->USERNAME) == 0) {

                    //
                    // update status to normal (1)
                    //
                    $this->permit->staff_restore($staff_id);

                    //
                    // create array permit
                    $array_permit = $this->permit->get_dataPermitSet($staff_id);
// print_r($array_permit);exit;
                    $permit = "";

                    if ($array_permit) {
                        /* foreach ($query_permit->result() as $row_permit) {

                        #
                        # select permit from json data 
                        # convert to array
                        $permit_name = $row_permit->permit_name;

                        if($permit_name){
                            $json = json_decode($permit_name);
                            if (count($json->data)) {
                                foreach ($json->data as $key => $value) {
                                    $permit_allow[] = $value;
                                }
                            }
                        }

                        # select permit bane from json data
                        $permit_name_ban = $row_permit->permit_name_ban;

                        if($permit_name_ban){
                            $json_bane = json_decode($permit_name_ban);
                            if (count($json_bane->data)) {
                                foreach ($json_bane->data as $key => $value) {
                                    $permit_ban[] = $value;
                                }
                            }
                        }
                    } */

                        #
                        # fill data ban out
                        // $permit = array_values(array_diff($permit_allow, $permit_ban));

                        #
                        # set permit convert to json data
                        $permit = json_encode($array_permit);
                    }

                    // create token for caching
                    $array = array(
                        'staff_id'    => $staff_id
                    );
                    $token = $this->authorization_token->generateToken($array);

                    $result = array(
                        'error' => 0,
                        'data' => $sql->row(),
                        'token' => $token
                    );

                    //
                    // caching token
                    // Save into the cache for 1 day
                    // $this->caching->save('authorization', $token);

                } else {
                    $result = array(
                        'error' => 1,
                        'text' => 'ชื่อผู้ใช้ ไม่ถูกต้อง',
                        'data' => ''
                    );
                }
            } else {
                $result = array(
                    'error' => 1,
                    'text' => 'ไม่พบข้อมูล',
                    'data' => ''
                );
            }
        } else {
            $result = array(
                'error' => 1,
                'text' => 'กรุณากรอกข้อมูลให้ครบ',
                'data' => ''
            );
        }

        return $result;
    }
}
