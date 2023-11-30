<?php
defined('BASEPATH') or exit('No direct script access allowed');

// require APPPATH . '/libraries/API_Controller.php';

class Ctl_quotation extends MY_Controller
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
        $this->title = 'ใบเสนอราคา';
    }

    public function index()
    {
        $this->template->set_layout('lay_main');
        $this->template->title($this->title);
        $this->template->build('index');
    }
}
