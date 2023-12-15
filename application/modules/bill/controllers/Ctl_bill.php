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
        $this->load->model(array('bill/mdl_bill', 'information/mdl_round'));
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
        $data['round'] = $this->mdl_round->get_dataShow(null, $optional);

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
        $optional = [];
        $optional_detail = [];
        $item_code = $this->input->get('code');
        $data = [];

        if ($item_code) {
            $optional['where'] = array(
                'bill.code'  => $item_code
            );
            $data['bill'] = $this->mdl_bill->get_data(null, $optional,'row_array');

            $optional_detail['where'] = array(
                'bill_detail.bill_code'  => $item_code
            );
            
        } else {
            $item_id = $this->input->get('id');
            $data['bill'] = $this->mdl_bill->get_data(textNull($item_id),null,'row_array');

            $optional_detail['where'] = array(
                'bill_detail.bill_id'  => textNull($item_id)
            );   
        }
        $data['bill_detail'] = $this->mdl_bill_detail->get_data(null, $optional_detail,'result_array');
        /* echo "<pre>";
        print_r($data['bill']);
        print_r($data['bill_detail']);
        die; */
        $this->template->set_layout('lay_main');
        $this->template->title($this->title);
        $this->template->build('bills/document_bill', $data);
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

            // print_r($_POST);
            // print_r(json_decode($_POST['item_list']));
            $returns = $this->bill->create_bill();
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
