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
                // 'need'       => ['bill','quotation'],
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
        ";
        $data = $this->model->get_dataShow(null, $optional);
        $count = $this->model->get_data_all();

        $data_result = [];

        if ($data) {
            $no = 1;
            foreach ($data as $row) {

                $user_active_id = $row->USER_STARTS ? $row->USER_STARTS : $row->USER_UPDATE;

                if ($row->DATE_UPDATE) {
                    $query_date = $row->DATE_UPDATE;
                    $user_active = "(แก้) " . whois($row->USER_UPDATE);
                } else {
                    $query_date = $row->DATE_STARTS;
                    $user_active =  whois($row->USER_STARTS);
                }

                $dom_status = status_offview($row->STATUS_OFFVIEW);

                $sub_data = [];

                $sub_data['ID'] = $row->ID;
                $sub_data['NO'] = $no;

                $sub_data['BILL'] = array(
                    "display"   => $row->CUSTOMER_NAME,
                    "data"      =>  array(
                        'id'    => $row->ID,
                        'code'    => textShow($row->CODE),
                        'total_unit'    => textShow($row->TOTAL_UNIT),
                        'net'    => textShow($row->NET),
                        'date_order'    => textShow($row->DATE_ORDER),
                        'date_booking'    => textShow($row->BOOKING_DATE),
                    ),
                );

                $sub_data['DATE_ORDER'] = array(
                    "display"   => convertdate_fromDB($row->DATE_ORDER),
                    "timestamp" => date('Y-m-d H:i:s', strtotime($row->DATE_ORDER))
                );
                $sub_data['BOOKING_DATE'] = array(
                    "display"   => convertdate_fromDB($row->BOOKING_DATE),
                    "timestamp" => date('Y-m-d H:i:s', strtotime($row->BOOKING_DATE))
                );

                $sub_data['DEPOSIT'] = array(
                    "display"   => $row->dp_deposit,
                    "data"      =>  array(
                        'id'    => $row->dp_id,
                        'codetext'    => textShow($row->dp_codetext),
                    ),
                );

                $sub_data['CUSTOMER'] = array(
                    "display"   => $row->CUSTOMER_NAME,
                    "data"      =>  array(
                        'id'    => $row->CUSTOMER_ID,
                    ),
                );

                
                $sub_data['BANK'] = array(
                    "data"      =>  array(
                        'scb'    => "ไทยพาณิชย์",
                        'scb_code'    => "123456798",
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
