<?php
defined('BASEPATH') or exit('No direct script access allowed');

// require APPPATH . '/libraries/API_Controller.php';

class Ctl_calendar extends MY_Controller
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
                'need'       => ['calendar.view'],
            )
        );

        // setting
        $this->model = $this->$modelname;
        $this->title = 'ปฏิทินรายการจอง';
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

        $this->template->set_layout('lay_calendar');
        $this->template->title($this->title);
        $this->template->build('bills/calendar', $data);
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

                $dom_workstatus = workstatus($row->WORKSTATUS, 'status');
                $dom_status = status_offview($row->STATUS_OFFVIEW);

                $sub_data = [];

                $sub_data['ID'] = $row->ID;
                $sub_data['CODE'] = textNull($row->CODE);
                $sub_data['NAME'] = textNull($row->NAME);

                $sub_data['TICKET'] = array(
                    "display"   => textNull($row->TICKET_NAME),
                    "data"      =>  array(
                        'id'    => $row->TICKET_ID,
                    ),
                );

                $sub_data['DIVISION'] = array(
                    "display"   => textNull($row->DIVISION_NAME),
                    "data"      =>  array(
                        'id'    => $row->DIVISION_ID,
                    ),
                );

                $sub_data['PRICE'] = array(
                    // "display"   => number_format($row->PRICE),
                    "display"   => textMoney($row->PRICE,'int'),
                    "data"      =>  array(
                        'value'    => $row->PRICE,
                    ),
                );
                
                $sub_data['WORKSTATUS'] = array(
                    "display"   => $dom_workstatus,
                    "data"      =>  array(
                        'id'    => $row->WORKSTATUS,
                    ),
                );

                $sub_data['STATUS'] = array(
                    "display"   => $dom_status,
                    "data"   => array(
                        'id'    => $row->STATUS_OFFVIEW,
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

        if($data && $item_id){
            if(is_array($data)){
                $data['PRICE_DISPLAY'] = textMoney($data['PRICE']);
                $data['STATUS_TEXT'] = status_offview($data['STATUS_OFFVIEW']);
            }else{
                $data->PRICE_DISPLAY = textMoney($data->PRICE);
                $data->STATUS_TEXT = status_offview($data->STATUS_OFFVIEW);
            }
        }

        $result = $data;
        echo json_encode($result);
    }
    public function get_dataDisplay()
    {
        $request = $_REQUEST;
        $data = $this->model->get_dataDisplay();

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
            // print_r($_POST);
            // print_r(json_decode($_POST['item_list']));die;
            $returns = $this->bill->update_billdata();
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
