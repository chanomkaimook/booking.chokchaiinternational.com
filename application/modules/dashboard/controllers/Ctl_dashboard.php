<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ctl_dashboard extends MY_Controller
{

    private $detect;
    private $user_id;

    private $date_now;

    private $date_start;
    private $date_end;


    public function __construct()
    {
        parent::__construct();

        $this->middleware(
            array(
                'access'    => [
                    // 'index'     => ['bill'],
                    // 'index'     => ['bill','bill.view'],
                    'view'      => ['bill.view','bill.a','bill.insert']
                    // 'view'      => ['bill.view','bill.a','bill.insert','quatation']
                    // 'view'      => ['quotation']
                ],
                // 'need'       => ['workorder'],
                // 'need'       => ['bill'],
                // 'need'       => ['bill','quotation'],
                // 'need'       => ['quotation'],

                'except'    => [
                    // 'index'      => ['workorder','bill.view','bill'],
                    // 'view'      => [],
                    // 'view'      => ['quotation','b','c','d'],
                    // 'view'      => ['bill.a','b','c','d'],
                ]
            )
        );

        include FCPATH . "mobile_detect.php";
        $this->detect = new Mobile_Detect();

        $request = $_REQUEST;
        if ($request['hidden_userid']) {
            $this->user_id = $request['hidden_userid'];
        }
        if ($request['h_userid']) {
            $this->user_id = $request['h_userid'];
        }

        $this->date_start = $request['dstart'];
        $this->date_end = $request['dend'];

        $this->date_now = date('Y-m-d');
        // $this->date_now = '2023-07-05';

        $dateRange_fromDate = dateRange_fromDate($this->date_now);
        $this->date_week_s = $dateRange_fromDate['start'];
        $this->date_week_e = $dateRange_fromDate['end'];

        $monthRange_fromDate = monthRange_fromDate($this->date_now);
        $this->date_month_s = $monthRange_fromDate['start'];
        $this->date_month_e = $monthRange_fromDate['end'];

        $yearRange_fromDate = yearRange_fromDate($this->date_now);
        $this->date_year_s = $yearRange_fromDate['start'];
        $this->date_year_e = $yearRange_fromDate['end'];

        /* if ($this->detect->isMobile()) {
            // Any mobile device.
            $base_item_image = $base_item_image_mobile . "/";
            $base_promotion_image = $base_promotion_image_mobile . "/";
        } */
    }

    public function index()
    {

        $data['is_mobile'] = $this->detect->isMobile();
        $data['barchart_height'] = $this->detect->isMobile() ? 750 : 950;

        $this->template->set_layout('lay_dashboard');
        $this->template->title('Dashboard');

        if (check_admin()) {
            $this->template->build('dashboard_admin', $data);
        } else {
            $this->template->build('dashboard', $data);
        }
    }
}
