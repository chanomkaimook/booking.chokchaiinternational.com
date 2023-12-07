<?php
defined('BASEPATH') or exit('No direct script access allowed');

class mdl_profile extends CI_Model

{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_user_owner()
    {
        $id = $this->input->get('id');

        $query = $this->db->select('*')
            ->where('id', $id)
            ->where('status', 1)
            ->get('staff');

        return  $query->row();
    }

    public function get_data_staff()
    {
        $id = $this->session->userdata('user_code');
        $query = $this->db->select('
            employee.NAME as NAME,
            employee.LASTNAME as LASTNAME,
            employee.EMAIL as EMAIL,
            employee.POSITION as POSITION,
            employee.DEPARTMENT as DEPARTMENT,
            employee.SECTION as SECTION,
            staff.ID as ID,
            staff.DATE_START as DATE_START,
        ')
            ->join('employee', 'staff.employee_id = employee.id', 'left')
            ->where('staff.id', $id)
            ->where('staff.status', 1)
            ->get('staff');

        return $query->row();
    }

    public function get_data_employee(int $id = null)
    {
        if ($id) {
            $query = $this->db->select('NAME')
                ->where('id', $id)
                ->where('status', 1)
                ->get('employee');

            return $query->row();
        }
    }

    public function get_employee_name(int $id = null)
    {
        $result = 'ไม่ระบุ';
        if ($id) {
            $data = $this->get_data_employee($id);
            if ($data) {
                $result = $data->NAME;
            }
        }
        return $result;
    }

    public function update_profile()
    {
        $data_array = array(
            'ID' => $this->input->post('id'),
            'OWNER1' => $this->input->post('owner1'),
            'OWNER2' => $this->input->post('owner2'),
            'OWNER3' => $this->input->post('owner3'),
            'DATE_UPDATE' =>   date('Y-m-d H:i:s'),
            'USER_UPDATE' =>   $this->session->userdata('user_code'),
        );
        $result = array();

        if ($this->input->post('id')) {
            $id = $data_array['ID'];

            $this->db->where('id', $id);
            $this->db->update('staff', $data_array);

            // keep log
            log_data(array('update', 'update', $this->db->last_query()));

            $query = $this->db->select('*')
                ->where('verify is not null',null,false)
                ->where('status', 1)
                ->where('id', $data_array['ID'])
                ->get('staff');

            $data = $query->row();

            foreach ($data as $row) {
                $result = array(
                    'owner1' => $row->OWNER1,
                    'owner2' => $row->OWNER2,
                    'owner3' => $row->OWNER3,
                );
            }
        }

        return $result;
    }
}
