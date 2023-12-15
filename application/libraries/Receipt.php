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
    public function create_deposit(int $id = null, string $codebill = null,$deposit = null)
    {
        $request = $_REQUEST;

        $result = array(
            'error' => 1,
            'txt'        => 'ไม่มีการทำรายการ'
        );

        $id = $id ? $id : $request['id'];
        $codebill = $codebill ? $codebill : textNull($request['codebill']);
        $deposit = $deposit ? $deposit : textNull($request['deposit']);

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
            if (!$codebill) {
                $codebill = $bill['CODE'];
            }

            $bill_id = $bill['id'];
            $date_order = textNull($bill['date_order']) ? textNull($bill['date_order']) : textNull($request['date_order']);
            $remark = textNull($request['deposit_remark']) ? textNull($request['deposit_remark']) : null;


            $data_insert = array(
                'bill_id'       => $bill_id,
                'bill_code'     => $codebill,

                'date_order'    => $date_order,
                'deposit'       => $deposit,
                'remark'        => $remark,
            );

            $this->ci->db->trans_begin();

            // 
            // insert bill
            $bill = $this->ci->mdl_deposit->insert_data($data_insert);

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
    public function create_bill(int $id = null, string $codebill = null,$deposit = null)
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
                $codebill = $bill['CODE'];
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
            $this->ci->db->trans_begin();

            // 
            // insert bill
            $bill = $this->ci->mdl_receipt->insert_data($data_insert);

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

        // $customer = $request['customer'] ? textNull($request['customer']) : null;
    }

}
