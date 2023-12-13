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

        $this->ci->load->helper(array('My_status','My_calculate'));
        $this->ci->load->library('Promotion');
        $this->ci->load->model(
            array(
                'information/mdl_customer', 
                'information/mdl_round',
                'mdl_settings',
                'bill/mdl_bill'
            )
        );

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
     * get data from cart
     *
     * POST
     * @param int pay = deposit  
     * @param array|string item_data = [id,price,total]  
     * @return array
     */
    public function get_cartData()
    {
        $result = array();
        $request = $_REQUEST;

        if ($request['item_data'] && is_string($request['item_data'])) {

            $item_data = json_decode($request['item_data']);

            $unit = 0;
            $price = 0.00;
            $discount = 0.00;
            $net = 0.00;
            $pay = 0.00;
            $promotion = [];

            if (count($item_data)) {
                foreach ($item_data as $key => $value) {
                    $unit = $unit + $value->total;

                    $price_item = 0.00;
                    $discount_item = 0.00;

                    $price_item = $value->price * $value->total;
                    $price = $price + $price_item;

                    $discount_detail = $this->ci->promotion->get_itemPromotion($value->id, $value);
                    if ($discount_detail && $discount_detail['discount']) {
                        if ($discount_detail['type'] == 'q') {
                            $discount_item = $discount_detail['discount'] * $value->total;
                        } else {
                            $discount_item = $discount_detail['discount'];
                        }

                        $discount = $discount + $discount_item;

                        $total_item_unit = $value->total;
                        $total_item_discount = 0.00;

                        $t = array_keys(array_column($promotion,'ID'),$discount_detail['promotion']->ID);

                        if($t){
                            foreach($t as $key => $value){
                                $total_item_discount = $total_item_discount + $promotion[$value]->TOTAL_DISCOUNT;
                                $total_item_unit = $total_item_unit + $promotion[$value]->TOTAL_UNIT;
                                $promotion[$value] = (object)array('ID'=>null);
                            }
                        }
                        $discount_detail['promotion']->TOTAL_UNIT = $total_item_unit;
                        $discount_detail['promotion']->TOTAL_DISCOUNT = $discount_item + $total_item_discount;
                        $promotion[] = $discount_detail['promotion'];
                        // array_push($promotion,$discount_detail['promotion']);
                    }
                }

                $net = $price - $discount;
            }

            if ($request['pay']) {
                $pay = $request['pay'];
            }

            // calculate VAT
            $q_vat = $this->ci->mdl_settings->get_vatNum();
            if(!$q_vat){
                $vatnum = $this->ci->config->item('vat_num');
            }else{
                $vatnum = $q_vat->VAT_NUM;
            }

            $price_withvat = get_priceVat($net,$vatnum);
            if($price_withvat){
                $result['vat'] = $price_withvat['vat'];
                $result['vatnum'] = $price_withvat['vat_num'];
                $result['price_novat'] = $price_withvat['before_vat'];
            }else{
                $result['vat'] = null;
                $result['vatnum'] = null;
                $result['price_novat'] = null;
            }
            

            $result['discount'] = $discount;
            $result['price'] = $price;
            $result['net'] = $net;
            $result['pay'] = $pay;
            $result['unit'] = textNull($unit);

            $r_status = $this->get_status_bill($pay, $net);
            $result['payment_id'] = $r_status['payment_id'];
            $result['payment_status'] = $r_status['payment_status'];
            $result['complete_id'] = $r_status['complete_id'];
            $result['complete_status'] = $r_status['complete_status'];
            $result['promotion'] = $promotion;
        }

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

            // 
            // add customer auto
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

            'price'     => null,
            'discount'  => null,
            'deposit'   => null,
            'net'       => null,

            'remark'  => $remark,
        );

        $data_insert['complete_id'] = 1;
        $data_insert['complete_alias'] = complete('pending');
        $data_insert['payment_id'] = 6;
        $data_insert['payment_alias'] = payment('pending');

        //
        // detail price bill
        //
        $detail_bill = $this->get_cartData();

        if ($detail_bill) {
            if ($detail_bill['payment_status']) {
                $data_insert['payment_id'] = $detail_bill['payment_id'];
                $data_insert['payment_alias'] = $detail_bill['payment_status'];
            }
            if ($detail_bill['complete_status']) {
                $data_insert['complete_id'] = $detail_bill['complete_id'];
                $data_insert['complete_alias'] = $detail_bill['complete_status'];
            }

            if ($detail_bill['price']) {
                $data_insert['price'] = $detail_bill['price'];
            }

            if ($detail_bill['discount']) {
                $data_insert['discount'] = $detail_bill['discount'];
            }

            if ($detail_bill['pay']) {
                $data_insert['deposit'] = $detail_bill['pay'];
            }

            if ($detail_bill['net']) {
                $data_insert['net'] = $detail_bill['net'];
            }

            if ($detail_bill['price_novat']) {
                $data_insert['price_novat'] = $detail_bill['price_novat'];
            }

            if ($detail_bill['vat']) {
                $data_insert['vat'] = $detail_bill['vat'];
            }

            if ($detail_bill['vatnum']) {
                $data_insert['vatnum'] = $detail_bill['vatnum'];
            }

            if ($detail_bill['unit']) {
                $data_insert['total_unit'] = $detail_bill['unit'];
            }
        }
        //
        // bill Round
        //
        if ($round_id) {
            if ($row_round = $this->ci->mdl_round->get_data($round_id)) {

                $data_insert['round_id'] = $round_id;

                $round_name = $row_round->NAME;
                $time_start = $row_round->TIME_START;
                $time_end = $row_round->TIME_END;

                if ($row_round->NAME) {
                    $round_name = $row_round->NAME;
                    $data_insert['round_name'] = $round_name;
                }
                if ($row_round->TIME_START) {
                    $time_start = $row_round->TIME_START;
                    $data_insert['time_start'] = $time_start;
                }
                if ($row_round->TIME_END) {
                    $time_end = $row_round->TIME_END;
                    $data_insert['time_end'] = $time_end;
                }
            }
        }

        $this->ci->db->trans_begin();

        $this->ci->mdl_bill->insert_data($data_insert);

        // insert bill_detail

        if ($this->ci->db->trans_status() === FALSE) {
            $this->ci->db->trans_rollback();
        } else {
            $this->ci->db->trans_commit();
        }
        die;
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

        $deposit = textNull($deposit) ? (float) $deposit : null;
        $item_net = textNull($item_net) ? (float) $item_net : null;

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
