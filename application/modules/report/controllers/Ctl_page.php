<?php
defined('BASEPATH') or exit('No direct script access allowed');

// require APPPATH . '/libraries/API_Controller.php';

class Ctl_page extends MY_Controller
{

    private $model;
    private $title;

    public function __construct()
    {
        parent::__construct();
        $modelname = 'mdl_page';
        $this->load->model(array('report/mdl_page'));

        $this->middleware(
            array(
                'access'    => [
                    // 'index'     => ['bill','quotation'],
                    // 'view'      => ['bill.view','bill.insert']
                ],
                'need'       => ['report.role'],
                'except'    => [
                    // 'index'      => ['workorder','bill.view','bill'],
                    // 'view'      => [],
                ]
            )
        );

        // setting
        $this->model = $this->$modelname;
        $this->title = 'รายงานสรุปรับเงินมัดจำ';
    }

    public function index()
    {
        $this->template->set_layout('lay_report');
        $this->template->title($this->title);
        $this->template->build('pages/index');
    }

    /**
     *
     * get data to datatable
     * non-severside (load all data before display)
     *
     * # whois() = my_sql_helper
     * # textShow() = my_text_helper
     * # workstatus() = my_html_helper
     * # status_offview() = my_html_helper
     * # toThaiDateTimeString() = my_date_helper
     * 
     * @return void
     */
    public function get_dataTable()
    {
        $this->load->helper('my_date');

        $request = $_REQUEST;

        $optional['select'] = "
        bill.*,
        deposit.id as dp_id,
        deposit.codetext as dp_codetext,
        deposit.deposit as dp_deposit,
        deposit.bill_net as dp_bill_net,
        deposit.bank_name as dp_bank,
        deposit.bill_complete as dp_complete,
        deposit.total_unit as dp_total_unit,
        deposit.deposit_date as deposit_date,
        deposit.pos_date as dp_pos_date,
        bank.id as bank_id,
        bank.numbercard as bank_numbercard,
        ";
        $data = $this->model->get_dataShow(null, $optional);
        $count = $this->model->get_data_all();
        // print_r($data);

        $data_result = [];

        if ($data) {
            // split group
            $group = array_values(array_unique(array_column($data, 'CODE')));
            $deposit_array = array_keys(array_column($data, "dp_complete"), null);
            $receipt_array = array_keys(array_column($data, "dp_complete"), 1);
            // print_r($receipt_array);
            if ($group) {
                foreach ($group as $group_k => $group_v) {
                    $price_paid = 0;

                    $deposit_id = null;
                    $deposit_code = null;
                    $deposit_bill_net = null;
                    $deposit_net = null;
                    $deposit_total_unit = null;
                    $deposit_bank_id = null;
                    $deposit_bank_name = null;
                    $deposit_bank_number = null;
                    $deposit_pos_date = null;

                    $paid_id = null;
                    $paid_code = null;
                    $paid_net = null;
                    $paid_total_unit = null;
                    $paid_bank_id = null;
                    $paid_bank_name = null;
                    $paid_bank_number = null;

                    $bill_array = (array)array_keys(array_column($data, "CODE"), $group_v);
                    // print_r($bill_array);
                    //
                    // deposit
                    if ($bill_array && $deposit_array) {
                        $array = [];
                        $array = array_values(array_intersect($bill_array, $deposit_array));
                        if ($array) {
                            $deposit_id = textShow($data[$array[0]]->dp_id);
                            $deposit_code = textShow($data[$array[0]]->dp_codetext);
                            $deposit_bill_net = textShow($data[$array[0]]->dp_bill_net);
                            $deposit_net = textShow($data[$array[0]]->dp_deposit);
                            $deposit_total_unit = textShow($data[$array[0]]->dp_total_unit);
                            $deposit_bank_id = textShow($data[$array[0]]->bank_id);
                            $deposit_bank_name = textShow($data[$array[0]]->dp_bank);
                            $deposit_bank_number = textShow($data[$array[0]]->bank_numbercard);
                            $deposit_pos_date = textShow($data[$array[0]]->dp_pos_date);
                        
                            $price_paid = $price_paid + floatval($deposit_net);
                        }
                    }

                    // 
                    // deposit (last time)
                    if ($bill_array && $receipt_array) {
                        $array = [];
                        $array = array_values(array_intersect($bill_array, $receipt_array));
                        if ($array) {
                            $paid_id = textShow($data[$array[0]]->dp_id);
                            $paid_code = textShow($data[$array[0]]->dp_codetext);
                            $paid_net = textShow($data[$array[0]]->dp_deposit);
                            $paid_total_unit = textShow($data[$array[0]]->dp_total_unit);
                            $paid_bank_id = textShow($data[$array[0]]->bank_id);
                            $paid_bank_name = textShow($data[$array[0]]->dp_bank);
                            $paid_bank_number = textShow($data[$array[0]]->bank_numbercard);

                            $price_paid = $price_paid + floatval($paid_net);
                        }
                    }

                    $group[$group_k] = array(
                        'bill_complete_id' => $data[$bill_array[0]]->COMPLETE_ID,
                        'bill_complete' => $data[$bill_array[0]]->COMPLETE_ALIAS,
                        'bill_payment_id' => $data[$bill_array[0]]->PAYMENT_ID,
                        'bill_payment' => $data[$bill_array[0]]->PAYMENT_ALIAS,
                        'bill_customer' => $data[$bill_array[0]]->CUSTOMER_NAME,
                        'bill_customer_id' => $data[$bill_array[0]]->CUSTOMER_ID,
                        'bill_user_starts' => $data[$bill_array[0]]->USER_STARTS,
                        'bill_user_update' => $data[$bill_array[0]]->USER_UPDATE,
                        'bill_date_starts' => $data[$bill_array[0]]->DATE_STARTS,
                        'bill_date_update' => $data[$bill_array[0]]->DATE_UPDATE,
                        'bill_id' => $data[$bill_array[0]]->ID,
                        'bill_code' => $data[$bill_array[0]]->CODE,
                        'bill_net' => $data[$bill_array[0]]->NET,
                        'bill_date_order' => $data[$bill_array[0]]->DATE_ORDER,
                        'bill_booking' => $data[$bill_array[0]]->BOOKING_DATE,
                        'bill_status' => $data[$bill_array[0]]->STATUS_OFFVIEW,
                        'bill_paid' => $price_paid,

                        'deposit_id' => $deposit_id,
                        'deposit_code' => $deposit_code,
                        'deposit_bill_net' => $deposit_bill_net,
                        'deposit_net' => $deposit_net,
                        'deposit_total_unit' => $deposit_total_unit,
                        'deposit_bank_id' => $deposit_bank_id,
                        'deposit_bank_name' => $deposit_bank_name,
                        'deposit_bank_number' => $deposit_bank_number,
                        'deposit_pos_date' => $deposit_pos_date,
                        
                        'paid_id' => $paid_id,
                        'paid_code' => $paid_code,
                        'paid_net' => $paid_net,
                        'paid_total_unit' => $paid_total_unit,
                        'paid_bank_id' => $paid_bank_id,
                        'paid_bank_name' => $paid_bank_name,
                        'paid_bank_number' => $paid_bank_number,
                    );
                }
            }
            // print_r($group);
            $no = 1;
            foreach ($group as $key => $row) {
                $user_active_id = $row['bill_user_starts'] ? $row['bill_user_starts'] : $row['bill_user_update'];

                if ($row['bill_date_update']) {
                    $query_date = $row['bill_date_update'];
                    $user_active = "(แก้) " . whois($row['bill_user_update']);
                } else {
                    $query_date = $row['bill_date_starts'];
                    $user_active =  whois($row['bill_user_starts']);
                }

                $dom_status = status_offview($row['STATUS_OFFVIEW']);

                $sub_data = [];

                $sub_data['ID'] = $row['ID'];
                $sub_data['NO'] = $no;

                $sub_data['BILL'] = array(
                    "display"   => $row['bill_code'],
                    "data"      =>  array(
                        'id'    => $row['ID'],
                        'code'    => textShow($row['bill_code']),
                        'net'       => textShow($row['bill_net']),
                        'paid'      => textShow($row['bill_paid']),
                        'date_order'    => textShow($row['bill_date_order']),
                        'date_booking'    => textShow($row['bill_booking']),
                    ),
                );

                $sub_data['DATE_ORDER'] = array(
                    "display"   => convertdate_fromDB($row['bill_date_order']),
                    "timestamp" => date('Y-m-d H:i:s', strtotime($row['bill_date_order']))
                );
                $sub_data['BOOKING_DATE'] = array(
                    "display"   => convertdate_fromDB($row['bill_booking']),
                    "timestamp" => date('Y-m-d H:i:s', strtotime($row['bill_booking']))
                );
                $sub_data['POS_DATE'] = array(
                    // "display"   => convertdate_fromDB($row->deposit_pos_date),
                    "display"   => convertdate_fromDB($row['deposit_pos_date']),
                    "timestamp" => date('Y-m-d H:i:s', strtotime($row['deposit_pos_date']))
                );

                $sub_data['CUSTOMER'] = array(
                    "display"   => $row['bill_customer'],
                    "data"      =>  array(
                        'id'    => $row['bill_customer_id'],
                    ),
                );

                $sub_data['DEPOSIT'] = array(
                    "data"      =>  array(
                        'deposit_code'    => $row['deposit_code'],
                        'deposit_bill_net'  => $row['deposit_bill_net'],
                        'deposit_bill_net_display'  => textMoney($row['deposit_bill_net']),
                        'deposit_net'    => $row['deposit_net'],
                        'deposit_net_display'    => textMoney($row['deposit_net']),
                        'deposit_total_unit'    => $row['deposit_total_unit'],
                        'deposit_bank_name'    => $row['deposit_bank_name'],
                        'deposit_bank_number'    => $row['deposit_bank_number'],
                    ),
                );

                $sub_data['PAID'] = array(
                    "display"   => textMoney($row['paid_net']),
                    "data"      =>  array(
                        'paid_code'    => $row['paid_code'],
                        'paid_net'    => $row['paid_net'],
                        'paid_total_unit'    => $row['paid_total_unit'],
                        'paid_bank_name'    => $row['paid_bank_name'],
                        'paid_bank_number'    => $row['paid_bank_number'],
                        'paid_pos_date'    => array(
                            "display"   => toDateTimeString($row['paid_pos_date'], 'datetime'),
                            "timestamp" => date('Y-m-d H:i:s', strtotime($row['paid_pos_date']))
                        )
                    ),
                );

                $sub_data['STATUS'] = array(
                    "display"   => $row['bill_complete'],
                    "data"   => array(
                        'id'    => $row['bill_complete_id'],
                    ),
                );
                $sub_data['PAYMENT'] = array(
                    "display"   => $row['bill_payment'],
                    "data"   => array(
                        'id'    => $row['bill_payment_id'],
                    ),
                );

                $sub_data['USER_ACTIVE'] = array(
                    "display"   => $user_active,
                    "data"   => array(
                        'id'    => $user_active_id,
                    ),
                );

                $sub_data['DATE_ACTIVE'] = array(
                    "display"   => toDateTimeString($query_date, 'datetime'),
                    "timestamp" => date('Y-m-d H:i:s', strtotime($query_date))
                );

                $sub_data['DATA_NULL'] = "";
                $sub_data['DATA'] = "xxxx";

                $no++;
                $data_result[] = $sub_data;
            }
        }

        $result = array(
            "recordsTotal"      =>     count($data),
            "recordsFiltered"   =>     $count,
            "data"              =>     $data_result
        );

        echo json_encode($result);
    }

    //  *
    //  * CRUD
    //  * read
    //  * 
    //  * get data for item id
    //  *
    public function get_data()
    {
        $request = $_REQUEST;
        $item_id = $request['id'];
        $data = $this->model->get_data($item_id);

        $result = $data;
        echo json_encode($result);
    }

    //  *
    //  * CRUD
    //  * insert
    //  * 
    //  * insert data
    //  *
    public function insert_data()
    {
        # code...
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $returns = $this->model->insert_data();
            echo json_encode($returns);
        }
    }

    //  *
    //  * CRUD
    //  * update
    //  * 
    //  * update data
    //  *
    public function update_data()
    {
        # code...
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $returns = $this->model->update_data();
            echo json_encode($returns);
        }
    }


    //  *
    //  * CRUD
    //  * delete
    //  * 
    //  * delete data
    //  *
    public function delete_data()
    {
        # code...
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $returns = $this->model->delete_data();
            echo json_encode($returns);
        }
    }
}
