<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mdl_ticketcomment extends CI_Model

{
    private $table = "ticket_comment";

    public function __construct()
    {
        parent::__construct();
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
     * @param String|result $result = get data result or data row
     * @return void
     */
    public function get_data(int $id = null, array $optionnal = [],string $result = 'result')
    {
        $sql = $this->get_sql($id, $optionnal);

        $query = $sql->get();

        if ($id) {
            $result = $query->row();
        } else {
            if($result == 'result'){
                $result = $query->result();
            }else{
                $result = $query->row();
            }
        }

        return $result;
    }

    #
    # Insert
    public function insert_data(array $data_array = null)
    {

        $this->db->insert($this->table, $data_array);
        $new_id = $this->db->insert_id();

        // keep log
        log_data(array('insert '.$this->table, 'insert', $this->db->last_query()));

        if ($new_id) {

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

    public function get_sql(int $id = null, array $optionnal = [])
    {
        $request = $_REQUEST;

        $sql = $this->db->from($this->table)
        ->join('ticket',$this->table.'.ticket_id=ticket.id','left');
        if ($id) {
            $sql->where($this->table.'.id', $id);
        }

        if ($optionnal['select']) {
            $sql->select($optionnal['select']);
        } else {
            $sql->select($this->table.'.*');
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
            $sql->order_by($this->table.'.id', 'asc');
        }

        if ($optionnal['group_by'] && count($optionnal['group_by'])) {
            $sql->group_by($column, $optionnal['group_by']);
        }

        if ($optionnal['limit'] && count($optionnal['limit'])) {
            $sql->limit($optionnal['limit']);
        } else {
            if (isset($request['start']) && isset($request['length'])) {
                $sql->limit($request['length'], $request['start']);
            } else {
                // $sql->limit(10, 0);
            }
        }

        return $sql;
    }
}
