<div class="content">

    <input type="hidden" id="hidden_task_id">
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="section-tool d-flex flex-column flex-md-row justify-content-between">

            <div class="mb-1 mb-md-0">
                <div class="d-flex gap-2">
                    <div class="tool-btn">
                        <button type="button" class="btn-add btn">เพิ่มรายการ</button>
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
                            <th>รหัส</th>
                            <th>ชื่อ</th>
                            <th>Email</th>
                            <th>เบอร์ติดต่อ</th>
                            <th>ประเภท</th>
                            <th>วันอัพเดต</th>
                            <th class="hidden-sm">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>

        <!-- end row -->

    </div> <!-- end container-fluid -->

</div> <!-- end content -->

<!-- Modal -->
<?php require_once('component/modal_item.php') ?>
<!-- End Modal -->

<!-- Script -->
<?php require_once('script_crud.php') ?>
<?php require_once('script.php') ?>
<?php require_once('application/views/partials/e_script_print.php'); ?>
<!-- End Script -->

<script>
    $(document).ready(function() {
        //
        // # datatable_name = form script.php
        let datatable = $('#datatable')

        let last_columntable = datatable.find('th').length - 1
        let last_defaultSort = last_columntable - 1

        //
        // get data to data table
        //
        // # domain = form e_navbar.php
        // # url_moduleControl = form e_navbar.php
        // # dataTableHeight() = form e_navbar.php
        // # dataFillterFunc() = form e_navbar.php
        // # datatable_dom     = form e_navbar.php
        // # datatable_button  = form e_navbar.php
        //
        let urlname = new URL(path(url_moduleControl + '/get_dataTable'), domain);

        let table = datatable.DataTable({
            scrollY: dataTableHeight(),
            scrollCollapse: false,
            // responsive: true,
            // autoWidth: false,
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
                    "data": "EMPLOYEE.data.id",
                    "width": "60px",
                },
                {
                    "data": "EMPLOYEE.display",
                    "width": "120px",
                },
                {
                    "data": "EMPLOYEE.data.email",
                },
                {
                    "data": "EMPLOYEE.data.tel",
                },
                {
                    "data": "EMPLOYEE.data.worktype",
                },
                {
                    "data": {
                        _: 'USER_ACTIVE.display', // default show
                    }
                },
                {
                    "data": true,
                    "render": function(data, type, row, meta) {
                        let btn_view = `<a data-id="${data}" class="btn-view dropdown-item" href="#" data-code="${row.CODE}" ><i class="mdi mdi-magnify mr-2 text-info font-18 vertical-middle"></i>รายละเอียด</a>`
                        let btn_edit = `<a data-id="${data}" class="btn-edit dropdown-item" href="#"><i class="mdi mdi-wrench mr-2 text-warning font-18 vertical-middle"></i>แก้ไข</a>`
                        let btn_del = `<a data-id="${data}" class="btn-del dropdown-item" href="#" ><i class="mdi mdi-delete mr-2 text-danger font-18 vertical-middle"></i>ลบรายการ</a>`

                        if (row.STATUS.data.id == 1) {
                            btn_edit = ''
                        }

                        let table_action = `
                                <div class="btn-group dropdown">
                                    <a href="javascript: void(0);" class="table-action-btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-horizontal"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        ${btn_view}
                                        ${btn_edit}
                                        ${btn_del}
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

        //
        // revise
        /* frm_task_revise.addEventListener('submit', function(e) {
            e.preventDefault()

            let task_id = $('#hidden_task_id').val()
            let remark = $('#frm_task_revise').find('[name=modal_revise]').val()
            revise_data(task_id, remark)
        }) */

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





    })
</script>