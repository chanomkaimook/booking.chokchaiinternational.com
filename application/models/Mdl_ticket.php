<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mdl_ticket extends CI_Model

{
    private $table = "ticket";

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
        $sql = $this->get_sql($id, $optionnal);

        $query = $sql->get();

        if ($id) {
            $query->row();
        } else {
            $query->result();
        }

        return $query->result();
    }

    #
    # data to show
    public function get_dataShow(int $id = null, array $optionnal = [], string $return = 'result')
    {
        # code...
        $sql = $this->get_sql($id, $optionnal);
        $sql->where('ticket.status', 1);

        // $c = $sql->count_all_results(null,false);

        $query = $sql->get();

        if ($id) {
            return $query->row();
        } else {

            if ($return != 'result') {
                return $query->row();
            } else {
                return $query->result();
            }
        }
    }

    #
    # data to show on your work
    public function get_dataShowWork(int $id = null, array $optionnal = [])
    {
        # code...
        $myself = $this->session->userdata('user_code');

        # 
        # sql provide for helpdesk permit
        $text_sql = '';
        if(check_helpdesk()){
            $this->load->model('mdl_role_focus');

            $option['where'] = array(
                'staff_owner'  => $this->session->userdata('user_code'),
            );
            $result_helpdesk = $this->mdl_role_focus->get_data(null,$option);
            
            if($result_helpdesk){
                $array_in = [];
                
                foreach($result_helpdesk as $row){
                    $array_in[] = $row->STAFF_CHILD;
                }
// print_r($result_helpdesk);
                if(count($array_in)){
                    $array_in[] = $myself;
                    $text_in = implode(',',$array_in);
                    $text_sql = 'ticket_assign.staff_id in('.$text_in.')';
                }
            }

            // echo $text_sql."---";
        }

        $sql = $this->get_sql($id, $optionnal);
        $sql->where('ticket.status', 1);

        // check role level
        if (check_operator()) {
            $sql->where('ticket_assign.staff_id=' . $myself);
        }

        if(check_helpdesk() && textShow($text_sql)){
           $sql->where($text_sql);
        }

        $query = $sql->get();
        
        if ($id) {
            return $query->row();
        } else {
            return $query->result();
        }
    }

    #
    # count data to show on your work
    public function count_dataShowWork()
    {
        # code...
        $request = $_REQUEST;
        $optionnal['select'] = 'count('.$this->table.'.id) as total';

        $optionnal['limit'] = 1;

        $row = $this->get_dataShowWork(null,$optionnal);

        $result = 0;
        if($row){
            $result = $row[0]->total;
        }

        return $result;
    }

    #
    # Query
    # set sql on this
    #
    function get_sql(int $id = null, array $optionnal = [])
    {
        $request = $_REQUEST;

        $hidden_start = "";
        $hidden_end = "";

        $workstatus_id = "";

        $sql = $this->db->from($this->table)
            ->join('ticket_assign', 'ticket.id=ticket_assign.ticket_id', 'left');

        if (textShow($request['hidden_datestart'])) {
            $hidden_start = textShow($request['hidden_datestart']);
        }
        if (textShow($request['hidden_dateend'])) {
            $hidden_end = textShow($request['hidden_dateend']);
        }

        if (textShow($request['hidden_statusbill'])) {
            $hidden_statusbill = textShow($request['hidden_statusbill']);
        }
        
        #
        # force enter date start and date end
        # because limit call data from server
        if ($hidden_start && $hidden_end) {
            $sql->where('date(ticket.date_starts) >=', $hidden_start);
            $sql->where('date(ticket.date_starts) <=', $hidden_end);
        }

        if ($hidden_statusbill) {
            $sql->where('ticket.workstatus_id', $hidden_statusbill);
        }

        if (textShow($request['hidden_operator_id'])) {
            $hidden_operator_id = textShow($request['hidden_operator_id']);
            $sql->where('ticket_assign.staff_id', $hidden_operator_id);
        }

        if ($id) {
            $sql->where('ticket.id', $id);
        }

        if ($optionnal['select']) {
            $sql->select($optionnal['select']);
        } else {
            $sql->select(
                $this->table . '.*,' .
                    'ticket_assign.STAFF_ID,'.
                    'ticket_assign.NAME'
            );
        }

        if ($optionnal['where'] && count($optionnal['where'])) {
            foreach ($optionnal['where'] as $column => $value) {
                $sql->where($column, $value);
            }
        }

        if ($optionnal['or_where']) {
            $sql->or_where($optionnal['or_where']);
        }

        if ($optionnal['order_by'] && count($optionnal['order_by'])) {
            foreach ($optionnal['order_by'] as $column => $value) {
                $sql->order_by($column, $value);
            }
        } else {
            $sql->order_by('ticket.id', 'desc');
        }

        if ($optionnal['group_by'] && count($optionnal['group_by'])) {
            $sql->group_by($column, $optionnal['group_by']);
        }

        if ($optionnal['limit']) {
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

    #
    # Insert
    public function insert_data(array $data_array = null)
    {

        $this->db->insert($this->table, $data_array);
        $new_id = $this->db->insert_id();

        // keep log
        log_data(array('insert ticket', 'insert', $this->db->last_query()));

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

    # assign ticket
    public function insert_assign_data(array $data_array = null)
    {

        $this->db->insert('ticket_assign', $data_array);
        $new_id = $this->db->insert_id();

        // keep log
        log_data(array('assign ticket', 'insert', $this->db->last_query()));

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

    #
    # Update
    public function update_data(int $id = null, array $data_array = null)
    {
        $this->db->where('id', $id);
        $this->db->update($this->table, $data_array);

        // keep log
        log_data(array('update ticket', 'update', $this->db->last_query()));

        $result = array(
            'error'     => 0,
            'txt'       => 'ทำรายการสำเร็จ',
            'data'      => array(
                'id'    => $id
            )
        );

        return $result;
    }

    #
    # Delete
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


        $status = status_delete();
        $workstatus_id = $status['id'];
        $workstatus_name = $status['name'];

        $data_array = array(
            'status'      => 0,

            'workstatus_id'      => $workstatus_id,
            'workstatus_name'    => $workstatus_name,

            'date_update'  => date('Y-m-d H:i:s'),
            'user_update'  => $this->session->userdata('user_code'),
        );

        if ($item_remark) {
            $data_array['remark_delete'] = $item_remark;
        }

        $this->db->update($this->table, $data_array, array('id' => $item_id));

        // keep log
        log_data(array('delete ticket', 'update', $this->db->last_query()));

        $result = array(
            'error'     => 0,
            'txt'       => 'ทำรายการสำเร็จ'
        );

        return $result;
    }

    #
    # Delete
    public function delete_defect()
    {
        $this->load->library('ticket');
        $item_id = textShow($this->input->post('item_id'));

        $result = array(
            'error' => 1,
            'txt'        => 'ไม่มีการทำรายการ'
        );

        if (!$item_id) {
            return $result;
        }

        $array = array(
            'id'            => $item_id,
            'defect'        => null,
            'defect_remark' => null
        );
        $this->ticket->update_ticketDefect($array);

        // keep log
        log_data(array('delete defect', 'update', $this->db->last_query()));

        $result = array(
            'error'     => 0,
            'txt'       => 'ทำรายการสำเร็จ'
        );

        return $result;
    }
}
