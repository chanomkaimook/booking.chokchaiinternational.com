<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ctl_ticket extends MY_Controller
{

    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mdl_section');
        $this->load->model('mdl_catagory');
        $this->load->model('mdl_ticket');
        $this->load->model('mdl_role_focus');
        $this->load->library(array('ticket'));

        $this->middleware();

        // setting
        $this->model = $this->mdl_ticket;
    }

    public function index()
    {
        $data['catagory'] = $this->mdl_catagory->get_dataShow();

        $this->template->set_layout('lay_datatable');
        $this->template->title('งานที่ต้องทำ');
        $this->template->set_partial(
            'footerscript',
            'partials/script/page',
            array(
                'data'  => array(
                    '<script src="' . base_url('') . 'asset/js/pages/scrollbar.init.js"></script>',
                )
            )
        );
        $this->template->set_partial('footerscript', 'partials/script/scrollbar');
        $this->template->build('/ticket/index', $data);
    }

    /**
     * form add data
     *
     * @return void
     */
    public function formadd()
    {
        $data['section'] = $this->mdl_section->get_data();
        $data['catagory'] = $this->mdl_catagory->get_dataShow();

        $this->template->set_layout('lay_main');
        $this->template->title('สร้างใบงาน');
        $this->template->build('/ticket/form_add', $data);
    }

    public function get_data()
    {
        $request = $_REQUEST;
        $code = $request['code'];

        $optional = array(
            'where' => array(
                'code'  => $code
            )
        );

        $row = $this->mdl_ticket->get_data(null, $optional);

        $dom_workstatus = workstatus($row[0]->WORKSTATUS_ID, $row[0]->WORKSTATUS_NAME);

        $result = $row;
        $result[0]->WORKSTATUS_DISPLAY = $dom_workstatus;

        echo json_encode($result);
    }

    public function get_dataTable()
    {
        $this->load->helper('my_date');

        $request = $_REQUEST;

        $hidden_start = "";
        $hidden_end = "";

        if (textShow($request['hidden_datestart'])) {
            $hidden_start = textShow($request['hidden_datestart']);
        }
        if (textShow($request['hidden_dateend'])) {
            $hidden_end = textShow($request['hidden_dateend']);
        }

        $optional['order_by'] = array(
            'ticket.workstatus_id' => 'asc',
            'ticket.id' => 'desc',
        );

        if (textShow($request['search']['value'])) {
            $optional['or_where'] = "
            (ticket.code like '%" . $request['search']['value'] . "%' 
            OR ticket.member_name like '%" . $request['search']['value'] . "%' 
            OR ticket.section_name like '%" . $request['search']['value'] . "%' 
            OR ticket.catagory_name like '%" . $request['search']['value'] . "%'
            OR ticket_assign.name like '%" . $request['search']['value'] . "%' )
            ";
        }
        $data = $this->mdl_ticket->get_dataShowWork(null, $optional);
        $dataall = $this->mdl_ticket->count_dataShowWork();

        $data_result = [];

        if ($data) {
            foreach ($data as $row) {

                if ($row->DATE_UPDATE) {
                    $query_date = $row->DATE_UPDATE;
                    $query_user = "(แก้) " . whois('staff_id', $row->USER_UPDATE);
                } else {
                    $query_date = $row->DATE_STARTS;
                    $query_user =  whois('staff_id', $row->USER_STARTS);
                }

                $member_split = explode(" ", $row->MEMBER_NAME);
                $nickname = $member_split[0];

                $assign_split = explode(" ", $row->NAME);
                $technical = $assign_split[0];

                $dom_workstatus = workstatus($row->WORKSTATUS_ID, $row->WORKSTATUS_NAME);

                $sub_data = [];

                $sub_data['ID'] = $row->ID;
                $sub_data['CODE'] = textShow($row->CODE);
                $sub_data['TASK'] = textShow($row->TASK);
                $sub_data['DEFECT'] = array(
                    "display"    =>  textShow($row->DEFECT_REMARK),
                    "data"       => array(
                        'user_id'       => $row->USER_DEFECT,
                        'user_name'     => whois('staff_id', $row->USER_DEFECT),
                        'date'          => toThaiDateTimeString($row->DATE_DEFECT, 'datetime'),
                        'defect'          => $row->DEFECT,
                    )
                );
                $sub_data['MEMBER'] = array(
                    "display"    =>  textShow($nickname),
                    "data"       => array(
                        'id'         => $row->MEMBER_ID,
                        'name'       => $row->MEMBER_NAME,
                        'position'   => $row->MEMBER_POSITION,
                        'department' => $row->MEMBER_DEPARTMENT,
                        'section'    => $row->MEMBER_SECTION,
                        'email'      => $row->MEMBER_EMAIL,
                    )
                );

                $sub_data['ASSIGN'] = array(
                    "display"   => textShow($technical),
                    "data"      =>  array(
                        'id'    => $row->STAFF_ID,
                        'name'    => $row->NAME,
                    )
                );

                $sub_data['SECTION'] = array(
                    "display"   => textShow($row->SECTION_NAME),
                    "data"      =>  array(
                        'id'    => $row->SECTION_ID,
                    )
                );

                $sub_data['APPROVE'] = array(
                    "display"   => textShow($technical),
                    "data"      =>  array(
                        'id'    => $row->APPROVE_ID,
                        'name'    => $row->APPROVE_NAME,
                    )
                );

                $sub_data['WORKSTATUS'] = array(
                    "display"   => $dom_workstatus,
                    "data"      =>  array(
                        'id'    => $row->WORKSTATUS_ID,
                        'name'    => $row->WORKSTATUS_NAME,
                    ),
                );

                $sub_data['CATAGORY'] = array(
                    "display"   => $row->CATAGORY_NAME,
                    "data"      =>  array(
                        'id'    => $row->CATAGORY_ID,
                    ),
                );

                $optionnalcomment['select'] = "count(ticket_comment.id) as countid";
                $optionnalcomment['where'] = array(
                    'ticket.code'   => $row->CODE
                );
                $data_comment = $this->mdl_ticketcomment->get_data(null, $optionnalcomment, 'row');
                // $sub_data['COMMENT'] = textShow($data_comment->id) ? $data_comment->id : null ;
                $sub_data['COMMENT'] = textShow($data_comment->countid);

                #
                # hashtag list
                $text_hashtag = [];
                if ($row->HASHTAG) {

                    #
                    # ถ้าตรงกัน แสดงเพียง hashtag เดียว
                    if ($row->HASHTAG == $row->HASHTAG_BY) {
                        $text_hashtag[] = $row->HASHTAG;
                    } else {
                        $text_hashtag = $this->ticket->get_hashtaglist($row->HASHTAG, $row->ID);
                    }
                }
                $sub_data['HASHTAG'] = $text_hashtag;

                $sub_data['BEGIN_DATE'] = array(
                    "display"   =>  $row->BEGIN_DATE ? toThaiDateTimeString($row->BEGIN_DATE, 'date') : '',
                    "timestamp" => $row->BEGIN_DATE ? date('Y-m-d', strtotime($row->BEGIN_DATE)) : ''
                );
                $sub_data['END_DATE'] = array(
                    "display"   =>  $row->END_DATE ? toThaiDateTimeString($row->END_DATE, 'date') : '',
                    "timestamp" => $row->END_DATE ? date('Y-m-d', strtotime($row->END_DATE)) : ''
                );

                $sub_data['TOTAL'] = $row->TOTAL;
                $sub_data['CORRECTION'] = $row->CORRECTION;
                $sub_data['PROBLEMS'] = $row->PROBLEMS;
                $sub_data['REMARK'] = $row->REMARK;

                $sub_data['CREATER'] = $query_user;

                $sub_data['DATE_STARTS'] = array(
                    "display"   => toThaiDateTimeString($query_date, 'datetime'),
                    "timestamp" => date('Y-m-d H:i:s', strtotime($query_date))
                );

                $data_result[] = $sub_data;
            }
        }

        $result = array(
            "recordsTotal"      =>     count($data),
            "recordsFiltered"   =>     $dataall,
            "data"              =>     $data_result
        );

        echo json_encode($result);
    }

    #
    # CRUD
    #
    # insert data
    public function insert_data()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $data = $this->input->post();

            $returns = $this->ticket->insert_ticket($data);

            echo json_encode($returns);
        }
    }

    #
    # insert data
    public function revise_data()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $data = $this->input->post();

            $returns = $this->ticket->revise_ticket($data);

            echo json_encode($returns);
        }
    }

    //
    // update
    public function update_data()
    {
        # code...
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $data = $this->input->post();

            if ($this->input->post('method') == "update") {

                $returns = $this->ticket->update_ticket($data);
            } else {

                $returns = $this->ticket->update_ticketWork($data);
            }

            echo json_encode($returns);
        }
    }
    public function update_ticketDefect()
    {
        # code...
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $data = $this->input->post();

            $returns = $this->ticket->update_ticketDefect($data);

            echo json_encode($returns);
        }
    }

    //
    // delete
    public function delete_data()
    {
        # code...
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $returns = $this->mdl_ticket->delete_data();

            echo json_encode($returns);
        }
    }

    #
    # COMMENT
    #
    # insert comment data
    public function insert_comment_data()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $data = $this->input->post();

            $returns = $this->ticket->insert_comment_ticket($data);

            echo json_encode($returns);
        }
    }

    #
    # get comment data
    public function get_commentdata()
    {
        $request = $_REQUEST;
        $code = $request['code'];

        $optional = array(
            'where' => array(
                'ticket.code'  => $code
            )
        );

        $data = $this->mdl_ticketcomment->get_data(null, $optional);

        if ($data) {
            foreach ($data as $key => $row) {
                $texttime = toThaiDateTimeString($row->DATE_STARTS);
                $time = date('H:i', strtotime($row->DATE_STARTS));
                $username = whois('staff_id', $row->STAFF_ID);
                $data[$key]->USERNAME = $username;
                $data[$key]->TEXTIME = $texttime . ' ' . $time;
            }
        }
        $result = $data;

        echo json_encode($result);
    }
}
