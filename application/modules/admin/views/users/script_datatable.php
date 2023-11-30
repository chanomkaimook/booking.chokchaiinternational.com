<script>
    function getData() {
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
        let urlname = new URL(path(url_moduleControl + '/fetch_data'), domain);

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
                    "targets": [2, 3],
                    "className": "truncate"
                },
            ],
            columns: [{
                    "data": "USERNAME",
                    "width": "60px",
                    "render": function(data, type, row, meta) {
                        let code = data

                        code = `<a href=# class="text-info">
                        ${data}
                        </a> `

                        if (!code) {
                            code = ""
                        }
                        return "<b>" + code + "</b>"
                    }
                },
                {
                    "data": "NAME",
                    "width": "",
                    "createdCell": function(td, cellData, rowData, row, col) {
                        $(td).css('min-width', '150px')
                    }
                },
                {
                    "data": "LASTNAME",
                    "width": "",
                    "createdCell": function(td, cellData, rowData, row, col) {
                        $(td).css('min-width', '150px')
                    }
                },
                {
                    "data": "STATUS.display",
                },
                {
                    "data": {
                        _: 'DATE_STARTS.display', // default show
                        sort: 'DATE_ACTIVE.timestamp'
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
                        let btn_view = `<a data-id="${data}" class="btn-view text-capitalize dropdown-item" href="#"><i class="mdi mdi-magnify mr-2 text-info font-18 vertical-middle"></i>${table_column_view[setlang]}</a>`
                        let btn_edit = `<a data-id="${data}" class="btn-edit text-capitalize dropdown-item" href="#"><i class="mdi mdi-wrench mr-2 text-warning font-18 vertical-middle"></i>${table_column_edit[setlang]}</a>`
                        let btn_del = `<a data-id="${data}" class="btn-del text-capitalize dropdown-item" href="#" ><i class="mdi mdi-delete mr-2 text-danger font-18 vertical-middle"></i>${table_column_del[setlang]}</a>`

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
            //	data load after 
            "rowCallback": function(row, data) {
                $('td:eq(0)', row).addClass('btn-view')
                    .attr('data-id', data.ID)
            },

            dom: datatable_dom,
            buttons: datatable_button,
        })

        // table.buttons(0, 0).remove();
        // table.button().add(0,'print');
    }
</script>