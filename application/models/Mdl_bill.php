<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mdl_bill extends CI_Model

{
    private $table = "bill";
    private $fildstatus = "status";

    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        // $this->db->free_result();
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
    public function get_data(int $id = null, array $optionnal = null, string $type = "result")
    {
        $sql = (object) $this->get_sql($id, $optionnal);
        $query = $sql->get();

        if ($id) {
            return $query->row();
        } else {
            return $query->$type();
        }
    }

    #
    # count data to show all
    public function get_data_all(int $id = null, array $optionnal = null)
    {
        # code...
        $optionnal['select'] = 'count(' . $this->table . '.id) as total';

        $data = (object) $this->get_dataShow($id, $optionnal, 'row');
        $num = $data->total;

        return $num;
    }

    //  *
    //  * CRUD
    //  * read
    //  * 
    //  * get data only for display (not data delete)
    //  *
    public function get_dataShow(int $id = null, array $optionnal = null, string $type = "result")
    {
        # code...
        $sql = (object) $this->get_sql($id, $optionnal, $type);
        if($this->fildstatus){
            $sql->where($this->table . '.' . $this->fildstatus, 1);
        }

        $query = $sql->get();

        if ($id) {
            return $query->row();
        } else {
            return $query->$type();
        }
    }

    //  *
    //  * Check validate
    //  * validation
    //  *
    /**
     * Check validate
     *
     * @param array|null $arrayset = array from method POST or GET
     * @param array|null $array_to_find
     * @return void
     */
    function check_value_valid($arrayset, array $array_to_find = null)
    {

        $result = false;

        if ($array_to_find) {
            $array_text_error = $array_to_find;
        } else {
            $array_text_error = array(
                'customer_name'       => 'ชื่อ'
            );
        }

        if (is_array($array_text_error) && count($array_text_error)) {
            if ($text = check_value_valid($array_text_error, $arrayset)) {
                $result = array(
                    'error' => 1,
                    'txt'   => 'โปรดระบุ ' . $text,
                );

                return $result;
            }
        }


        return $result;
    }

    /**
     * Check duplicate
     *
     * @param array|null $arraywhere = array where query
     * @param string|null $valueshow = value for show when error
     * @param string|null $table = table name for check
     * @return void
     */
    function check_dup($arraywhere, string $valueshow = null, string $table = null)
    {
        $result = false;

        if ($arraywhere && $valueshow) {

            if (!$table) {
                $table = $this->table;
            }

            if (check_dup($arraywhere, $table)) {
                $result = array(
                    'error' => 1,
                    'txt'   => $valueshow . ' ซ้ำในระบบ ',
                );
            }
        }

        return $result;
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

        $request = $_POST;
        if ($return = $this->check_value_valid($request)) {
            return $return;
        }

        if ($request) {
            $data = array(
                'code'  => textNull($this->input->post('label_2')),
                'name'  => textNull($this->input->post('label_6')),
                'workstatus'  => $this->input->post('label_1'),

                'user_starts'  => $this->userlogin,
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
    public function update_data()
    {
        $item_id = $this->input->post('item_id');

        $request = $_POST;
        if ($return = $this->check_value_valid($request)) {
            return $return;
        }

        $array_chk_dup = array(
            'name' => $request['item_name'],
            'status' => 1,
            'id !=' => $item_id,
        );
        if ($return = $this->check_dup($array_chk_dup, $request['item_name'])) {
            return $return;
        }

        $data = array(
            'code'  => textNull($this->input->post('label_2')),
            'name'  => textNull($this->input->post('label_6')),
            'workstatus'  => $this->input->post('label_1'),

            'date_update'  => date('Y-m-d H:i:s'),
            'user_update'  => $this->userlogin,
        );

        $this->db->where('id', $item_id);
        $this->db->update($this->table, $data);

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
        $item_id = textNull($this->input->post('item_id'));
        $item_remark = textNull($this->input->post('item_remark'));

        $result = array(
            'error' => 1,
            'txt'        => 'ไม่มีการทำรายการ'
        );

        if (!$item_id) {
            return $result;
        }

        $data_array = array(
            'date_update'  => date('Y-m-d H:i:s'),
            'user_update'  => $this->userlogin,
        );

        if($this->fildstatus){
            $data_array[$this->fildstatus]  = 0;
        }

        if ($item_remark) {
            $data_array['remark_delete'] = $item_remark;
        }

        $this->db->update($this->table, $data_array, array('id' => $item_id));

        // keep log
        log_data(array('delete ' . $this->table, 'update', $this->db->last_query()));

        $result = array(
            'error'     => 0,
            'txt'       => 'ลบการสำเร็จ'
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
     * @param string $type
     * @return void
     */
    function get_sql(int $id = null, array $optionnal = null, string $type = 'result')
    {
        $request = $_REQUEST;

        $hidden_start = "";
        $hidden_end = "";

        $sql = $this->db->from($this->table);

        if (textNull($request['hidden_datestart'])) {
            $hidden_start = textNull($request['hidden_datestart']);
        }
        if (textNull($request['hidden_dateend'])) {
            $hidden_end = textNull($request['hidden_dateend']);
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

        if ($type != "row") {
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
