<?php
defined('BASEPATH') or exit('No direct script access allowed');

// require APPPATH . '/libraries/API_Controller.php';

class event_calendar extends CI_Controller
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

        $this->template->set_layout('lay_public');
        $this->template->title($this->title);
        $this->template->build('bills/calendarpublic', $data);
        // $this->load->view('bills/calendarpublic', $data);
    }

    public function get_dataCalendar()
    {
        $request = $_REQUEST;
        $this->load->model('deposit/mdl_deposit');

        $data_array = $this->model->get_dataDisplayCalendar();
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
                    $data[$key]->DEPOSIT = $deposit;

                    $net_pure = $this->get_data_todo_1($item_id, $data_array[$key]);
                    $data[$key]->NET_PURE = textMoney($net_pure);
                }
            }
        }
        $result = $data;
        echo json_encode($result);
    }

    public function get_data_todo_1($item_id, $data)
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

    public function get_dataDisplay()
    {
        $request = $_REQUEST;
        $item_id = $request['id'] ? $request['id'] : null;
        $data = $this->model->get_dataDisplay($item_id);

        $result = $data;
        echo json_encode($result);
    }
}
