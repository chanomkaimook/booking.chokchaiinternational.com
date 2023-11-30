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
        $this->load->model(array('mdl_ticket'));
        $this->load->helper(array('my_calculate'));

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
        $this->load->model('mdl_staff');

        $optional['select'] = '
            staff.ID as ID,
            concat(employee.NAME," ",employee.LASTNAME) as staff_name
        ';

        $data['operator'] = $this->date_now;

        $data['today_s'] = $this->date_now;
        $data['today_e'] = $this->date_now;

        $data['week_s'] = $this->date_week_s;
        $data['week_e'] = $this->date_week_e;

        $data['date_month_s'] = $this->date_month_s;
        $data['date_month_e'] = $this->date_month_e;

        $data['date_year_s'] = $this->date_year_s;
        $data['date_year_e'] = $this->date_year_e;

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

    public function view()
    {
        echo "views";
    }
    public function insert()
    {
        echo "insert";
    }
    public function update()
    {
        echo "update";
    }
    public function delete()
    {
        echo "delete";
    }
    public function check()
    {
        echo "appreove and revise";
    }

    function get_dataTicketScore()
    {
        $result = array();
        $result_data = array(
            'waite'             => 0,
            'waite_percent'     => 0,

            'doing'             => 0,
            'doing_percent'     => 0,

            'success'           => 0,
            'success_percent'   => 0,

            'all'               => 0,
            'all_percent'       => 0,

            'defect'               => 0,
            'defect_percent'       => 0,

            'ticketavg'               => 0,
            'ticketavg_percent'       => 0,
        );

        $optional = [];

        // 
        // select count
        $sql_select = "";

        // 
        // split catagory
        $array_catagory = [];
        $data_catagory = $this->mdl_catagory->get_dataShow();
        if ($data_catagory) {
            foreach ($data_catagory as $row) {
                $array_catagory[$row->ID] = $row->NAME;
                $sql_select .= "count(case when ticket.CATAGORY_ID = '" . $row->ID . "' then 1 else null end) as count" . $row->ID . ",";
            }
        }

        $sql_select .= "count(case when ticket.WORKSTATUS_ID = 1 then 1 else null end) as countwaite,";
        $sql_select .= "count(case when ticket.WORKSTATUS_ID = 2 then 1 else null end) as countdoing,";
        $sql_select .= "count(case when ticket.WORKSTATUS_ID = 3 then 1 else null end) as countsuccess,";
        $sql_select .= "count(ticket.ID) as countall,";
        $sql_select .= "sum(ticket.DEFECT) as sumdefect,";

        // $optional['select'] = 'count(ticket.id) as tk_all,count(ticket.id) as tk_all';
        $optional['select'] = $sql_select;
        $optionalRange['select'] = $sql_select;
        $optionalAll['select'] = $sql_select;

        $dstart = date('Y-01-01');
        $dend = date('Y-m-d');

        if ($this->user_id) {
            $optional['where']['ticket_assign.staff_id'] = $this->user_id;
        }

        if ($this->date_start && $this->date_end) {
            $sqlwhere = "(
                CASE WHEN ticket.WORKSTATUS_ID = 3 
                THEN date(ticket.date_update) >= '" . $this->date_start . "' 
                and date(ticket.date_update) <= '" . $this->date_end . "'
                ELSE 
                date(ticket.date_starts) >= '" . $this->date_start . "' 
                and date(ticket.date_starts) <= '" . $this->date_end . "' 
                END)";

            $dstart = $this->date_start;
            $dend = $this->date_end;
            $optional['where'][$sqlwhere] = null;
            $optionalRange['where'][$sqlwhere] = null;
        } else {
            $sqlwhere = "(
                CASE WHEN ticket.WORKSTATUS_ID = 3 
                THEN date(ticket.date_update) >= '" . $dstart . "' 
                and date(ticket.date_update) <= '" . $dend . "'
                ELSE 
                date(ticket.date_starts) >= '" . $dstart . "' 
                and date(ticket.date_starts) <= '" . $dend . "'
                END)";

            $optional['where'][$sqlwhere] = null;
            $optionalRange['where'][$sqlwhere] = null;
        }

        $date_starts = date_create($dstart);
        $date_end = date_create($dend);

        $data_ticket = [];

        $data_ticketFilter = $this->mdl_ticket->get_dataShow(null, $optional, 'row');
        // $data_ticket['filter'] = $data_ticketFilter;
        // echo $this->db->last_query();
        // exit;
        $data_ticketRange = $this->mdl_ticket->get_dataShow(null, $optionalRange, 'row');
        $data_ticketAll = $this->mdl_ticket->get_dataShow(null, $optionalAll, 'row');
        // $data_ticket['all'] = $data_ticketAll;

        if ($data_ticketFilter && $data_ticketAll) {

            // waite
            $result_data['waite'] = $data_ticketFilter->countwaite;
            $result_data['waite_percent'] = get_percentFromTotal((int)$data_ticketFilter->countwaite, (int)$data_ticketFilter->countall);

            // doing
            $result_data['doing'] = $data_ticketFilter->countdoing;
            $result_data['doing_percent'] = get_percentFromTotal((int)$data_ticketFilter->countdoing, (int)$data_ticketFilter->countall);

            // succcess
            $result_data['success'] = $data_ticketFilter->countsuccess;
            $result_data['success_percent'] = get_percentFromTotal((int)$data_ticketFilter->countsuccess, (int)$data_ticketFilter->countall);

            // all
            $result_data['all'] = $data_ticketFilter->countall;
            $result_data['all_percent'] = get_percentFromTotal((int)$data_ticketFilter->countall, (int)$data_ticketAll->countall);

            // defect
            $result_data['defect'] = (float) $data_ticketFilter->sumdefect;
            $result_data['defect_percent'] = get_percentFromTotal((float)$data_ticketFilter->sumdefect, (float)$data_ticketAll->sumdefect);

            // average
            $datediff = date_diff($date_starts, $date_end);
            $datecount = $datediff->format("%a") + 1;

            $totalDays = (int) $datecount;
            $totalTickets = (int) $data_ticketFilter->countsuccess;
            $totalTicketsAvg = $totalTickets / $totalDays;
            $totalTicketsAll = (int) $data_ticketAll->countsuccess;
            $totalTicketsAvgAll = $totalTicketsAll / $totalDays;

            // echo $totalTicketsAvg;
            // echo $totalTicketsAvgAll;
            // echo $totalTickets;
            // echo "-".$totalTicketsAll;
            // echo "<br>";
            // echo $totalDays;
            $result_data['ticketavg'] = number_format($totalTicketsAvg, 2);
            $result_data['ticketavg_percent'] = get_percentFromTotal($totalTicketsAvg, $totalTicketsAvgAll);

            $result['data'] = $result_data;

            $item = [];
            if (count($array_catagory)) {
                foreach ($array_catagory as $catkey => $catval) {
                    $text = "count" . $catkey;
                    $data_ticket_cat = [];
                    $data_ticket_cat = array(
                        'id'    => $catkey,
                        'name'  => $catval,
                        'total' => $data_ticketFilter->$text,
                        'all'   => $data_ticketRange->$text
                    );

                    $item[] = $data_ticket_cat;
                }
            }
            $result['catagory'] = $item;
        }

        echo json_encode($result);
    }

    function get_dataCatagoryScore()
    {
        # code...
        if ($this->input->server('REQUEST_METHOD')) {
            $request = $_REQUEST;
            $optional = [];

            $data_catagory = $this->mdl_catagory->get_dataShow(null, $optional);

            # for data ticket

            $item = [];

            if (count($data_catagory)) {
                $array_catagory = [];
                $sql_select = '';

                foreach ($data_catagory as $catkey => $catrow) {
                    $array_catagory[$catrow->ID] = $catrow->NAME;
                    $sql_select .= "count(case when ticket.CATAGORY_ID = '" . $catrow->ID . "' then 1 else null end) as count" . $catrow->ID . ",";
                }

                # set where default
                $optional['select'] = $sql_select;

                if ($this->user_id) {
                    $optional['where']['ticket_assign.staff_id'] = $this->user_id;
                }
                if ($this->date_start) {
                    $optional['where']['date(ticket.date_starts) >='] = $this->date_start;
                }
                if ($this->date_end) {
                    $optional['where']['date(ticket.date_starts) <='] = $this->date_end;
                }
                if ($request['workstatus']) {
                    $optional['where']['ticket.workstatus_id'] = $request['workstatus'];
                }

                $data_ticket = $this->mdl_ticket->get_dataShow(null, $optional, 'row');

                if (count($array_catagory)) {
                    foreach ($array_catagory as $catkey => $catval) {
                        $text = "count" . $catkey;
                        $data_ticket_cat = [];
                        $data_ticket_cat = array(
                            'id'    => $catkey,
                            'name'  => $catval,
                            'total' => $data_ticket->$text,
                        );

                        $item[] = $data_ticket_cat;
                    }
                }
            }

            $result['catagory'] = $item;
            // $returns = $data_ticket;
            echo json_encode($result);
        }
    }

    public function get_dataTable()
    {
        $this->load->helper('my_date');

        $request = $_REQUEST;

        $optional['where'] = array(
            'ticket.workstatus_id in(1,2)' => null
        );

        if ($this->user_id) {
            $optional['where']['ticket_assign.staff_id'] = $this->user_id;
        }
        if ($this->date_start) {
            $optional['where']['date(ticket.date_starts) >='] = $this->date_start;
        }
        if ($this->date_end) {
            $optional['where']['date(ticket.date_starts) <='] = $this->date_end;
        }

        $data = $this->mdl_ticket->get_dataShowWork(null, $optional);
        $dataall = $this->mdl_ticket->get_dataShowWork();

        $data_result = [];

        if ($data) {
            foreach ($data as $row) {
                $dom_workstatus = workstatus($row->WORKSTATUS_ID, $row->WORKSTATUS_NAME);

                $member_split = explode(" ", $row->MEMBER_NAME);
                $nickname = $member_split[0];

                $assign_split = explode(" ", $row->NAME);
                $technical = $assign_split[0];

                $sub_data = [];

                $sub_data['ID'] = $row->ID;
                $sub_data['CODE'] = textShow($row->CODE);
                $sub_data['TASK'] = textShow($row->TASK);
                $sub_data['CATAGORY_NAME'] = textShow($row->CATAGORY_NAME);
                $sub_data['WORKSTATUS_NAME'] = $dom_workstatus;
                $sub_data['MEMBER_NAME'] = textShow($nickname);
                $sub_data['SECTION_NAME'] = textShow($row->MEMBER_SECTION);
                $sub_data['ASSIGN_NAME'] = $technical;

                $data_result[] = $sub_data;
            }
        }

        $result = array(
            "recordsTotal"      =>     count($data),
            "recordsFiltered"   =>     count($dataall),
            "data"              =>     $data_result
        );

        echo json_encode($result);
    }
}
