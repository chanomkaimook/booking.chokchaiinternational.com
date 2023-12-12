<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bill
{

    private $tb;
    private $tbd;

    public function __construct()
    {
        //=	 call database	=//
        $this->ci = &get_instance();
        $this->ci->load->database();
        //===================//

        $this->ci->load->helper('My_status');
        $this->ci->load->model(array('information/mdl_customer', 'information/mdl_round'));

        $this->tb = 'bill';
        $this->tbd = 'bill_detail';
    }

    public function gen_code()
    {
        # code...
        $yearindent = date('y');
        $year = date('Y');
        $month = date('m');

        $sql = $this->ci->db->from($this->tb)
            ->where('year(' . $this->tb . '.date_starts)', $year)
            ->where('month(' . $this->tb . '.date_starts)', $month);
        $query = $sql->get();
        $num = $query->num_rows();

        if ($num) {
            $numnext = (int) $num + 1;
            $numpad = str_pad($numnext, 4, '0', STR_PAD_LEFT);
            $code = $yearindent . "" . $month . "" . $numpad;
        } else {
            $numpad = "0001";
            $code = $yearindent . "" . $month . "" . $numpad;
        }

        $result = $code;

        return $result;
    }

    /**
     * create bill
     *
     * @return array
     */
    public function insert_item()
    {
        /* [frm_hidden_id] => 
    [customer] =>         
    [agent_name] => aaaa
    [agent_tel] => 080
    [round] => 1
    [bookingdate] => 2023-12-10
    [price] => 1500
    [item_net] => 120
    [item_list] => [{"item_id":"2","item_name":"บัตรนั่งรถฟาร์ม ผู้ใหญ่","item_price":"120.00","item_qty":"1"}]
    [item_qty] => 1
    [remark] => remark
    )
    Array
    (
        [0] => stdClass Object
            (
                [item_id] => 2
                [item_name] => บัตรนั่งรถฟาร์ม ผู้ใหญ่
                [item_price] => 120.00
                [item_qty] => 1
            )

    ) */
        $request = $_REQUEST;

        $customer = $request['customer'] ? textNull($request['customer']) : null;
        $customer_id = $request['customer_id'] ? textNull($request['customer_id']) : null;
        $agent_name = $request['agent_name'] ? textNull($request['agent_name']) : null;
        $agent_contact = $request['agent_contact'] ? textNull($request['agent_contact']) : null;
        $round_id = $request['round'] ? textNull($request['round']) : null;
        $bookingdate = $request['bookingdate'] ? textNull($request['bookingdate']) : null;
        $deposit = $request['deposit'] ? textNull($request['deposit']) : null;
        $item_net = $request['item_net'] ? textNull($request['item_net']) : null;
        $remark = $request['remark'] ? textNull($request['remark']) : null;

        $item_list = $request['item_list'] ? $request['item_list'] : null;

        $result = array(
            'error' => 1,
            'txt'        => 'ไม่มีการทำรายการ'
        );

        if (!$customer) {
            $result = array(
                'error' => 1,
                'txt'        => 'ไม่พบชื่อลูกค้า'
            );
            return $result;
        } else {
            if (!$customer_id) {
                $data_insert_customer = array(
                    'name'          => $customer,
                );
                $result_customer = $this->ci->mdl_customer->insert_data($data_insert_customer);
                if ($result_customer['error'] != 0) {
                    $optional['where'] = $data_insert_customer;
                    $r_customer = $this->ci->mdl_customer->get_data(null, $optional, 'row');

                    $customer_id = $r_customer->ID;
                } else {
                    if ($result_customer['data']['id']) {
                        $customer_id = $result_customer['data']['id'];
                    }
                }
            }
        }

        $code = $this->gen_code();

        //
        // array data to insert
        $data_insert = array(
            'code'  => $code,
            'customer_id'  => $customer_id,
            'customer_name'  => $customer,
            'agent_name'  => $agent_name,
            'agent_contact'  => $agent_contact,
            'booking_date'  => $bookingdate,

            'deposit'  => $deposit,

            'remark'  => $remark,
        );

        //
        // bill NET
        //
        if (!$item_net) {
            $data_insert['net'] = $item_net;
        } else {
            if (is_string($item_list)) {
                $item_list = json_decode($request['item_list']);
            }
            if ($item_list && is_array($item_list)) {
                $totalprice = 0.00;
                $item_net = 0.00;
                foreach ($item_list as $key => $item) {
                    $totalprice = $item->item_price * $item->item_qty;
                    $item_net = $item_net + $totalprice;
                }
            }

            $data_insert['net'] = $item_net;
        }

        //
        // bill Round
        //
        if ($round_id) {
            if ($row_round = $this->ci->mdl_round->get_data($round_id)) {
                $round_name = $row_round->NAME;
                $round_start = $row_round->TIME_START;
                $round_end = $row_round->TIME_END;

                if ($row_round->NAME) {
                    $round_name = $row_round->NAME;
                    $data_insert['round_name'] = $round_name;
                }
                if ($row_round->TIME_START) {
                    $round_start = $row_round->TIME_START;
                    $data_insert['round_start'] = $round_start;
                }
                if ($row_round->TIME_END) {
                    $round_end = $row_round->TIME_END;
                    $data_insert['round_end'] = $round_end;
                }
            }
        }

        $data_insert['complete_id'] = 1;
        $data_insert['complete_alias'] = complete('pending');
        $data_insert['payment_alias'] = 6;
        $data_insert['payment_alias'] = payment('pending');
        // 
        // status bill 
        // 
        // @param int|float $deposit = deposit
        // @param int|float $item_net = net
        if ($r_status = $this->get_status_bill($deposit, $item_net)) {
            $data_insert['payment_id'] = $r_status['payment_id'];
            $data_insert['payment_alias'] = $r_status['payment_status'];
            $data_insert['complete_id'] = $r_status['complete_id'];
            $data_insert['complete_alias'] = $r_status['complete_status'];
        }

        print_r($data_insert);
        die;
        $this->ci->db->insert('bill', $data_insert);

        echo $code;
    }

    /**
     * get status on bill
     *
     * @param [type] $deposit = deposit
     * @param [type] $item_net = bill net
     * @return void
     */
    function get_status_bill($deposit, $item_net)
    {
        $result = null;

        $payment_status = "";
        $complete_status = "";

        $deposit = textNull($deposit) ? number_format($deposit, 2) : null;
        $item_net = textNull($item_net) ? number_format($item_net, 2) : null;

        // โอนเงินและมียอดรวมบิล
        if ($deposit && $item_net) {

            // โอนเงิน มากกว่าเท่ากับ ยอดรวมบิล
            if ($deposit >= $item_net) {
                $payment_status = payment('success');
                $complete_status = complete('success');
                $result = array(
                    'payment_id' => 8,
                    'payment_status' => $payment_status,
                    'complete_id' => 3,
                    'complete_status' => $complete_status,
                );
            }
            // โอนเงิน น้อยกว่า ยอดรวมบิล
            else {
                $payment_status = payment('deposit');
                $complete_status = complete('checking');
                $result = array(
                    'payment_id' => 7,
                    'payment_status' => $payment_status,
                    'complete_id' => 2,
                    'complete_status' => $complete_status,
                );
            }
        }

        // โอนเงิน แต่ไม่มียอดรวมบิล
        if ($deposit && !$item_net) {
            $payment_status = payment('pending');
            $complete_status = complete('checking');
            $result = array(
                'payment_id' => 6,
                'payment_status' => $payment_status,
                'complete_id' => 2,
                'complete_status' => $complete_status,
            );
        }

        // ไม่โอนเงิน แต่มียอดรวมบิล
        if (!$deposit && $item_net) {
            $payment_status = payment('pending');
            $complete_status = complete('pending');
            $result = array(
                'payment_id' => 6,
                'payment_status' => $payment_status,
                'complete_id' => 1,
                'complete_status' => $complete_status,
            );
        }

        return $result;
    }
}
