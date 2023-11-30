<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mdl_permit extends CI_Model

{
    private $table = "permit";
    private $fildstatus = "";

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

        if ($type == "row" || $id) {
            return $query->row();
        } else {
            return $query->result();
        }
    }

    #
    # count data to show all
    public function get_data_all(int $id = null, array $optionnal = null)
    {
        # code...
        $optionnal['select'] = 'count(' . $this->table . '.id) as total';

        $data = (object) $this->get_data($id, $optionnal,'row');
        $num = $data->total;

        return $num;
    }

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
    public function get_dataJoinMenus(int $id = null, array $optionnal = null, string $type = "result")
    {
        $optionnal['select'] = $this->table.".*,
        menus.name as MENUS_NAME,
        menus.name_us as MENUS_NAME_US
        ";

        $optionnal['order_by'] = array(
            'menus.sort' => 'asc',
            $this->table.'.sort' => 'asc',
        );
        $sql = (object) $this->get_sql($id, $optionnal);
        $sql->join('menus',$this->table.'.menus_id=menus.id','left');
        $query = $sql->get();

        if ($id) {
            return $query->row();
        } else {
            return $query->$type();
        }
    }

    /**
     * permit data
     *
     * @param integer|array $id  = permit_id
     * @param array|null $optionnal
     * @param string $type
     * @return void
     */
    public function get_dataPermit($permit_id = null, array $optionnal = null, string $type = "result")
    {
        # code...
        $menus = $this->menu;

        if (!$optionnal['select']) {
            $optionnal['select'] = $this->table.".*,
            " . $this->table . ".id as PERMIT_ID,
            " . $this->table . ".code as CODE,
            " . $this->table . ".name as NAME,
            " . $this->table . ".name_us as NAME_US,
            " . $menus . ".name as MENUS_NAME,
            " . $menus . ".name_us as MENUS_NAME_US";
        }

        if(is_array($permit_id)){
            $permit_set = implode(",", $permit_id);
            $optionnal['where'][$this->table . '.id in('.$permit_set.')'] = null;
        }else{
            $optionnal['where'][$this->table . '.id'] = $permit_id;
        }

        $optionnal['order_by'] = array(
            $menus . '.sort' => 'asc',
            $this->table . '.sort' => 'asc',
        );

        $sql = (object) $this->get_sql(null, $optionnal, $type);
        $sql->join($menus, $menus . '.id=' . $this->table . '.menus_id', 'left');
        $query = $sql->get();

        return $query->$type();
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

        $sql = $this->db->from($this->table);

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
