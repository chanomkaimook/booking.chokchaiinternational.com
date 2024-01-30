<?php
defined('BASEPATH') or exit('No direct script access allowed');

// require APPPATH . '/libraries/API_Controller.php';

class Ctl_bill extends MY_Controller
{

    private $model;
    private $title;

    public function __construct()
    {
        parent::__construct();
        $modelname = 'mdl_bill';
        $this->load->model(array(
            'bill/mdl_bill',
            'information/mdl_round',
            'information/mdl_bank',
            'information/mdl_customer_address',
            'receipt/mdl_receipt'
        ));
        $this->load->library(array('Bill'));

        $this->middleware(
            array(
                'access'    => [
                    // 'index'     => ['bill','quotation'],
                    // 'view'      => ['bill.view','bill.insert']
                ],
                // 'need'       => ['bill','quotation'],
                'except'    => [
                    // 'index'      => ['workorder','bill.view','bill'],
                    // 'view'      => [],
                ]
            )
        );

        // setting
        $this->model = $this->$modelname;
        $this->title = 'ตารางจองทัวร์';
    }

    public function index()
    {
        $optional['order_by'] = array('id' => 'asc');
        $data['bank'] = $this->mdl_bank->get_dataShow(null, $optional);
        $data['round'] = $this->mdl_round->get_dataShow(null, $optional);
        $data['address'] = $this->mdl_customer_address->get_dataShow(null, $optional);

        $this->template->set_partial(
            'headlink',
            'partials/link/page',
            array(
                'data'  => array(
                    '<link href="' . base_url('') . 'asset/plugins/jquery/ui/1.13.2/jquery-ui.css" rel="stylesheet" type="text/css" />',
                    '<link href="' . base_url('') . 'asset/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.css" rel="stylesheet" type="text/css" />',
                )
            )
        );
        $this->template->set_partial(
            'footerscript',
            'partials/script/page',
            array(
                'data'  => array(
                    '<script src="' . base_url('') . 'asset/plugins/jquery/ui/1.13.2/jquery-ui.js"></script>',
                    '<script src="' . base_url('') . 'asset/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>',
                )
            )
        );

        $this->template->set_layout('lay_datatable');
        $this->template->title($this->title);
        $this->template->build('bills/index', $data);
    }

    public function document()
    {
        $data = [];

        // set page title
        $data['pagetitle'] = "ใบเสนอราคา";
        $data['breadcrumb'] = array('รายการจอง', 'ข้อมูลการจอง');

        $optional['order_by'] = array('id' => 'asc');
        $data['bank'] = $this->mdl_bank->get_dataShow(null, $optional);
        $data['round'] = $this->mdl_round->get_dataShow(null, $optional);

        $optional = [];
        $optional_joinbill = [];
        $item_code = $this->input->get('code');


        if ($item_code) {
            $optional['where'] = array(
                'bill.code'  => $item_code
            );
            $data['bill'] = $this->mdl_bill->get_dataShow(null, $optional, 'row_array');

            $optional_joinbill['where'] = array(
                'bill_code'  => $item_code
            );

            $optional_deposit = $optional_joinbill;
        } else {
            $item_id = $this->input->get('id');
            $data['bill'] = $this->mdl_bill->get_dataShow(textNull($item_id), null, 'row_array');

            $optional_joinbill['where'] = array(
                'bill_id'  => textNull($item_id)
            );

            $optional_deposit = $optional_joinbill;
        }
        // $optional_deposit['order_by'] = array('id'=>'asc');

        $data['bill_detail'] = $this->mdl_bill_detail->get_dataShow(null, $optional_joinbill, 'result_array');

        $deposit = "";
        $this->load->model('deposit/mdl_deposit');
        $optional_deposit['select'] = "sum(deposit) as total_deposit";

        $q_deposit = $this->mdl_deposit->get_datashow(null, $optional_deposit, 'row_array');
        if ($q_deposit) {
            $deposit = $q_deposit['total_deposit'];
        }
        $data['total_deposit'] = $deposit;

        // $this->load->model('receipt/mdl_receipt');
        // $data['bill_receipt'] = $this->mdl_receipt->get_data(null, $optional_joinbill, 'result_array');

        $this->template->set_layout('lay_main');
        $this->template->title($this->title);
        $this->template->build('bills/document_bill', $data);
    }

