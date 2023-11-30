<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mdl_role_focus extends CI_Model

{
    private $table = "roles_focus";

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
     * @return void
     */
    public function get_data(int $id = null, array $optionnal = [])
    {
        $sql = $this->db->select('*');
        if ($id) {
            $sql->where('id', $id);
        }else{
            if($this->input->get('id')){
                $sql->where('staff_owner', $this->input->get('id'));
            }
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
        }else{
            $sql->order_by('id','asc');
        }

        if ($optionnal['group_by'] && count($optionnal['group_by'])) {
            $sql->group_by($column, $optionnal['group_by']);
        }

        if ($optionnal['limit'] && count($optionnal['limit'])) {
            $sql->limit($optionnal['limit']);
        }

        $query = $sql->get($this->table);

        if($id){
            $query->row(); 
        }else{
            $query->result();
        }

        return $query->result();
    }

}
