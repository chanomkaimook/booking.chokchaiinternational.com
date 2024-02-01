<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mdl_user extends CI_Model

{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_data_staff()
    {
        $request = $_REQUEST;

        $id = $request['id'];

        $sql = $this->db->select('
            employee.NAME as NAME,
            employee.NAME_US as NAME_US,
            employee.LASTNAME as LASTNAME,
            employee.LASTNAME_US as LASTNAME_US,
            employee.EMAIL as EMAIL,
            employee.POSITION as POSITION,
            employee.DEPARTMENT as DEPARTMENT,
            employee.SECTION as SECTION,
            concat(employee.NAME," ",employee.LASTNAME) as staff_name,
            staff.ID as ID,
            staff.USERNAME as USERNAME,
            staff.VERIFY as VERIFY,
            staff.DATE_STARTS as DATE_STARTS,
            staff.DATE_UPDATE as DATE_UPDATE,
            staff.STATUS as STATUS,
        ')
            ->join('employee', 'staff.employee_id = employee.id', 'left')
            ->where('staff.verify is not null', null, false)
            ->where('staff.id !=', 1)
            ->where('staff.status >=', 1)
            ->order_by('staff.id', 'desc');

        if (textShow($request['hidden_datestart'])) {
            $hidden_start = textShow($request['hidden_datestart']);
        }
        if (textShow($request['hidden_dateend'])) {
            $hidden_end = textShow($request['hidden_dateend']);
        }

        if ($hidden_start && $hidden_end) {
            $sql->where('date(staff.date_starts) >=', $hidden_start);
            $sql->where('date(staff.date_starts) <=', $hidden_end);
        }

        if ($id) {
            $sql->where('staff.id', $id);
        }

        $query = $sql->get('staff');

        if (!$id) {
            $result = $query->result();
        } else {
            $result = $query->row();
        }

        return $result;
    }

    /* public function get_user()
    {
        $id = $this->input->get('id');

        $query = $this->db->select('*')
            ->where('id', $id)
            ->where('status', 1)
            ->get('staff');

        return $query->row();
    } */

    public function update_data()
    {
        $date_update = date('Y-m-d H:i:s');
        $user_update = $this->userlogin;

        $id = $this->input->post('item_id');
        $array_role = $this->input->post('user_role');
        $array_permit = $this->input->post('permit_id');
        $data_roles = [];

        if ($id) {

            $this->db->trans_begin();

            $sql_staff = $this->db->select('employee_id')
                ->where('id', $id)
                ->get('staff');
            $row_staff = $sql_staff->row();
            $employee_id = $row_staff->employee_id;

            // 
            // Employee
            // 
            $data_employee = array(
                'NAME' => textNull($this->input->post('name_th')),
                'NAME_US' => textNull($this->input->post('name_us')),
                'LASTNAME' => textNull($this->input->post('lastname_th')),
                'LASTNAME_US' => textNull($this->input->post('lastname_us')),
                'DATE_UPDATE' =>   $date_update,
                'USER_UPDATE' =>   $user_update,
            );
            $this->db->where('id', $employee_id);
            $this->db->update('employee', $data_employee);

            // 
            // Staff
            // 
            $data_staff = array(
                'STATUS' => 9,     // 10 = force login again
            );
            $this->db->where('id', $id);
            $this->db->update('staff', $data_staff);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }

            // 
            // Role
            // 
            if ($array_role && is_array($array_role)) {
                foreach ($array_role as $value) {
                    $data_roles_sub = array(
                        'permit_id'  => null,
                        'roles_id'  => $value
                    );
                    $data_roles[] = $data_roles_sub;
                }
            }

            if ($array_permit) {
                foreach ($array_permit as $value) {
                    $data_roles_sub = array(
                        'permit_id'  => $value,
                        'roles_id'  => null
                    );
                    $data_roles[] = $data_roles_sub;
                }
            }

            

            #
            # helpdesk only
            # insert user for helpdesk
            # if role = 8 (helpdesk) will clear roles_focus
            /* if (trim($this->input->post('role')) == 8) {
                $this->db->delete('roles_focus', array('staff_owner' => $id));

                $userfocus = trim($this->input->post('userfocus'));
                if ($userfocus) {
                    $explode_userfocus = explode(",", $userfocus);
                    foreach ($explode_userfocus as $value) {

                        $data_rolefocus = array(
                            'staff_child'       => trim($value),
                            'staff_owner'       => $id,
                            'user_starts'       => $this->session->userdata('user_code')
                        );
                        $this->db->insert('roles_focus', $data_rolefocus);
                    }
                }
            }

            // keep log
            log_data(array('update user', 'update', $this->db->last_query())); */

            $result = array(
                'error' =>   0,
                'text' => '',
                'id' => $id,
            );
        } else {
            $result = array(
                'error' =>   1,
                'text' => 'ไม่พบ ID',
                'id' => null,
            );
        }

        return $result;
    }

    public function delete_user()
    {
        $data_array = array(
            'ID' => $this->input->post('id'),
            'DATE_UPDATE' =>   date('Y-m-d H:i:s'),
            'STATUS' =>   '0',

        );

        if ($this->input->post('id')) {
            $id = $data_array['ID'];

            $this->db->where('id', $id);
            $this->db->update('staff', $data_array);

            // keep log
            log_data(array('delete', 'update', $this->db->last_query()));

            $result = array(
                'error' =>   0,
                'text' => 'ลบสำเร็จแล้ว',
                'id' => $data_array['ID'],
            );
        } else {
            $result = array(
                'error' =>   1,
                'text' => 'ไม่พบ ID',
                'id' => null,
            );
        }

        return $result;
    }
}