    public function receipt()
    {
        $data = [];

        // set page title
        $data['pagetitle'] = "ใบเสร็จรับเงิน";
        $data['breadcrumb'] = array('รายการจอง', 'ข้อมูลการจอง');

        $bill = null;
        $item_id = null;
        $optional = [];

        $item_code = $this->input->get('code');

        if ($item_code) {
            $optional['where'] = array(
                'bill.code'  => $item_code
            );
            $b = $this->mdl_bill->get_data(null, $optional, 'row_array');
            if ($b) {
                $item_id = $b['ID'];
                $bill = $this->bill->get_bill($item_id);
            }
        } else {
            $item_id = $this->input->get('id');
            if ($item_id) {
                $bill = $this->bill->get_bill($item_id);
            }
        }

        if ($item_id) {
            $optional_receipt['where'] = array(
                'bill_id'   => $item_id
            );
            $data['receipt'] = $this->mdl_receipt->get_datashow(null, $optional_receipt, 'row_array');
        }

        $data['bill'] = (array)$bill['data'];

        $this->template->set_layout('lay_main');
        $this->template->title($this->title);
        $this->template->build('bills/document_receipt', $data);
    }

    public function export()
    {
        $page = $this->input->get('page');
        if ($page) {

            $data = [];

            $bill = null;
            $item_id = null;
            $optional = [];

            $item_code = $this->input->get('code');

            if ($item_code) {
                $optional['where'] = array(
                    'bill.code'  => $item_code
                );
                $b = $this->mdl_bill->get_data(null, $optional, 'row_array');
                if ($b) {
                    $item_id = $b['ID'];
                    $bill = $this->bill->get_bill($item_id);
                }
            } else {
                $item_id = $this->input->get('id');
                if ($item_id) {
                    $bill = $this->bill->get_bill($item_id);
                }
            }

            if ($item_id) {
                $optional_receipt['where'] = array(
                    'bill_id'   => $item_id
                );
                $data['receipt'] = $this->mdl_receipt->get_datashow(null, $optional_receipt, 'row_array');
            }

            $data['bill'] = (array)$bill['data'];
            $data['q_setting'] = $this->mdl_settings->get_data();

            switch ($page) {
                case 'bill':
                    $path = "bills/excel/bill";
                    break;
                case 'receipt':
                    $path = "bills/excel/receipt";
                    break;
            }

            $this->load->view($path, $data);
        }
    }

