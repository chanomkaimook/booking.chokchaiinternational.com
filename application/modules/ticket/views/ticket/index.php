<?php
if (is_Mobile()) {
?>
    <style>
        .dropdown-menu-right {
            right: auto !important;
            left: 0px !important;
        }
    </style>
<?php
}
?>
<div class="content">

    <input type="hidden" id="hidden_task_id">
    <input type="hidden" id="hidden_user_id" value="<?= $this->session->userdata('user_code'); ?>">
    <input type="hidden" id="hidden_user_rolelevel" value="<?= $this->session->userdata('role_level'); ?>">

    <!-- Start Content-->
    <div class="container-fluid">
        <div class="section-tool d-flex flex-column flex-md-row justify-content-between">

            <div class="mb-1 mb-md-0">
                <div class="d-flex gap-2">
                    <div class="tool-btn">

                    </div>
                </div>
            </div>

            <div class="">
                <?php require_once('application/views/partials/e_filter_base.php'); ?>
            </div>

        </div>
        <div class="">
            <div class="card-box">
                <table id="datatable" class="table table-hover m-0 table-actions-bar dt-responsive dataTable no-footer dtr-inline" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Task</th>
                            <th>ประเภท</th>
                            <th>สถานะ</th>
                            <th>ฝ่าย</th>
                            <th>โดย</th>
                            <th>OP</th>
                            <th class="hidden-sm">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>

            <style>
                @media only screen and (min-width:1458px) {
                    /* .content-page {
                        min-height: 97vh;
                    } */
                }
            </style>
        </div>

        <!-- end row -->

    </div> <!-- end container-fluid -->

</div> <!-- end content -->

<!-- Modal -->
<?php require_once('component/modal_task_correction.php') ?>
<?php require_once('component/modal_task_defect.php') ?>
<?php require_once('component/modal_task_remark.php') ?>
<?php require_once('component/modal_task_revise.php') ?>
<?php require_once('component/modal_ticket.php') ?>
<?php require_once('component/modal_defect.php') ?>
<?php require_once('component/modal_task_comment.php') ?>
<!-- End Modal -->

<!-- Script -->
<?php require_once('script_crud.php') ?>
<?php require_once('script.php') ?>
<?php require_once('application/views/partials/e_script_print.php'); ?>
<!-- End Script -->

