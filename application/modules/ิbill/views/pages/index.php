<div class="content">
    <input type="hidden" id="hidden_task_id">
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="section-tool d-flex flex-column flex-md-row justify-content-between">

            <div class="mb-1 mb-md-0">
                <div class="d-flex gap-2">
                    <div class="tool-btn">
                        <button type="button" class="btn-add btn"><?= mb_ucfirst($this->lang->line('_form_btn_add')) ?></button>
                    </div>
                </div>
            </div>

            <div class="">
                <?php require_once('application/views/partials/e_filter_base.php'); ?>
            </div>

        </div>
        <style>
            .truncate {
                 max-width: 200px;
             }
        </style>
        <div class="">
            <div class="card-box">
                <table id="datatable" class="table table-hover m-0 table-actions-bar dt-responsive dataTable no-footer dtr-inline" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?= mb_ucfirst($this->lang->line('_name')) ?></th>
                            <th><?= mb_ucfirst($this->lang->line('_status')) ?></th>
                            <th><?= mb_ucfirst($this->lang->line('_display')) ?></th>
                            <th><?= mb_ucfirst($this->lang->line('_usernow')) ?></th>
                            <th><?= mb_ucfirst($this->lang->line('_datenow')) ?></th>
                            <th class="hidden-sm"><?= mb_ucfirst($this->lang->line('_action')) ?></th>
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
        let datatable = $(datatable_name)

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
            autoWidth: false,
            // searchDelay: datatable_searchdelay_time,
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
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
                    targets: last_columntable
                },
                {
                    "targets": [2, 3, 4],
                    "className": "truncate"
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
                    "data": "NAME",
                    "width": "",
                    "createdCell": function(td, cellData, rowData, row, col) {
                        $(td).css('min-width', '150px')
                    },
                    "render": function(data, type, row, meta) {

                        let task = `${data}`
                        if (row.CODE) {
                            task += `<a href=# data-target="#modal_ticket" class="text-info" data-toggle="modal" data-code="${row.CODE}">#${row.CODE}</a> `
                        }

                        return task
                    }
                },
                {
                    "data": "WORKSTATUS.display",
                },
                {
                    "data": "STATUS.display",
                },
                {
                    "data": {
                        _: 'USER_ACTIVE.display', // default show
                    }
                },
                {
                    "data": {
                        _: 'DATE_ACTIVE.display', // default show
                        sort: 'DATE_ACTIVE.timestamp'
                    }
                },
                {
                    "data": "ID",
                    "render": function(data, type, row, meta) {
                        let btn_view = `<a data-id="${data}" class="btn-view text-capitalize dropdown-item" href="#" data-code="${row.CODE}" ><i class="mdi mdi-magnify mr-2 text-info font-18 vertical-middle"></i>${table_column_view[setlang]}</a>`
                        let btn_edit = `<a data-id="${data}" class="btn-edit text-capitalize dropdown-item" href="#"><i class="mdi mdi-wrench mr-2 text-warning font-18 vertical-middle"></i>${table_column_edit[setlang]}</a>`
                        let btn_del = `<a data-id="${data}" class="btn-del text-capitalize dropdown-item" href="#" ><i class="mdi mdi-delete mr-2 text-danger font-18 vertical-middle"></i>${table_column_del[setlang]}</a>`

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

    })
</script>