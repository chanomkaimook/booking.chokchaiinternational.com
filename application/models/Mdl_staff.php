<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mdl_staff extends CI_Model

{
    private $table = "staff";

    public function __construct()
    {
        parent::__construct();
    }

    //  =========================
    //  =========================
    //  CRUD
    //  =========================
    //  =========================

    //  *
    //  * CRUD
    //  * read
    //  * 
    //  * get data
    //  *
    /**
     * data
     *
     * @param integer|null $id = primary key
     * @param array $optionnal = [
     *                          select=array(a,b,c),
     *                          where=array(a=>desc,b=asc),
     *                          orderby=array(a=>desc,b=asc),
     *                          groupby=array(a,b),
     *                          limit=0,10,
     *                           ]
     * @return void
     */
    public function get_data(int $id = null, array $optionnal = [], string $type = "result", bool $limit = true)
    {
        $sql = (object) $this->get_sql($id, $optionnal, $limit);
        $query = $sql->get();

        if ($id) {
            return $query->row();
        } else {
            return $query->$type();
        }
    }

    #
    # count data to show all
    public function get_data_all(int $id = null, array $optionnal = [], bool $limit = true)
    {
        # code...
        $sql = (object) $this->get_sql($id, $optionnal, $limit);
        $sql->where($this->table . '.status', 1);
        $num = $sql->count_all_results(null, false);
        $sql->get();

        return $num;
    }

    //  *
    //  * CRUD
    //  * read
    //  * 
    //  * get data only for display (not data delete)
    //  *
    public function get_dataShow(int $id = null, array $optionnal = [], string $type = "result", bool $limit = true)
    {
        # code...
        $sql = (object) $this->get_sql($id, $optionnal, $limit);
        $sql->where($this->table . '.status', 1);

        $query = $sql->get();

        if ($type == "row") {
            return $query->row();
        } else {
            return $query->result();
        }
    }

    //  *
    //  * CRUD
    //  * insert
    //  * 
    //  * insert data
    //  *
    public function insert_data()
    {

        $result = array(
            'error'     => 1,
            'txt'       => 'ไม่มีการทำรายการ',
        );

        if (textShow($this->input->post('label_6'))) {
            $data = array(
                'code'  => textShow($this->input->post('label_2')),
                'name'  => textShow($this->input->post('label_6')),
                'workstatus'  => $this->input->post('label_1'),

                'user_starts'  => $this->session->userdata('user_code'),
            );

            $this->db->insert($this->table, $data);
            $new_id = $this->db->insert_id();

            // keep log
            log_data(array('insert ' . $this->table, 'insert', $this->db->last_query()));

            if ($new_id) {

                $result = array(
                    'error'     => 0,
                    'txt'       => 'ทำรายการสำเร็จ',
                    'data'      => array(
                        'id'    => $new_id
                    )
                );
            }
        }


        return $result;
    }

    //  *
    //  * CRUD
    //  * update
    //  * 
    //  * update data
    //  *
    /**
     * Undocumented function
     *
     * @param array|null $data_array = data update[column=>value]
     * @param array|null $data_staff = data where[column=>value]
     * @return void
     */
    public function update_data(array $data_array = null,array $data_staff = null)
    {
        $item_id = $this->input->post('item_id');

        if($data_array && is_array($data_array) && $data_staff){
            $this->db->update($this->table, $data_array, $data_staff);
        }else{

            $data = array(
                'code'  => textShow($this->input->post('label_2')),
                'name'  => textShow($this->input->post('label_6')),
                'workstatus'  => $this->input->post('label_1'),
    
                'date_update'  => date('Y-m-d H:i:s'),
                'user_update'  => $this->session->userdata('user_code'),
            );
    
            $this->db->where('id', $item_id);
            $this->db->update($this->table, $data);
        }

        // keep log
        log_data(array('update ' . $this->table, 'update', $this->db->last_query()));

        $result = array(
            'error'     => 0,
            'txt'       => 'ทำรายการสำเร็จ',
            'data'      => array(
                'id'    => $item_id
            )
        );

        return $result;
    }

    //  *
    //  * CRUD
    //  * delete
    //  * 
    //  * delete data
    //  *
    public function delete_data()
    {
        $item_id = textShow($this->input->post('item_id'));
        $item_remark = textShow($this->input->post('item_remark'));

        $result = array(
            'error' => 1,
            'txt'        => 'ไม่มีการทำรายการ'
        );

        if (!$item_id) {
            return $result;
        }

        $data_array = array(
            'status'      => 0,

            'date_update'  => date('Y-m-d H:i:s'),
            'user_update'  => $this->session->userdata('user_code'),
        );

        if ($item_remark) {
            $data_array['remark_delete'] = $item_remark;
        }

        $this->db->update($this->table, $data_array, array('id' => $item_id));

        // keep log
        log_data(array('delete ' . $this->table, 'update', $this->db->last_query()));

        $result = array(
            'error'     => 0,
            'txt'       => 'ทำรายการสำเร็จ'
        );

        return $result;
    }
    //  =========================
    //  =========================
    //  End CRUD
    //  =========================
    //  =========================



    //  =========================
    //  =========================
    //  Query
    //  =========================
    //  =========================
    /**
     * query
     *
     * @param integer|null $id
     * @param array $optionnal
     * @return void
     */
    function get_sql(int $id = null, array $optionnal = [], bool $limit = true)
    {
        $request = $_REQUEST;

        $hidden_start = "";
        $hidden_end = "";

        $sql = $this->db->from($this->table)
            ->join('employee', $this->table . '.employee_id=employee.id', 'left')
            ->where('employee.id >',0);
        
        if (textShow($request['hidden_datestart'])) {
            $hidden_start = textShow($request['hidden_datestart']);
        }
        if (textShow($request['hidden_dateend'])) {
            $hidden_end = textShow($request['hidden_dateend']);
        }

        if ($hidden_start && $hidden_end) {
            $sql->where('date(' . $this->table . '.date_starts) >=', $hidden_start);
            $sql->where('date(' . $this->table . '.date_starts) <=', $hidden_end);
        }

        if ($id) {
            $sql->where($this->table . '.id', $id);
        }

        if ($optionnal['select']) {
            $sql->select($optionnal['select']);
        } else {
            $sql->select('
            employee.NAME as NAME,
            employee.LASTNAME as LASTNAME,
            employee.EMAIL as EMAIL,
            employee.POSITION as POSITION,
            employee.DEPARTMENT as DEPARTMENT,
            employee.SECTION as SECTION,
            concat(employee.NAME," ",employee.LASTNAME) as staff_name,
            staff.ID as ID,
            staff.EMPLOYEE_ID as EMPLOYEE_ID,
            staff.USERNAME as USERNAME,
            staff.VERIFY as VERIFY,
            staff.DATE_STARTS as DATE_START,
            staff.DATE_UPDATE as DATE_UPDATE,
            staff.USER_STARTS as USER_START,
            staff.USER_UPDATE as USER_UPDATE,
            staff.STATUS as STATUS,
        ');
        }

        if ($optionnal['where'] && count($optionnal['where'])) {
            foreach ($optionnal['where'] as $column => $value) {
                $sql->where($column, $value);
            }
        }

        if ($optionnal['order_by'] && count($optionnal['order_by'])) {
            foreach ($optionnal['order_by'] as $column => $value) {
                $sql->order_by($column, $value);
            }
        } else {
            $sql->order_by($this->table . '.id', 'desc');
        }

        if ($optionnal['group_by'] && count($optionnal['group_by'])) {
            foreach ($optionnal['group_by'] as $column) {
                $sql->group_by($column);
            }
        }

        if ($limit == true) {
            if ($optionnal['limit']) {
                $sql->limit($optionnal['limit']);
            } else {

                if (isset($request['start']) && isset($request['length'])) {
                    $sql->limit($request['length'], $request['start']);
                } else {
                    // $sql->limit(10, 0);
                }
            }
        }


        return $sql;
    }
    //  =========================
    //  =========================
    //  End Query
    //  =========================
    //  =========================
}
