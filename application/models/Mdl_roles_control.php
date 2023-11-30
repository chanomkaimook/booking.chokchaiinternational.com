<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mdl_roles_control extends CI_Model

{
    private $table = "roles_control";
    private $fildstatus = "status_offview";

    private $roles = "roles";
    private $permit = "permit";
    private $menu = "menus";

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
        $sql->where($this->table . '.' . $this->fildstatus, null);

        $query = $sql->get();

        if ($id) {
            return $query->row();
        } else {
            return $query->$type();
        }
    }

    /**
     * permit data from roles_control
     *
     * @param integer|array $id  = roles_id
     * @param array|null $optionnal
     * @param string $type
     * @return void
     */
    public function get_dataRoles($roles_id = null, array $optionnal = null, string $type = "result")
    {
        # code...
        $roles = $this->roles;
        $permit = $this->permit;
        $menus = $this->menu;

        if (!$optionnal['select']) {
            $optionnal['select'] = "*,
            " . $roles . ".id as ROLES_ID,
            " . $roles . ".code as ROLES_CODE,
            " . $permit . ".code as CODE,
            " . $permit . ".name as NAME,
            " . $permit . ".name_us as NAME_US,
            " . $menus . ".name as MENUS_NAME,
            " . $menus . ".name_us as MENUS_NAME_US";
        }

        if(is_array($roles_id)){
            $roles_id = implode(",", $roles_id);
            $optionnal['where'][$this->table . '.roles_id in('.$roles_id.')'] = null;
        }else{
            $optionnal['where'][$this->table . '.roles_id'] = $roles_id;
        }
        
        $optionnal['where'][$this->table . '.roles_id_child is null'] = null;

        $optionnal['group_by'] = array(
            $this->table.'.permit_id'
        );
        $optionnal['order_by'] = array(
            $menus . '.sort' => 'asc',
            $permit . '.sort' => 'asc',
        );

        $sql = (object) $this->get_sql(null, $optionnal, $type);
        $sql->join($roles, $roles . '.id=' . $this->table . '.roles_id', 'left')
            ->join($permit, $permit . '.id=' . $this->table . '.permit_id', 'left')
            ->join($menus, $menus . '.id=' . $permit . '.menus_id', 'left')
            ->where($this->table . '.' . $this->fildstatus, null);
        $query = $sql->get();

        return $query->$type();
    }

    /**
     * roles data roles_control
     *
     * @param integer|array $id  = roles_id owner
     * @param array|null $optionnal
     * @param string $type
     * @return void
     */
    public function get_dataRolesOnly($roles_id = null, array $optionnal = null, string $type = "result")
    {
        # code...
        $roles = $this->roles;

        if (!$optionnal['select']) {
            $optionnal['select'] = $this->table . ".*,
            " . $roles . ".code as ROLES_CODE";
        }

        if(is_array($roles_id)){
            $roles_id = implode(",", $roles_id);
            $optionnal['where'][$this->table . '.roles_id in('.$roles_id.')'] = null;
        }else{
            $optionnal['where'][$this->table . '.roles_id'] = $roles_id;
        }
    
        $optionnal['where'][$this->table . '.roles_id_child is null'] = null;
        $sql = (object) $this->get_sql(null, $optionnal, $type);
        $sql->join($roles, $roles . '.id=' . $this->table . '.roles_id', 'left');

        $query = $sql->get();

        return $query->$type();
    }

    /**
     * roles data from role child in roles_control
     *
     * @param integer|array $id  = roles_id owner
     * @param array|null $optionnal
     * @param string $type
     * @return void
     */
    public function get_dataRolesChild($roles_id = null, array $optionnal = null, string $type = "result")
    {
        # code...
        $roles = $this->roles;

        if (!$optionnal['select']) {
            $optionnal['select'] = $this->table . ".*,
            " . $roles . ".code as ROLES_CODE";
        }
        if(is_array($roles_id)){
            $optionnal['where'][$this->table . '.roles_id in('.$roles_id.')'] = null;
        }else{
            $optionnal['where'][$this->table . '.roles_id'] = $roles_id;
        }
    
        $optionnal['where'][$this->table . '.roles_id_child is not null'] = null;
        $sql = (object) $this->get_sql(null, $optionnal, $type);
        $sql->join($roles, $roles . '.id=' . $this->table . '.roles_id_child', 'left');

        $query = $sql->get();

        return $query->$type();
    }

    /**
     * roles data from role child in roles_control
     *
     * @param integer|array $id  = roles_id owner
     * @param array|null $optionnal
     * @param string $type
     * @return void
     */
    public function get_dataRolesChild_permit($roles_id = null, array $optionnal = null, string $type = "result")
    {
        # code...
        $result = [];

        if (!$optionnal['select']) {
            $optionnal['select'] = $this->table . ".roles_id_child";
        }

        if(is_array($roles_id)){
            $roles_id = implode(",", $roles_id);
            $optionnal['where'][$this->table . '.roles_id in('.$roles_id.')'] = null;
        }else{
            $optionnal['where'][$this->table . '.roles_id'] = $roles_id;
        }

        $optionnal['where'][$this->table . '.roles_id_child is not null'] = null;
        $sql = (object) $this->get_sql(null, $optionnal, $type);
        $query = $sql->get();

        if ($query) {
            foreach ($query->result() as $row) {

                $array_id[] = $row->roles_id_child;;
            }

            if ($array_id) {
                $row_id = implode(",", $array_id);
                $query = $this->get_dataPermitFromRole($row_id);
            }

            return $query->$type();
        } else {
            return $result;
        }
    }

    /**
     * get query data from item_id (array || int)
     *
     * @param [type] $item_id
     * @return void
     */
    function get_dataPermitFromRole($item_id)
    {
        $query = "";

        if($item_id){
            $roles = $this->roles;
            $permit = $this->permit;
            $menus = $this->menu;
    
            $optionnals['select'] = "*,
                        " . $roles . ".code as ROLES_CODE,
                        " . $permit . ".code as CODE,
                        " . $permit . ".name as NAME,
                        " . $permit . ".name_us as NAME_US,
                        " . $menus . ".name as MENUS_NAME,
                        " . $menus . ".name_us as MENUS_NAME_US";
    
            $optionnals['where'][$this->table . '.roles_id in (' . $item_id . ')'] = null;
            $optionnals['where'][$this->table . '.roles_id_child is null'] = null;
    
            $optionnals['group_by'] = array(
                $this->table.'.permit_id'
            );
            $optionnals['order_by'] = array(
                $menus . '.sort' => 'asc',
                $permit . '.sort' => 'asc',
            );
    
            $sql = (object) $this->get_sql(null, $optionnals);
            $sql->join($roles, $roles . '.id=' . $this->table . '.roles_id', 'left')
                ->join($permit, $permit . '.id=' . $this->table . '.permit_id', 'left')
                ->join($menus, $menus . '.id=' . $permit . '.menus_id', 'left')
                ->where($this->table . '.' . $this->fildstatus, null);
            $query = $sql->get();
        }
        
        return $query;
    }

    //  *
    //  * CRUD
    //  * insert
    //  * 
    //  * insert data
    //  *

    //  *
    //  * CRUD
    //  * update
    //  * 
    //  * update data
    //  *
    public function update_data()
    {
        $item_id = $this->input->post('item_id');

        /* $begin_date = "";
        if ($this->input->post('item_begin_date')) {
            $ex = explode('-', $this->input->post('item_begin_date'));
            $begin_date = $ex[2] . "-" . $ex[1] . "-" . $ex[0];
        } */

        $data = array(
            'code'  => textShow($this->input->post('label_2')),
            'name'  => textShow($this->input->post('label_6')),
            'workstatus'  => $this->input->post('label_1'),

            'date_update'  => date('Y-m-d H:i:s'),
            'user_update'  => $this->userlogin
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
     * @param string $type
     * @return void
     */
    function get_sql(int $id = null, array $optionnal = null, string $type = 'result')
    {
        $request = $_REQUEST;

        $hidden_start = "";
        $hidden_end = "";

        $sql = $this->db->from($this->table);

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