    public function get_dataCalendar()
    {
        $request = $_REQUEST;
        $this->load->model('deposit/mdl_deposit');

        $data_array = $this->model->get_data();
        $data = [];
        if ($data_array) {

            $net_pure = "";

            foreach ($data_array as $key => $row) {
                $item_id = $row->ID;
                if ($item_id) {
                    $get_bill = $this->bill->get_bill($item_id);
                    $data[$key] = $get_bill['data'];

                    $deposit = "";
                   
                    $optional_deposit['select'] = "sum(deposit) as total_deposit";
                    $optional_deposit['where'] = array(
                        'bill_id'   => $item_id
                    );
                    $q_deposit = $this->mdl_deposit->get_datashow(null, $optional_deposit, 'row_array');
                    if ($q_deposit) {
                        $deposit = $q_deposit['total_deposit'];
                    }
                    $data[$key]->DEPOSIT= $deposit;

                    $net_pure = $this->get_data_todo_1($item_id, $data_array[$key]);
                    $data[$key]->NET_PURE = textMoney($net_pure);
                }
            }
        }
        $result = $data;
        echo json_encode($result);
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

        $data = $this->model->get_dataShow();
        $count = $this->model->get_data_all();

        $data_result = [];

        if ($data) {
            foreach ($data as $row) {

                $user_active_id = $row->USER_STARTS ? $row->USER_STARTS : $row->USER_UPDATE;

                if ($row->DATE_UPDATE) {
                    $query_date = $row->DATE_UPDATE;
                    $user_active = "(แก้) " . whois($row->USER_UPDATE);
                } else {
                    $query_date = $row->DATE_STARTS;
                    $user_active =  whois($row->USER_STARTS);
                }

                $bookingd_date = null;
                $booking_user = null;
                if ($row->BOOKING_DATE) {
                    $bookingd_date = $row->BOOKING_DATE;
                }
                if ($row->BOOKING_USER) {
                    $booking_user = $row->BOOKING_USER;
                }

                $sub_data = [];

                $sub_data['ID'] = $row->ID;
                $sub_data['CODE'] = $row->CODE;
                $sub_data['REMARK'] = $row->REMARK;

                $sub_data['CUSTOMER'] = array(
                    "display"   => $row->CUSTOMER_NAME,
                    "data"      =>  array(
                        'id'    => $row->CUSTOMER_ID,
                        'total'    => $row->TOTAL_UNIT,
                    ),
                );

                $sub_data['AGENT'] = array(
                    "display"   => $row->AGENT_NAME,
                    "data"      =>  array(
                        'contact'    => $row->AGENT_CONTACT,
                    ),
                );

                $complete_status = workstatus($row->COMPLETE_ID, $row->COMPLETE_ALIAS);
                $sub_data['COMPLETE'] = array(
                    "display"   => $complete_status,
                    "data"      =>  array(
                        'id'    => $row->COMPLETE_ID,
                        'name'    => $row->COMPLETE_ALIAS,
                    ),
                );

                $payment_status = paymentstatus($row->PAYMENT_ID, $row->PAYMENT_ALIAS);
                $sub_data['PAYMENT'] = array(
                    "display"   => $payment_status,
                    "data"      =>  array(
                        'id'    => $row->PAYMENT_ID,
                        'name'    => $row->PAYMENT_ALIAS,
                    ),
                );

                $sub_data['BOOKING'] = array(
                    "display"   => $bookingd_date ? toDateTimeString($bookingd_date, 'date') : null,
                    "timestamp" => date('Y-m-d H:i:s', strtotime($bookingd_date)),
                    "data"      =>  array(
                        'staff'    => $booking_user ? whois($booking_user) : null,
                    ),
                );

                $sub_data['ROUND'] = array(
                    "display"   => $row->ROUND_NAME,
                    "data"      =>  array(
                        'id'    => $row->ROUND_ID,
                        'time_start'    => $row->TIME_START,
                        'time_end'    => $row->TIME_END,
                    ),
                );

                $sub_data['BILL_PRICE'] = array(
                    "display"   => textMoney($row->NET),
                    "data"   => array(
                        'price'     => textMoney($row->PRICE),
                        'discount'  => textMoney($row->DISCOUNT),
                        'deposit'   => textMoney($row->DEPOSIT),
                        'vat'       => textMoney($row->VAT),
                        'vatnum'       => $row->VATNUM
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

    /**
     * get detail from cart
     *
     * @return void
     */
    public function get_cartData()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $data = $this->bill->get_cartData();

            $result = $data;
            echo json_encode($result);
        }
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

        if ($data) {

            $net_pure = "";

            if ($item_id) {

                $net_pure = $this->get_data_todo_1($item_id, $data);

                $data->NET_PURE = textMoney($net_pure);
            } else {
                if (count($data)) {
                    foreach ($data as $key => $row) {
                        $item_id = $row->ID;

                        $net_pure = $this->get_data_todo_1($item_id, $data[$key]);
                        $data[$key]->NET_PURE = textMoney($net_pure);
                    }
                }
            }
        }

        $result = $data;
        echo json_encode($result);
    }

    function get_data_todo_1($item_id, $data)
    {
        $t = "";
        if ($item_id && $data) {
            $deposit = $this->bill->get_deposit($item_id);
            if ($deposit) {
                $t = floatval($data->NET) - floatval($deposit);
            }
        }

        return $t;
    }

    //  *
    //  * CRUD
    //  * read
    //  * 
    //  * get data for bill and item
    //  *
    public function get_bill()
    {
        $request = $_REQUEST;
        $item_id = $request['id'];

        $data = [];
        if ($item_id) {
            $data = $this->bill->get_bill($item_id);
        }

        $result = $data;
        echo json_encode($result);
    }

    public function get_deposit()
    {
        $request = $_REQUEST;
        $item_id = $request['id'];
        $this->load->model('deposit/mdl_deposit');

        if ($item_id) {
            $data = $this->mdl_deposit->get_dataShow($item_id);
            //
            // add data user active
            if ($data) {
                if ($data->USER_UPDATE) {
                    $user_active = whois($data->USER_UPDATE);
                    $date_active = toThaiDateTimeString($data->DATE_UPDATE, 'datetime');
                } else {
                    $user_active = whois($data->USER_STARTS);
                    $date_active = toThaiDateTimeString($data->DATE_STARTS, 'datetime');
                }

                $data->USER_ACTIVE = $user_active;
                $data->DATE_ACTIVE = $date_active;
            }
        } else {
            if ($bill_id = $request['bill_id']) {
                $optional['where'] = array(
                    'bill_id'   => $bill_id
                );
                $optional['order_by'] = array('id' => 'asc');
                $data = $this->mdl_deposit->get_dataShow(null, $optional);
                //
                // add data user active
                if ($data) {
                    foreach ($data as $key => $row) {

                        if ($row->USER_UPDATE) {
                            $user_active = whois($row->USER_UPDATE);
                            $date_active = toThaiDateTimeString($row->DATE_UPDATE, 'datetime');
                        } else {
                            $user_active = whois($row->USER_STARTS);
                            $date_active = toThaiDateTimeString($row->DATE_STARTS, 'datetime');
                        }

                        $data[$key]->USER_ACTIVE = $user_active;
                        $data[$key]->DATE_ACTIVE = $date_active;
                    }
                }
            }
        }

        $result = $data;
        echo json_encode($result);
    }

    public function get_receipt()
    {
        $request = $_REQUEST;
        $item_id = $request['id'];
        $this->load->model('receipt/mdl_receipt');

        if ($item_id) {
            $data = $this->mdl_receipt->get_dataShow($item_id);
            //
            // add data user active
            if ($data) {
                if ($data->USER_UPDATE) {
                    $user_active = whois($data->USER_UPDATE);
                    $date_active = toThaiDateTimeString($data->DATE_UPDATE, 'datetime');
                } else {
                    $user_active = whois($data->USER_STARTS);
                    $date_active = toThaiDateTimeString($data->DATE_STARTS, 'datetime');
                }

                $data->USER_ACTIVE = $user_active;
                $data->DATE_ACTIVE = $date_active;
            }
        } else {
            if ($bill_id = $request['bill_id']) {
                $optional['where'] = array(
                    'bill_id'   => $bill_id
                );
                $optional['order_by'] = array('id' => 'asc');
                $data = $this->mdl_receipt->get_dataShow(null, $optional);
                //
                // add data user active
                if ($data) {
                    foreach ($data as $key => $row) {

                        if ($row->USER_UPDATE) {
                            $user_active = whois($row->USER_UPDATE);
                            $date_active = toThaiDateTimeString($row->DATE_UPDATE, 'datetime');
                        } else {
                            $user_active = whois($row->USER_STARTS);
                            $date_active = toThaiDateTimeString($row->DATE_STARTS, 'datetime');
                        }

                        $data[$key]->USER_ACTIVE = $user_active;
                        $data[$key]->DATE_ACTIVE = $date_active;
                    }
                }
            }
        }

        $result = $data;
        echo json_encode($result);
    }

    public function get_rc_codetext($bill_id = null)
    {
        $codetext = "";
        $result = array(
            'error' => 1,
            'txt'   => "ไม่มีการทำรายการ",
        );

        $request = $_REQUEST;
        $item_id = $bill_id ? $bill_id : $request['bill_id'];
        if ($item_id) {

            $optional['where'] = array(
                'bill_id'   => $item_id
            );
            $optional['order_by'] = array(
                'id'   => 'asc'
            );
            $data = $this->mdl_deposit->get_dataShow(null, $optional);

            if ($data) {
                $net = 0;
                $deposit = 0;
                $ar_codetext = [];

                $data_bill = $this->mdl_bill->get_data($item_id);
                if ($data_bill) {
                    $net = $data_bill->NET;
                }

                foreach ($data as $key => $row) {
                    if ($row->CODETEXT) {
                        $ar_codetext[] =  $row->CODETEXT;
                    }
                    if ($row->DEPOSIT) {
                        $deposit =  $deposit + $row->DEPOSIT;
                    }
                }

                if (textNull($deposit) && textNull($net)) {
                    if ($data_bill->COMPLETE_ID != 4) { // 4= cancel
                        if ($ar_codetext && (floatval($deposit) >= floatval($net))) {

                            $codetext = implode(",", $ar_codetext);
                            $result = array(
                                'error' => 0,
                                'txt'   => "ทำรายการสำเร็จ",
                                'data'  => array(
                                    'codetext'  => $codetext
                                )
                            );
                        } else {
                            $result = array(
                                'error' => 1,
                                'txt'   => "ยอดโอนไม่ถูกต้อง",
                                'data'  => array(
                                    'payment'   => $deposit,
                                    'net'       => $net
                                )
                            );
                        }
                    } else {
                        $codetext = implode(",", $ar_codetext);
                        $result = array(
                            'error' => 0,
                            'txt'   => "ทำรายการสำเร็จ",
                            'data'  => array(
                                'codetext'  => $codetext
                            )
                        );
                    }
                } else {
                    $result = array(
                        'error' => 1,
                        'txt'   => "ยอดโอนไม่ถูกต้อง",
                        'data'  => array(
                            'payment'   => $deposit,
                            'net'       => $net
                        )
                    );
                }
            }
        }

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

            $returns = $this->bill->create_bill();
            echo json_encode($returns);
        }
    }

    public function insert_deposit()
    {
        # code...
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $this->load->library('receipt');

            $returns = $this->receipt->create_deposit();
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
            // print_r($_POST);
            // print_r(json_decode($_POST['item_list']));die;
            $returns = $this->bill->update_billdata();
            echo json_encode($returns);
        }
    }

    public function update_deposit()
    {
        # code...
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $this->load->library('receipt');

            $returns = $this->receipt->update_deposit();
            echo json_encode($returns);
        }
    }

    public function update_deposit_price()
    {
        # code...
        $request = $_REQUEST;
        $id = $request['id'];
        $returns = $this->bill->get_deposit($id);
        echo json_encode($returns);
    }

    public function update_receipt()
    {
        # code...
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $this->load->library('receipt');

            $returns = $this->receipt->update_receipt();
            echo json_encode($returns);
        }
    }

    //  *
    //  * CRUD
    //  * delete
    //  * 
    //  * delete data
    //  *
    public function delete_deposit()
    {
        # code...
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $this->load->library('receipt');

            $returns = $this->receipt->delete_deposit();
            echo json_encode($returns);
        }
    }

    //  *
    //  * CRUD
    //  * delete
    //  * 
    //  * delete data
    //  *
    public function cancel_bill()
    {
        # code...
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $returns = $this->bill->cancel_bill();
            echo json_encode($returns);
        }
    }
}
