<script>
    function getData() {
        // console.log( $("#hidden_statuspayment").val())
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
        /* data: dataFillterFunc([{
            name: 'column',
            value: {
                0: 'code',
                1: 'name',
                2: 'workstatus',
                3: 'status_offview',
                4: 'user_active', // find staff to do
                5: 'date_active' // find date time to do
            }
        }, ]) */
        let urlname = new URL(path(url_moduleControl + '/get_dataTable'), domain);
        let r = document.querySelector('#hidden_statuspayment').value
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
                data: dataFillterFunc([{
                        name: 'hidden_statuspayment',
                    },
                    {
                        name: 'hidden_statusbill', 
                    },
                    {
                        name: 'hidden_datetype', 
                    },
                ])
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
                    "targets": [0, 1],
                    "className": "truncate"
                },
            ],
            columns: [{
                    "data": "CODE",
                    "render": function(data, type, row, meta) {
                        let code = data
                        let url_doc_bill = new URL(path(url_moduleControl + '/document'), domain)
                        url_doc_bill.searchParams.append('code', code)

                        code = `<a href=${url_doc_bill} target=_blank class="text-info">
                        #${data}
                        </a> `

                        if (!code) {
                            code = ""
                        }
                        return "<b>" + code + "</b>"
                    }
                },
                {
                    "data": "CUSTOMER.display",
                    "width": "100",
                    "createdCell": function(td, cellData, rowData, row, col) {
                        $(td).css('min-width', '150px')
                    }
                },
                {
                    "data": "CUSTOMER.data.total",
                },
                {
                    "data": {
                        _: "BOOKING.display",
                        sort: 'BOOKING.timestamp'
                    }
                },
                {
                    "data": {
                        _: 'PAYMENT.display', // default show
                    }
                },
                {
                    "data": {
                        _: 'COMPLETE.display', // default show
                    }
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
                        let url_doc_bill = new URL(path(url_moduleControl + '/document'), domain)
                        url_doc_bill.searchParams.append('code', row.CODE)

                        let btn_view = `<a href="${url_doc_bill}" target=_blank class="text-capitalize dropdown-item" ><i class="mdi mdi-magnify mr-2 text-info font-18 vertical-middle"></i>${table_column_view[setlang]}</a>`
                        let btn_del = ''

                        let table_action = `
                                <div class="btn-group dropdown">
                                    <a href="javascript: void(0);" class="table-action-btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-horizontal"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        ${btn_view}
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

        // table.buttons(0, 0).remove();
        // table.button().add(0,'print');
    }
</script>