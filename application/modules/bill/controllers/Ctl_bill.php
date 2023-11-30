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
        $modelname = 'mdl_page';
        $this->load->model(array('mdl_page'));

        
        $this->middleware();

        // setting
        $this->model = $this->$modelname;
        $this->title = 'ใบขอรับบริการ';
    }

    public function index()
    {
        $this->auth = false;

        $this->template->set_layout('lay_datatable');
        $this->template->title($this->title);
        $this->template->build('index');
    }

    public function nopermit()
    {
        $this->template->set_layout('lay_datatable');
        $this->template->title($this->title);
        $this->template->build('index');
    }
}
