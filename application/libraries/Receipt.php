<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Receipt
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
        $this->ci->load->model(
            array(
                'mdl_settings',
                'bill/mdl_bill',
                'receipt/mdl_receipt',
                'deposit/mdl_deposit',
            )
        );
        $this->ci->load->library('Bill');

        $this->tb = 'receipt';
    }

    public function gen_code()
    {
        # code...
        $billmain = "001";
        $billsub = "0001";
        $sql = $this->ci->db->from($this->tb)
            ->where('status', 1)
            ->group_by('billmain');
        $query = $sql->get();
        $num = $query->num_rows();
        if ($num) {
            $bill_sub = "";

            $next = 1;
            foreach ($query->result() as $r) {
                if ($next == 1) {
                    $sql_main = $this->ci->db->from($this->tb)
                        ->where('billmain', $r->BILLMAIN)
                        ->where('status', 1)
                        ->order_by('id', 'desc');
                    $query_main = $sql_main->get();
                    $num_main = $query_main->num_rows();
                    if ($num_main) {
                        $row = $query_main->row();

                        $billmain = $row->BILLMAIN;

                        $numbersub = (int)$row->BILLSUB;

                        if ($numbersub < 9999) {
                            $next = 0;

                            $numbersub = $numbersub + 1;
                            $bill_sub = str_pad($numbersub, 4, '0', STR_PAD_LEFT);
                            $billsub = $bill_sub;
                        }
                    }
                }
            }

            if (!$bill_sub) {
                $numbermain = (int)$billmain;
                $numbermain = $numbermain + 1;
                $billmain = str_pad($numbermain, 3, '0', STR_PAD_LEFT);
            }
        }

        $code = $billmain . "/" . $billsub;

        $result = array(
            'code'  => $code,
            'billmain'  => $billmain,
            'billsub'  => $billsub
        );

        return $result;
    }
    /**
     * create bill
     *
     * @return array
     */
    /**
     * create deposit
     *
     * @param int $id = id bill
     * @param string|null $codebill = code bill
     * @param string|null $deposit = deposit
     * @return void
     */
    public function create_deposit(int $id = null, string $codebill = null, $deposit = null)
    {
        $request = $_REQUEST;

        $result = array(
            'error' => 1,
            'txt'        => 'ไม่มีการทำรายการ'
        );

        $id = $id ? $id : $request['id'];
        $codebill = $codebill ? $codebill : textNull($request['codebill']);
        $bank_id = textNull($request['bank']);
        $bank_name = textNull($request['bank_name']);
        $deposit = $deposit ? $deposit : textNull($request['deposit']);
        $codetext = textNull($request['codetext']);

        if (!$id) {
            $result = array(
                'error' => 1,
                'txt'        => 'ไม่พบรายการอ้างอิง'
            );
            return $result;
        }

        if ($id) {

            $optional['where'] = array(
                'id' => $id
            );
            $bill = $this->ci->mdl_bill->get_data(null, $optional, 'row_array');
            $bill = array_change_key_case($bill, CASE_LOWER);
            $net = $bill['net'];
            if (!$codebill) {
                $codebill = $bill['CODE'];
            }

            $bill_id = $bill['id'];
            $deposit_date = textNull($request['deposit_date']) ? textNull($request['deposit_date']) : date('Y-m-d');
            $pos_date = textNull($request['pos_date']) ? textNull($request['pos_date']) : null;
            $remark = textNull($request['deposit_remark']) ? textNull($request['deposit_remark']) : null;
            $total_unit =  $bill['total_unit'];

            $data_insert = array(
                'bill_id'       => $bill_id,
                'bill_code'     => $codebill,
                'bill_net'      => $bill['net'],

                'bank_id'       => $bank_id,
                'bank_name'     => $bank_name,

                'deposit_date'  => $deposit_date,
                'pos_date'      => $pos_date,
                'deposit'       => $deposit,
                'total_unit'    => $total_unit,
                'remark'        => $remark,
            );

            if ($codetext) {
                $data_insert['codetext'] = $codetext;
            }
            $this->ci->db->trans_begin();

            // 
            // insert bill
            $bill = $this->ci->mdl_deposit->insert_data($data_insert);
            $deposit_id = $bill['data']['id'];

            //
            // ตรวจสอบยอดเงินโอน
            // สร้างใบรับเงิน หากยอดเงินโอนครบ หรือมากกว่า
            $optionalr['where'] = array(
                'bill_id' => $bill_id
            );
            $check_receipt = $this->ci->mdl_receipt->get_dataShow(null, $optionalr, 'row');
            if (!$check_receipt) {
                $sql_deposit = $this->ci->db->select('sum(deposit) as total_deposit')
                    ->from('deposit')
                    ->where('bill_id', $bill_id)
                    ->where('status', 1)
                    ->get();
                $r = $sql_deposit->row();
                $total_deposit = textNull($r->total_deposit);
                if ($total_deposit && $total_deposit >= $net) {
                    $this->create_bill($bill_id, $codebill, $deposit);
                }
            }

            // update bill status
            $datastatus = $this->ci->bill->update_bill($bill_id);
            if ($datastatus && $datastatus['data']['data']['complete_id'] == 3) {
                $data_update = array(
                    'bill_complete'     => "1"
                );
                $update = $this->ci->mdl_deposit->update_data($data_update, $deposit_id,true);
            }

            if ($this->ci->db->trans_status() === FALSE) {
                $this->ci->db->trans_rollback();
            } else {
                $this->ci->db->trans_commit();

                $result = array(
                    'error' => 0,
                    'txt'   => 'ทำรายการสำเร็จ',
                    'data'  => $bill
                );
            }
        }

        return $result;
    }

    /**
     * update deposit
     *
     * @param int $id = id deposit
     * @return void
     */
    function update_deposit($id = null)
    {
        $result = null;
        $request = $_REQUEST;

        $result = array(
            'error' => 1,
            'txt'   => 'ไม่มีการทำรายการ'
        );

        if (!$id) {
            $id = $request['item_id'];
        }
        if ($id) {
            $codetext = textNull($request['codetext']);
            $dateorder = textNull($request['deposit_date']);
            $deposit = textNull($request['deposit']);
            $pos_date = textNull($request['pos_date']);
            $bank_id = textNull($request['bank']);
            $bank_name = textNull($request['bank_name']);
            $remark = textNull($request['deposit_remark']);

            if ($codetext && $deposit) {
                $data_update = array(
                    'codetext' => $codetext,
                    'deposit_date' => $dateorder,
                    'deposit' => $deposit,

                    'pos_date' => $pos_date,
                    'bank_id' => $bank_id,
                    'bank_name' => $bank_name,
                    'remark' => $remark,
                );
                $r = $this->ci->mdl_deposit->update_data($data_update, $id);

                // clear codetext receipt auto
                $r_dp = $this->ci->mdl_deposit->get_dataShow($id);
                $bill_id = $r_dp->BILL_ID;
                if ($bill_id) {
                    $optional['where'] = array(
                        'bill_id'   => $bill_id
                    );
                    $r_rc = $this->ci->mdl_receipt->get_data(null, $optional, 'row');
                    $rc_id = $r_rc->ID;
                    $this->clear_rc_codetext($rc_id);

                    /* // update status bill
                    $datastatus = $this->ci->bill->update_bill($bill_id);
                    if ($datastatus && $datastatus['data']['data']['complete_id'] == 3) {
                        $data_update = array(
                            'bill_complete'     => "1"
                        );
                        $update = $this->ci->mdl_deposit->update_data($data_update, $id);
                    }else{
                        $data_update = array(
                            'bill_complete'     => null
                        );
                        $update = $this->ci->mdl_deposit->update_data($data_update, $id);
                    } */
                }

                $result = array(
                    'error' => $r['error'],
                    'txt'   => $r['txt'],
                    'data'  => $data_update
                );
            }
        }

        return $result;
    }

    function delete_deposit($id = null)
    {
        $result = null;
        $request = $_REQUEST;

        $result = array(
            'error' => 1,
            'txt'   => 'ไม่มีการทำรายการ'
        );

        if (!$id) {
            $id = $request['item_id'];
        }
        if ($id) {

            $this->ci->db->trans_begin();

            $this->ci->mdl_deposit->delete_data();

            // clear codetext receipt auto
            $r_dp = $this->ci->mdl_deposit->get_data($id);
            $bill_id = $r_dp->BILL_ID;
            if ($bill_id) {
                $optional['where'] = array(
                    'bill_id'   => $bill_id
                );
                $r_rc = $this->ci->mdl_receipt->get_data(null, $optional, 'row');
                $rc_id = $r_rc->ID;
                $this->clear_rc_codetext($rc_id);

                // update status bill
                $this->ci->bill->update_bill($bill_id);
            }


            if ($this->ci->db->trans_status() === FALSE) {
                $this->ci->db->trans_rollback();
            } else {
                $this->ci->db->trans_commit();

                $result = array(
                    'error' => 0,
                    'txt'   => "ทำรายการสำเร็จ"
                );
            }
        }

        return $result;
    }

    /**
     * clear codetext
     *
     * @param int $id = id receipt
     * @return void
     */
    function clear_rc_codetext(int $id = null)
    {
        if ($id) {
            $data_update = array(
                'codetext'  => null
            );
            $this->ci->mdl_receipt->update_data($data_update, $id);
        }

        return true;
    }

    /**
     * create bill
     *
     * @return array
     */
    /**
     * create receipt
     *
     * @param int $id = id bill
     * @param string|null $codebill = code bill
     * @param string|null $deposit = deposit
     * @return void
     */
    public function create_bill(int $id = null, string $codebill = null, $deposit = null)
    {
        $result = array(
            'error' => 1,
            'txt'        => 'ไม่มีการทำรายการ'
        );

        if (!$id) {
            $result = array(
                'error' => 1,
                'txt'        => 'ไม่พบรายการอ้างอิง'
            );
            return $result;
        }

        if ($id) {
            $request = $_REQUEST;

            $optional['where'] = array(
                'id' => $id
            );
            $bill = $this->ci->mdl_bill->get_data(null, $optional, 'row_array');
            $bill = array_change_key_case($bill, CASE_LOWER);
            if (!$codebill) {
                $codebill = $bill['code'];
            }

            $code = $this->gen_code();

            $date_today = date('Y-m-d');
            $bill_id = $bill['id'];
            $customer_name = textNull($bill['customer_name']);
            $customer_id = textNull($bill['customer_id']);
            $date_order = textNull($bill['booking_date']) ? textNull($bill['booking_date']) : $date_today;
            $price_novat = textNull($bill['price_novat']);
            $vat = textNull($bill['vat']);
            $net = textNull($bill['net']);

            //
            // get data deposit 
            $array_codetext = [];
            $codetext = "";
            $optional_deposit['where'] = array(
                'bill_id'  => $id
            );
            $q_deposit = $this->ci->mdl_deposit->get_data(null, $optional_deposit, 'result_array');
            if ($q_deposit) {
                foreach ($q_deposit as $key => $r_deposit) {
                    $array_codetext[] = $r_deposit['CODETEXT'];
                }

                if ($array_codetext) {
                    asort($array_codetext);
                    $codetext = implode(",", $array_codetext);
                }
            }

            $data_insert = array(
                'code'      => $code['code'],
                'billmain'  => $code['billmain'],
                'billsub'   => $code['billsub'],

                'bill_id'       => $bill_id,
                'bill_code'     => $codebill,

                'date_order'   => $date_order,
                'customer_id'   => $customer_id,
                'customer_name'   => $customer_name,

                'price_novat'   => $price_novat,
                'vat'   => $vat,
                'net'   => $net,
            );
            if ($codetext) {
                $data_insert['codetext'] = $codetext;
            }
            $this->ci->db->trans_begin();

            // 
            // insert bill
            $bill = $this->ci->mdl_receipt->insert_data($data_insert);

            // update bill status
            $this->ci->bill->update_bill($id);

            if ($this->ci->db->trans_status() === FALSE) {
                $this->ci->db->trans_rollback();
            } else {
                $this->ci->db->trans_commit();

                $result = array(
                    'error' => 0,
                    'txt'   => 'ทำรายการสำเร็จ',
                    'data'  => $bill
                );
            }
        }

        return $result;
    }

    /**
     * update receipt
     *
     * @param int $id = id receipt
     * @return void
     */
    function update_receipt($id = null)
    {
        $result = null;
        $request = $_REQUEST;

        $result = array(
            'error' => 1,
            'txt'   => 'ไม่มีการทำรายการ'
        );

        if (!$id) {
            $id = $request['item_id'];
        }

        if ($id) {

            $date_order = textNull($request['date_order']);
            $remark = textNull($request['receipt_remark']);

            $data_update = array(
                'date_order'    => $date_order,
                'remark'        => $remark
            );

            if (textNull($request['rc_codetext'])) {
                $codetext = textNull($request['rc_codetext']);
                $data_update['codetext'] = $codetext;
            }

            $r = $this->ci->mdl_receipt->update_data($data_update, $id);

            $result = array(
                'error' => $r['error'],
                'txt'   => $r['txt'],
                'data'  => $data_update
            );
        }
        return $result;
    }
}
