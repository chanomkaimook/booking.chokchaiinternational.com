<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mdl_roles extends CI_Model

{
    private $table = "roles";
    private $fildstatus = "status_offview";

    private $roles_control = "roles_control";

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
    //  Function
    //  =========================
    //  =========================
    function update_roles_control(array $data = null, int $roles_id = null, string $type = 'permit_id')
    {
        if ($roles_id && $data) {
            $data_permit = [];
            $list_permit = $data;

            if ($type != "permit_id") {
                foreach ($list_permit as $value) {
                    $data_permit[] = array(
                        'roles_id'  => $roles_id,
                        'roles_id_child'  => $value,
                        'user_starts'  => $this->userlogin,
                    );
                }
            } else {
                foreach ($list_permit as $value) {
                    $data_permit[] = array(
                        'roles_id'  => $roles_id,
                        'permit_id'  => $value,
                        'user_starts'  => $this->userlogin,
                    );
                }
            }

            if (count($data_permit)) {
                $this->db->insert_batch($this->roles_control, $data_permit);

                // keep log
                log_data(array('insert' . $this->roles_control, 'insert', $this->db->last_query()));
            }
        }

        return true;
    }

    //  =========================
    //  =========================
    //  End Function
    //  =========================
    //  =========================

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
        $sql->where($this->table . '.' . $this->fildstatus, null);

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
        $sql->where($this->table . '.' . $this->fildstatus, null);
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
     * @param integer|null $item_id_noncheck = id to not check
     * @return void
     */
    function check_value_valid($arrayset, array $array_to_find = null, int $item_id_noncheck = null)
    {

        $result = false;

        if ($array_to_find) {
            $array_text_error = $array_to_find;
        } else {
            $array_text_error = array(
                'roles_name_th'  => 'ชื่อ',
                'roles_code'      => 'code',
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

        $array_checkdup['where'] = array(
            'code' => textNull($arrayset['roles_code'])
        );
        if ($item_id_noncheck) {
            $array_checkdup['where']['roles.id !='] = textNull($item_id_noncheck);
        }
        if($this->get_dataShow(null,$array_checkdup)){
            $result = array(
                'error' => 1,
                'txt'   => 'ค่าที่ระบุใน code มีการใช้แล้ว',
            );
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

        if ($this->input->post()) {
            $data = array(
                'code'  => textNull($this->input->post('roles_code')),

                'name'  => textNull($this->input->post('roles_name_th')),
                'name_us'  => textNull($this->input->post('roles_name_us')),
                'description'  => textNull($this->input->post('roles_descrip_th')),
                'description_us'  => textNull($this->input->post('roles_descrip_us')),

                'user_starts'  => $this->userlogin,
            );

            $this->db->insert($this->table, $data);
            $new_id = $this->db->insert_id();

            // keep log
            log_data(array('insert' . $this->table, 'insert', $this->db->last_query()));

            // 
            // if find variable permit_id
            $this->update_roles_control($this->input->post('permit_id'), $new_id, 'permit_id');

            // 
            // if find variable role child
            $this->update_roles_control($this->input->post('roles_child'), $new_id, 'roles_id_child');

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
        if ($return = $this->check_value_valid($request, null, $item_id)) {
            return $return;
        }

        $data = array(
            'code'  => textNull($this->input->post('roles_code')),

            'name'  => textNull($this->input->post('roles_name_th')),
            'name_us'  => textNull($this->input->post('roles_name_us')),
            'description'  => textNull($this->input->post('roles_descrip_th')),
            'description_us'  => textNull($this->input->post('roles_descrip_us')),

            'date_update'  => date('Y-m-d H:i:s'),
            'user_update'  => $this->userlogin,
        );

        $this->db->where('id', $item_id);
        $this->db->update($this->table, $data);

        // keep log
        log_data(array('update ' . $this->table, 'update', $this->db->last_query()));

        // 
        // if find variable permit_id
        // roles_control update

        // roles_control will delete permit before update
        $this->db->delete($this->roles_control, array('roles_id' => $item_id));

        // keep log
        log_data(array('delete' . $this->roles_control, 'delete', $this->db->last_query()));

        // roles_control update
        $this->update_roles_control($this->input->post('permit_id'), $item_id);

        // 
        // if find variable role child
        $this->update_roles_control($this->input->post('roles_child'), $item_id, 'roles_id_child');

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
            $this->fildstatus     => 1,

            'date_update'  => date('Y-m-d H:i:s'),
            'user_update'  => $this->userlogin,
        );

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

        $sql = $this->db->from($this->table)
            ->where($this->table . '.id >=', 1);

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
