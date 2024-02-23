<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mdl_page extends CI_Model

{
    private $table = "blank";
    private $offview = "status_offview";
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
        if ($this->fildstatus) {
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
    //  * CRUD
    //  * read
    //  * 
    //  * get data only for display (not data delete and not data hide)
    //  *
    public function get_dataDisplay(int $id = null, array $optionnal = null, string $type = "result")
    {
        # code...
        $sql = (object) $this->get_sql($id, $optionnal, $type);

        if ($this->offview) {
            $sql->where($this->table . '.' . $this->offview . ' is null', null, false);
        }

        if ($this->fildstatus) {
            $sql->where($this->table . '.' . $this->fildstatus, 1);
        }

        $query = $sql->get();

        if ($id) {
            return $query->row();
        } else {
            return $query->$type();
        }
    }

    public function get_dataShowForEdit(int $id = null, array $optionnal = null, string $type = "result")
    {
        # code...
        $sql = (object) $this->get_sql($id, $optionnal, $type);
        if ($this->fildstatus) {
            $sql->where($this->table . '.' . $this->fildstatus, 1);
        }
        $sql->where($this->table . '.noedit', null);

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
                'name'       => 'ชื่อ',
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
    public function insert_data($data_insert = null)
    {

        $result = array(
            'error'     => 1,
            'txt'       => 'ไม่มีการทำรายการ',
        );

        $request = $_POST;

        $item_code = textNull($data_insert['code']) ? $data_insert['code'] : $request['code'];
        $item_name = textNull($data_insert['name']) ? $data_insert['name'] : $request['name'];
        $array_chk_dup = array(
            'code' => $item_code,
            'name' => $item_name,
            'status' => 1
        );

        if ($return = $this->check_value_valid($array_chk_dup)) {
            return $return;
        }

        $array_chk_dup1 = array(
            'code' => $item_code,
            'status' => 1
        );
        if ($return = $this->check_dup($array_chk_dup1, $item_code)) {
            return $return;
        }

        $array_chk_dup2 = array(
            'name' => $item_name,
            'status' => 1
        );
        if ($return = $this->check_dup($array_chk_dup2, $item_name)) {
            return $return;
        }

        $new_id = "";

        if ($data_insert && is_array($data_insert)) {
            $data_insert['user_starts'] = $this->userlogin;
            $this->db->insert($this->table, $data_insert);
            $new_id = $this->db->insert_id();
        }

        if ($new_id) {

            // keep log
            log_data(array('insert ' . $this->table, 'insert', $this->db->last_query()));

            $result = array(
                'error'     => 0,
                'txt'       => 'ทำรายการสำเร็จ',
                'data'      => array(
                    'id'    => $new_id
                )
            );
        }

        return $result;
    }

    //  *
    //  * CRUD
    //  * update
    //  * 
    //  * update data
    //  *
    public function update_data($data_update = null, int $id = null)
    {
        $result = false;
        $item_id = $id ? $id : textNull($this->input->post('item_id'));

        if ($item_id) {
            $request = $_POST;

            $item_code = textNull($data_update['code']) ? $data_update['code'] : $request['code'];
            $item_name = textNull($data_update['name']) ? $data_update['name'] : $request['name'];
            $array_chk_dup = array(
                'name' => $item_name,
                'status' => 1,
                'id !=' => $item_id,
            );

            if ($return = $this->check_value_valid($array_chk_dup)) {
                return $return;
            }

            if ($return = $this->check_dup($array_chk_dup, $item_name)) {
                return $return;
            }

            $array_chk_dupcode = array(
                'code' => $item_code,
                'status' => 1,
                'id !=' => $item_id,
            );
            if ($return = $this->check_dup($array_chk_dupcode, $item_name)) {
                return $return;
            }

            if ($data_update && is_array($data_update)) {
                $data_update['date_update'] = date('Y-m-d H:i:s');
                $data_update['user_update'] = $this->userlogin;
                $this->db->where('id', $item_id);
                $this->db->update($this->table, $data_update);

                // keep log
                log_data(array('update ' . $this->table, 'update', $this->db->last_query()));
            }

            $result = array(
                'error'     => 0,
                'txt'       => 'ทำรายการสำเร็จ',
                'data'      => array(
                    'id'    => $item_id
                )
            );
        }
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

        if ($this->fildstatus) {
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
            'txt'       => 'การลบสำเร็จ'
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
            if ($hidden_datetype = textNull($request['hidden_datetype'])) {
                if ($hidden_datetype == 'date_update') {
                    // $sql->where('if(date(' . $this->table . '.'.$hidden_datetype.'),date(' . $this->table . '.'.$hidden_datetype.') >='.$hidden_start.',date(' . $this->table . '.date_starts'.') >='.$hidden_start.')',null,false);
                    $sql->where('(CASE WHEN date(' . $this->table . '.' . $hidden_datetype . ') is not null THEN date(' . $this->table . '.' . $hidden_datetype . ') >="' . $hidden_start . '" ELSE date(' . $this->table . '.date_starts' . ') >="' . $hidden_start . '" END)', null, false);
                    $sql->where('(CASE WHEN date(' . $this->table . '.' . $hidden_datetype . ') is not null THEN date(' . $this->table . '.' . $hidden_datetype . ') <="' . $hidden_end . '" ELSE date(' . $this->table . '.date_starts' . ') <="' . $hidden_end . '" END)', null, false);
                } else {
                    $sql->where('date(' . $this->table . '.' . $hidden_datetype . ') >=', $hidden_start);
                    $sql->where('date(' . $this->table . '.' . $hidden_datetype . ') <=', $hidden_end);
                }
            } else {
                $sql->where('date(' . $this->table . '.date_starts) >=', $hidden_start);
                $sql->where('date(' . $this->table . '.date_starts) <=', $hidden_end);
            }
        }

        if ($id) {
            $sql->where($this->table . '.id', $id);
        }

        if ($optionnal['select']) {
            $sql->select($optionnal['select']);
        } else {
            $sql->select($this->table . '.*');
        }

        if ($optionnal['where'] && count($optionnal['where'])) {
            foreach ($optionnal['where'] as $column => $value) {
                $sql->where($this->table . '.' . $column, $value);
            }
        }

        if ($request['search']['value']) {
            $search = $request['search']['value'];
            $sql->where('('
                . $this->table . '.code like "%' . $search . '%"
                or ' . $this->table . '.name like "%' . $search . '%"
                or ' . $this->table . '.name_us like "%' . $search . '%"
            )');
        }

        if ($optionnal['order_by'] && count($optionnal['order_by'])) {
            foreach ($optionnal['order_by'] as $column => $value) {
                $sql->order_by($this->table . '.' . $column, $value);
            }
        } else {
            $item_column = "";
            if (is_numeric($request['order'][0]['column']) && $request['item_name']) {
                if ($request['item_name'][$request['order'][0]['column']]) {

                    $item_column = $request['item_name'][$request['order'][0]['column']];
                }
            }
            if ($request['order'][0]['dir'] && $item_column) {
                // for value_active
                $next = 1;
                if ($item_column == "user_active") {
                    $sql->join('staff', $this->table . '.user_starts=staff.id', 'left');
                    $sql->join('employee', 'staff.employee_id=employee.id', 'left');

                    if ($_COOKIE['langadmin'] == 'thai') {
                        $item_column = "name";
                    } else {
                        $item_column = "name_us";
                    }

                    $sql->order_by(
                        'CASE WHEN employee.' . $item_column . ' is not null
                        THEN employee.' . $item_column . '
                        ELSE employee.name
                        END ' . $request['order'][0]['dir'],
                        null,
                        false
                    );

                    $next = 0;
                }
                if ($item_column == "date_active") {
                    $sql->order_by(
                        'CASE WHEN ' . $this->table . '.date_update is not null
                        THEN ' . $this->table . '.date_update
                        ELSE ' . $this->table . '.date_starts
                        END ' . $request['order'][0]['dir'],
                        null,
                        false
                    );
                    $next = 0;
                }
                if($next == 1){
                    $sql->order_by($this->table . '.'.$item_column, $request['order'][0]['dir']);
                }
            } else {
                $sql->order_by($this->table . '.id', 'desc');
            }
        }

        if ($optionnal['group_by'] && count($optionnal['group_by'])) {
            foreach ($optionnal['group_by'] as $column) {
                $sql->group_by($this->table . '.' . $column);
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