<script>
    const d = document
    const frm_task_remark = d.getElementById('frm_task_remark')
    const frm_task_correction = d.getElementById('frm_task_correction')
    const frm_task_revise = d.getElementById('frm_task_revise')
    const frm_task_comment = d.getElementById('frm_task_comment')

    $(document).ready(function() {

        let datatable = $('#datatable')
        let last_columntable = datatable.find('th').length - 1
        let last_defaultSort = last_columntable - 1

        //  get data
        //
        let urlname = new URL(path('ticket/ctl_ticket/get_dataTable'), domain);

        let table = datatable.DataTable({
            processing: true,
            serverSide: true,
            scrollY: dataTableHeight(),
            scrollCollapse: false,
            responsive: true,
            autoWidth: false,
            searchDelay: datatable_searchdelay_time,
            ajax: {
                url: urlname,
                type: 'get',
                dataType: 'json',
                data: dataFillterFunc()
            },
            order: [],
            columnDefs: [{
                    responsivePriority: 1,
                    targets: 0
                },

                {
                    responsivePriority: 2,
                    targets: 2
                },
            ],
            columns: [{
                    "data": "CODE",
                    "width": "60px",
                    "render": function(data, type, row, meta) {
                        let code = data
                        if (!code) {
                            code = ""
                        }
                        return "<b>#" + code + "</b>"
                    }
                },
                {
                    "data": "TASK",
                    "width": "34rem",
                    "createdCell": function(td, cellData, rowData, row, col) {
                        $(td).css('min-width', '150px')
                    },
                    "render": function(data, type, row, meta) {
                        let task = ''

                        if (row.COMMENT) {
                            task += '<a href=# data-target="#modal_task_comment" data-toggle="modal" data-id="' + row.ID + '" data-code="' + row.CODE + '" >'
                            task += '<i class="mdi mdi-chat-processing text-success lead"></i>'
                            task += '</a>'
                        }
                        if (row.DEFECT.data.defect) {
                            task = '<a href=# data-target="#modal_defect" data-toggle="modal" data-id="' + row.ID + '" data-task="' + row.DEFECT.display + '" data-user="' + row.DEFECT.data.user_name + '" data-date="' + row.DEFECT.data.date + '" >'
                            task += '<i class="mdi mdi-alert-circle text-danger lead"></i>'
                            task += '</a>'
                        }

                        task += `
                            ${data}
                            `
                        if (row.HASHTAG) {
                            row.HASHTAG.forEach(function(item, index) {
                                task += `<a href=# data-target="#modal_ticket" class="text-info" data-toggle="modal" data-code="${item}">#${item}</a> `
                            })
                        }

                        return task
                    }
                },
                {
                    "data": "CATAGORY.display",
                },
                {
                    "data": "WORKSTATUS.display",
                },
                {
                    "data": "MEMBER.data.section"
                },
                {
                    "data": {
                        _: 'MEMBER.display',
                    }
                },
                {
                    "data": 'ASSIGN.display',
                },
                {
                    "data": "ID",
                    "render": function(data, type, row, meta) {
                        let btn_defect = ''
                        let btn_approve = ''
                        let btn_reference = ''
                        let btn_edit = ''
                        let btn_comment = ''
                        let btn_delete = ''

                        if (row.WORKSTATUS.data.id != 2) { // doing
                            btn_edit = `<a data-id="${data}" class="btn-inprocess dropdown-item" href="#"><i class="mdi mdi-wrench mr-2 font-18 text-warning vertical-middle"></i>กำลังทำ</a>`
                        }

                        if (row.WORKSTATUS.data.id != 4) { // delete
                            btn_reference = `<a data-id="${data}" class="btn-revise dropdown-item" href="#" data-toggle="modal" data-target="#modal_task_revise" data-code="${row.CODE}"><i class="mdi mdi-target mr-2 text-primary font-18 vertical-middle"></i>อ้างอิง</a>`
                            btn_comment = `<a data-id="${data}" class="btn-comment dropdown-item" href="#" data-toggle="modal" data-target="#modal_task_comment" data-code="${row.CODE}"><i class="mdi mdi-chat-processing mr-2 text-primary font-18 vertical-middle"></i>ความเห็น</a>`
                        }

                        if (row.WORKSTATUS.data.id == 3) { // success
                            btn_edit = ""
                        } else {
                            btn_approve = `<a data-id="${data}" class="btn-done dropdown-item" href="#" data-toggle="modal" data-target="#modal_task_correction" data-code="${row.CODE}"><i class="mdi mdi-check-all mr-2 text-success font-18 vertical-middle"></i>ปิดงาน</a>`
                        }

                        if ($('#hidden_user_rolelevel').val() <= 10) {
                            btn_defect = `<a data-id="${data}" class="btn-defect dropdown-item" href="#" data-toggle="modal" data-target="#modal_task_defect" data-code="${row.CODE}"><i class="mdi mdi-alert-box mr-2 text-pink font-18 vertical-middle"></i>บกพร่อง</a>`
                        }

                        btn_delete = `<a data-id="${data}" class="btn-del dropdown-item" href="#" data-toggle="modal" data-target="#modal_task_remark"><i class="mdi mdi-delete mr-2 text-danger font-18 vertical-middle"></i>ลบรายการ</a>`

                        // # Helpdesk permit 
                        if ($('#hidden_user_rolelevel').val() == 11 && $('#hidden_user_id').val() != row.ASSIGN.data.id) {
                            btn_approve = ''
                            btn_defect = ''
                            btn_edit = ''
                            btn_reference = ''
                            btn_delete = ''
                        }

                        let table_action = `
                                <div class="btn-group dropdown">
                                    <a href="javascript: void(0);" class="table-action-btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-horizontal"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a data-id="${data}" class="dropdown-item" href="#" data-target="#modal_ticket" data-toggle="modal" data-code="${row.CODE}" ><i class="mdi mdi-magnify mr-2 text-info font-18 vertical-middle"></i>รายละเอียด</a>
                                        ${btn_defect}
                                        ${btn_approve}
                                        ${btn_reference}
                                        ${btn_edit}
                                        ${btn_comment}
                                        ${btn_delete}
                                    </div>
                                </div>
                            `
                        return table_action
                    },
                    "width": "60px",
                    "orderable": false
                }
            ],

            dom: datatable_dom,
            buttons: datatable_button,

        })

        $(document).on('click', '.btn-del,.btn-done,.btn-revise,.btn-defect', function() {
            let id = $(this).attr('data-id')
            let code = $(this).attr('data-code')

            get_dataTicket(code)
                .then((resp) => {
                    let modal = $('#modal_task_correction')
                    modal.find('.modal-body .loading').remove()
                    modal.find('.modal-body-content').show()

                    modal.find('[name=modal_correction]').val(resp[0].CORRECTION)
                })

            $('#hidden_task_id').val(id)
        })

        $(document).on('click', 'a[data-target="#modal_ticket"]', function() {
            let id = $(this).attr('data-id')
            let code = $(this).attr('data-code')
            let codetext = $(this).text()

            if (id) {
                codetext = "ใบงานที่ #" + code
            }
            $('.ticket_code').html(codetext)
            modal_ticket_show(code) // function on modal_ticket

            $('#hidden_task_id').val(id)
        })

        $(document).on('click', 'a[data-target="#modal_defect"]', function() {
            let id = $(this).attr('data-id')
            let task = $(this).attr('data-task')
            let user = $(this).attr('data-user')
            let date = $(this).attr('data-date')

            let m = $('#modal_defect')
            m.find('.remark').html(task)
            m.find('.user_active').html(user)
            m.find('.date_active').html(date)

            $('#hidden_task_id').val(id)
        })

        $(document).on('click', '.btn-comment,a[data-target="#modal_task_comment"]', function() {
            let id = $(this).attr('data-id')
            let code = $(this).attr('data-code')

            modal_comment_show(code)
            $('.section_timeline').addClass('d-none')

            $('#hidden_task_id').val(id)
        })

        /**
         * event delete defect
         */
        $(document).on('click', '.btn_del', function() {

            Swal.fire(swal_setConfirm())
                .then((data) => {
                    if (data.value === true) {
                        let id = $('#hidden_task_id').val()

                        let body = new FormData()
                        body.append('id', id)
                        body.append('defect', false)
                        body.append('defect_remark', false)
                        async_update_datadefect(body)
                            .then((resp) => {
                                if (resp.error != 1) {
                                    swalalert()
                                        .then(
                                            dataReload(false)
                                        )

                                } else {
                                    swalalert('error', false)
                                }
                            })

                    }
                })
        })

        /**
         * 
         * CRUD
         * 
         */
        //
        // update from modal_ticket
        let frm_ticket = document.getElementById('frm_ticket')
        frm_ticket.addEventListener('submit', function(e) {
            e.preventDefault()

            let dataForm = submitUpdateTicket()

            // update from script crud
            async_update_data(dataForm)
                .then((data) => {
                    dataReload()
                })
        })

        //
        // update done
        frm_task_correction.addEventListener('submit', function(e) {
            e.preventDefault()

            let task_id = $('#hidden_task_id').val()
            let remark = $('#frm_task_correction').find('[name=modal_correction]').val()

            // 1= waite
            // 2= doing
            // 3= done
            ticket_updateWork(task_id, remark, 3)
        })
        //
        // update defect
        frm_task_defect.addEventListener('submit', function(e) {
            e.preventDefault()

            let task_id = $('#hidden_task_id').val()
            let remark = $('#frm_task_defect').find('[name=modal_defect]').val()

            ticket_updateDefect(task_id, remark)
        })
        //
        // update in process
        $(document).on('click', '.btn-inprocess', function(e) {
            e.preventDefault()

            let task_id = $(this).attr('data-id')
            let remark = ""

            // 1= waite
            // 2= doing
            // 3= done
            ticket_updateWork(task_id, remark, 2)
        })

        //
        // update to waite
        $(document).on('click', '.btn-waite', function(e) {
            e.preventDefault()

            let task_id = $(this).attr('data-id')
            let remark = ""

            // 1= waite
            // 2= doing
            // 3= done
            ticket_updateWork(task_id, remark, 1)
        })

        function ticket_updateWork(task_id, remark, workstatus_id) {

            let data = new FormData()
            data.append('id', task_id)
            data.append('correction', remark)
            data.append('workstatus_id', workstatus_id)

            async_update_data(data)
                .then((data) => {
                    dataReload()
                })
        }

        function ticket_updateDefect(task_id, remark = null) {

            let data = new FormData()
            data.append('id', task_id)
            data.append('defect_remark', remark)

            async_update_datadefect(data)
                .then((data) => {
                    dataReload()
                })
        }

        //
        // revise
        frm_task_revise.addEventListener('submit', function(e) {
            e.preventDefault()

            let task_id = $('#hidden_task_id').val()
            let remark = $('#frm_task_revise').find('[name=modal_revise]').val()
            revise_data(task_id, remark)
        })

        // 
        // revise data form
        function revise_data(task_id = null, remark = null) {

            let data = new FormData();

            data.append('ticket_id', task_id)
            data.append('revise', remark)

            async_revise_data(data)
                .then((resp) => {
                    if (resp.error == 1) {
                        swalalert('error', resp.txt, {
                            auto: false
                        })
                    } else {
                        Swal.fire({
                            type: 'success',
                            title: 'สำเร็จ',
                            text: resp.txt,
                            timer: swal_autoClose,
                        }).then((result) => {

                            resetForm()

                            dataReload()

                        })
                    }
                });

        }

        //
        // delete
        frm_task_remark.addEventListener('submit', function(e) {
            e.preventDefault()

            let task_id = $('#hidden_task_id').val()
            let remark = $('#frm_task_remark').find('[name=modal_remark]').val()
            delete_data(task_id, remark)
        })

        function delete_data(task_id, remark) {
            async_delete_data(task_id, remark)
                .then((data) => {
                    dataReload()
                })
        }

        //
        // comment
        frm_task_comment.addEventListener('submit', function(e) {
            e.preventDefault()

            let task_id = $('#hidden_task_id').val()
            let comment = $('#frm_task_comment').find('[name=modal_comment]').val()
            comment_data(task_id, comment)
        })

        // 
        // comment data form
        function comment_data(task_id = null, comment = null) {

            let data = new FormData();

            data.append('ticket_id', task_id)
            data.append('task', comment)

            async_comment_data(data)
                .then((resp) => {
                    if (resp.error == 1) {
                        swalalert('error', resp.txt, {
                            auto: false
                        })
                    } else {
                        Swal.fire({
                            type: 'success',
                            title: 'สำเร็จ',
                            text: resp.txt,
                            timer: swal_autoClose,
                        }).then((result) => {

                            resetForm()

                            dataReload()

                        })
                    }
                });

        }

        /**
         * 
         * FUNCTION
         * 
         */
        function dataReload(refresh = true) {
            modalHide()

            if (refresh == false) {
                datatable.DataTable().ajax.reload(null, false)
            } else {
                datatable.DataTable().ajax.reload()
            }
        }
    })
</script>