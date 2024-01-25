<?php
defined('BASEPATH') or exit('No direct script access allowed');

// require APPPATH . '/libraries/API_Controller.php';

class Ctl_customer extends MY_Controller
{

    private $model;
    private $title;

    public function __construct()
    {
        parent::__construct();
        $modelname = 'mdl_customer';
        $this->load->model(array('information/mdl_customer'));

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
        $this->title = 'จัดการข้อมูลลูกค้า';
    }

    public function index()
    {
        $this->template->set_layout('lay_datatable');
        $this->template->title($this->title);
        $this->template->build('customer/index');
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
        $this->load->model('bill/mdl_bill');
        $this->load->helper('my_date');

        $request = $_REQUEST;

        $data = $this->model->get_dataShow();
        $count = $this->model->get_data_all();

        $data_result = [];

        if ($data) {
            foreach ($data as $row) {

                $optional_bill['select'] = 'count(bill.id) as total';
                $optional_bill['where'] = array(
                    'customer_id'   => $row->ID
                );
                $r_total = $this->mdl_bill->get_dataShow(null,$optional_bill,'row');

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
                $sub_data['NAME'] = textNull($row->NAME);
                $sub_data['VISIT'] = $r_total->total;

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
                    "display"   => toThaiDateTimeString($query_date, 'datetime'),
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

        if ($data && $item_id) {
            $this->load->model('information/mdl_customer_address');

            if (is_array($data)) {
                $optional['where'] = array(
                    'customer_id'   => $data['ID']
                );
                $data['CUS_ADDRESS'] = $this->mdl_customer_address->get_dataShow(null, $optional);
                $data['STATUS_TEXT'] = status_offview($data['STATUS_OFFVIEW']);
            } else {
                $optional['where'] = array(
                    'customer_id'   => $data->ID
                );
                $data->CUS_ADDRESS = $this->mdl_customer_address->get_dataShow(null, $optional);
                $data->STATUS_TEXT = status_offview($data->STATUS_OFFVIEW);
            }
        }
        $result = $data;
        echo json_encode($result);
    }
    public function get_data_address()
    {
        $this->load->model(array('information/mdl_customer_address'));

        $result = "";

        $request = $_REQUEST;
        $item_id = $request['id'];
        if ($item_id) {
            $optional['where'] = array(
                'customer_id'   => $item_id
            );
            $data = $this->mdl_customer_address->get_data(null, $optional);

            $result = $data;
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
