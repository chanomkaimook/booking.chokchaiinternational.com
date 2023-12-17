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

        $this->ci->load->helper(array('My_status', 'My_calculate'));
        $this->ci->load->library('Promotion');
        $this->ci->load->model(
            array(
                'information/mdl_customer',
                'information/mdl_round',
                'mdl_settings',
                'bill/mdl_bill',
                'bill/mdl_bill_detail',
                'deposit/mdl_deposit'
            )
        );

        $this->tb = 'bill';
        $this->tbd = 'bill_detail';
    }

    public function gen_code()
    {
        # code...
        $prefix = "CRR";
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
            $code = $prefix . $yearindent . "" . $month . "" . $numpad;
        } else {
            $numpad = "0001";
            $code = $prefix . $yearindent . "" . $month . "" . $numpad;
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
            $item_data_list = [];

            if (count($item_data)) {
                foreach ($item_data as $key => $value) {
                    $name = $value->name;
                    $unit = $unit + $value->total;

                    $price_item = 0.00;
                    $discount_item = 0.00;

                    $price_item = $value->price * $value->total;
                    $price = $price + $price_item;

                    $promotion_id = "";
                    $promotion_name = "";
                    $promotion_discount = "";
                    $discount_detail = $this->ci->promotion->get_itemPromotion($value->id, $value);
                    if ($discount_detail && $discount_detail['discount']) {

                        $promotion_id = $discount_detail['promotion']->ID;
                        $promotion_name = $discount_detail['promotion']->NAME;
                        $promotion_discount = $discount_detail['discount'];

                        if ($discount_detail['type'] == 'q') {
                            $discount_item = $promotion_discount * $value->total;
                        } else {
                            $discount_item = $promotion_discount;
                        }

                        $discount = $discount + $discount_item;

                        $total_item_unit = $value->total;
                        $total_item_discount = 0.00;

                        $t = array_keys(array_column($promotion, 'ID'), $promotion_id);

                        if ($t) {
                            foreach ($t as $key_pro => $value_pro) {
                                //
                                // delete item list for promotion duplicate
                                $p = array_keys(array_column($item_data_list, 'promotion_id'), $promotion_id);
                                foreach ($p as $key_item_pro => $value_item_pro) {
                                    unset($item_data_list[$value_item_pro]);
                                }

                                $total_item_discount = $total_item_discount + $promotion[$value_pro]->TOTAL_DISCOUNT;
                                $total_item_unit = $total_item_unit + $promotion[$value_pro]->TOTAL_UNIT;

                                //
                                // clear promotion duplicate (no delete)
                                $promotion[$value_pro] = (object)array('ID' => null);
                            }
                        }
                        $discount_detail['promotion']->TOTAL_UNIT = $total_item_unit;
                        $discount_detail['promotion']->TOTAL_DISCOUNT = $discount_item + $total_item_discount;
                        $promotion[] = $discount_detail['promotion'];
                    }

                    //
                    // set item list
                    $net_item = $price_item;
                    $item_data_list[] = array(
                        'item_id'       => $value->id,
                        'promotion_id'  => null,
                        'description'   => $name,
                        'price_unit'    => $value->price,
                        'quantity'      => $value->total,
                        'price'         => $price_item,
                        'discount'      => null,
                        'net'           => $net_item,
                        'p_id_use'      => textNull($promotion_id),
                    );
                    // 
                    // 

                    //
                    // add item promotion list
                    if (textNull($discount_detail['promotion']->TOTAL_DISCOUNT)) {

                        $item_data_list[] = array(
                            'item_id'       => null,
                            'promotion_id'  => $promotion_id,
                            'description'   => $promotion_name,
                            'price_unit'    => $promotion_discount,
                            'quantity'      => $discount_detail['promotion']->TOTAL_UNIT,
                            'price'         => null,
                            'discount'      => $discount_detail['promotion']->TOTAL_DISCOUNT,
                            'net'           => null,
                            'p_id_use'      => null,
                        );
                    }
                    // 
                    // 
                }

                $net = $price - $discount;
            }

            if ($request['pay']) {
                $pay = $request['pay'];
            }

            // calculate VAT
            $q_vat = $this->ci->mdl_settings->get_vatNum();
            if (!$q_vat) {
                $vatnum = $this->ci->config->item('vat_num');
            } else {
                $vatnum = $q_vat->VAT_NUM;
            }

            $price_withvat = get_priceVat($net, $vatnum);
            if ($price_withvat) {
                $result['vat'] = $price_withvat['vat'];
                $result['vatnum'] = $price_withvat['vat_num'];
                $result['price_novat'] = $price_withvat['before_vat'];
            } else {
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
            $result['item_data_list'] = $item_data_list;
        }

        return $result;
    }

    /**
     * create bill
     *
     * @return array
     */
    public function create_bill()
    {
        $request = $_REQUEST;

        $customer = $request['customer'] ? textNull($request['customer']) : null;
        $customer_id = $request['customer_id'] ? textNull($request['customer_id']) : null;
        $agent_name = $request['agent_name'] ? textNull($request['agent_name']) : null;
        $agent_contact = $request['agent_contact'] ? textNull($request['agent_contact']) : null;
        $round_id = $request['round'] ? textNull($request['round']) : null;
        $date_order = $request['date_order'] ? textNull($request['date_order']) : null;
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
            'date_order'    => $date_order,
            'booking_date'  => $bookingdate,

            'price'     => null,
            'discount'  => null,
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

            $deposit = 0;
            if ($detail_bill['pay']) {
                // 
                // create receipt
                $deposit = $detail_bill['pay'];
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

        // 
        // insert bill
        $data_bill = $this->ci->mdl_bill->insert_data($data_insert);
        $item_new_data = [];

        if ($data_bill) {

            $item_bill_id = $data_bill['data']['id'];
            $item_bill_code = $data_bill['data']['code'];

            $item_data = [];
            if ($detail_bill['item_data_list']) {

                $data_b_i_l = $detail_bill['item_data_list'];

                if (is_string($data_b_i_l)) {
                    $item_data = json_decode($data_b_i_l);
                } else {
                    $item_data = $data_b_i_l;
                }
                if ($item_data) {
                    foreach ($item_data as $key => $row) {
                        $item_new_data[] = array(
                            'bill_id'           => $item_bill_id,
                            'bill_code'         => $item_bill_code,

                            'item_id'           => $row['item_id'],
                            'promotion_id'      => $row['promotion_id'],
                            'description'       => $row['description'],
                            'price_unit'        => $row['price_unit'],
                            'quantity'          => $row['quantity'],
                            'price'             => $row['price'],
                            'discount'          => $row['discount'],
                            'net'               => $row['net'],
                            'p_id_use'          => $row['p_id_use'],
                        );
                    }
                }
            }

            // 
            // insert bill detail
            if ($item_new_data) {
                $data_bill = $this->ci->mdl_bill_detail->insert_data_batch($item_new_data);
            }

            if ($deposit) {
                $this->ci->load->library('receipt');
                //
                // สร้างใบรับโอน
                $this->ci->receipt->create_deposit($item_bill_id, $item_bill_code, $deposit);
            }
        }   // END if bill detail

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

    function update_bill($id = null)
    {
        $result = null;

        if (!$id) {
            $request = $_REQUEST;
            $id = $request['item_id'];
        }
        if ($id) {
            $optional['select'] = "sum(deposit) as total_deposit";
            $optional['where'] = array(
                'bill_id'   => $id,
                'status'    => 1
            );
            $q_deposit = $this->ci->mdl_deposit->get_data(null, $optional, 'row_array');
            if ($q_deposit) {
                $deposit = $q_deposit['total_deposit'];
            }

            $q_net = $this->ci->mdl_bill->get_data($id, null);
            if ($q_net) {
                $net = $q_net->NET;
            }

            $datastatus = $this->get_status_bill($deposit, $net);

            if ($datastatus) {
                $data_update = array(
                    'payment_id' => $datastatus['payment_id'],
                    'payment_alias' => $datastatus['payment_status'],
                    'complete_id' => $datastatus['complete_id'],
                    'complete_alias' => $datastatus['complete_status'],
                );
                $this->ci->mdl_bill->update_bill($data_update, $id);

                $result = array(
                    'error' => 0,
                    'txt'   => 'ทำรายการสำเร็จ',
                    'data'  => $datastatus
                );
            }
        }


        return $result;
    }
}
