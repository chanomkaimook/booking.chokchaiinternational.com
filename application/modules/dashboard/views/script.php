<script>
    const d = document

    const score_card = ".score_card"
    const content1 = '.card-box'
    const content2 = '.card-body'
    const content3 = '.chart-container'

    $(document).ready(function() {
        const j = $(document)

        get_dataAllScore()

        function get_dataAllScore() {

            cardLoading()

            get_dataTicketScore()
                .then((resp) => {
                    setTimeout(() => {
                        // console.log(resp)
                        draw_statusScore(resp.data)
                        draw_catagoryScore(resp.catagory)

                        if ($('#bar_operator').length) {
                            draw_operatorScore(resp.operator)
                        }

                        if ($('#datatable_operator_wrapper').length) {
                            reload_table()
                        } else {
                            draw_table()
                        }

                    }, 800);

                })
        }

        async function get_dataTicketScore() {
            let url = new URL(path(url_moduleControl + '/get_dataTicketScore'), domain)

            if (document.getElementById('hidden_userid').value) {
                url.searchParams.append('h_userid', document.getElementById('hidden_userid').value)
            }

            if (document.getElementById('hidden_datestart').value) {
                url.searchParams.append('dstart', document.getElementById('hidden_datestart').value)
            }
            if (document.getElementById('hidden_dateend').value) {
                url.searchParams.append('dend', document.getElementById('hidden_dateend').value)
            }

            let response = await fetch(url)
            let result = response.json()

            return result
        }



        j.on('click', 'button[data-target="#graph"]', function() {
            let barchart = d.getElementById('bar').style

            // check draw canvas from style.display = block
            if (!barchart.display) {
                draw_grap_bar()
            }

        })

        j.on('change', '#datestart-autoclose', function() {
            let result = convertDateDatabase($(this).val())
            $('#hidden_datestart').val(result)

            reload_dataAllScore()
        })
        j.on('change', '#dateend-autoclose', function() {
            let result = convertDateDatabase($(this).val())
            $('#hidden_dateend').val(result)

            reload_dataAllScore()
        })
        j.on('change', '[name=operator]', function() {
            let result = $(this).val()
            $('#hidden_userid').val(result)

            reload_dataAllScore(true)
        })

        // button filter
        j.on('click', '.tool_filter .btn', function() {
            let type = $(this).attr('data-type')

            // set style
            $('.tool_filter .btn').removeClass('active')

            let dateStart = $(this).attr('data-start')
            let dateEnd = $(this).attr('data-end')
            resetFilterRight(dateStart, dateEnd)

            // this code to set under resetFilterRight 
            // for add class active on class tool_filter
            $(this).addClass('active')
        })

        function reload_dataAllScore(command = false) {

            if ($('#datestart-autoclose').val() && $('#dateend-autoclose').val() || command == true) {
                resetFilterLeft()

                remove_grap()
                remove_operator()

                $('.collapse').collapse("hide")

                get_dataAllScore()
            }

            return false
        }

        /**
         * create data table
         *
         * @return void
         */
        function draw_table() {
            // clear remove
            let dt = '.table-responsive'
            let dtBody = $(dt).find('.table')
            cardLoadingRemove(dt)
            cardToggleDisplay(dtBody)

            //  get data
            //
            let urlname = new URL(path(url_moduleControl + '/get_dataTable'), domain);

            let table = $('#datatable_operator').DataTable({
                scrollY: '350px',
                scrollCollapse: false,
                responsive: false,
                autoWidth: false,
                lengthChange: false,
                ajax: {
                    url: urlname,
                    type: 'get',
                    dataType: 'json',
                    data: function(d) {

                        d.hidden_datestart = $('#hidden_datestart').val()
                        d.hidden_dateend = $('#hidden_dateend').val()
                        d.hidden_userid = $('#hidden_userid').val()
                    }
                },
                order: [
                    [0, 'desc']
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
                    },
                    {
                        "data": "CATAGORY_NAME",
                    },
                    {
                        "data": "WORKSTATUS_NAME",
                    },
                    {
                        "data": "SECTION_NAME"
                    },
                    {
                        "data": "MEMBER_NAME",
                    },
                    {
                        "data": 'ASSIGN_NAME',
                    },
                ],
            })

        }
    })

    function resetFilterLeft() {
        $('.tool_filter .btn').removeClass('active')
    }

    function resetFilterRight(dateStart = null, dateEnd = null) {
        $('#datestart-autoclose').val('')
        $('#dateend-autoclose').val('')

        $('#datestart-autoclose').datepicker('update', new Date(dateStart))
        $('#dateend-autoclose').datepicker('update', new Date(dateEnd))
        if (dateStart && dateEnd) {
            // $('#dateend-autoclose').val('')
        }
    }

    function reload_table() {
        // clear remove
        let dt = '.table-responsive'
        let dtBody = $(dt).find('.table')
        cardLoadingRemove(dt)
        cardToggleDisplay(dtBody)

        $('#datatable_operator').DataTable().ajax.reload()
    }


    // cardLoading()
    /** show loading on DOM
     * 
     */
    function cardLoading() {
        let score_div = '.score_card .card-box'
        let score_div_body = $(score_div).find('div')
        let operator_div = '.score_graph .chart-container'
        let operator_div_body = $(operator_div).find('canvas')
        let catagory_div = '.card-body .score_catagory'
        let catagory_div_body = $(catagory_div).find('.card-body')
        let table_div = '.table-responsive'
        let table_div_body = $(table_div).find('.table')

        $(score_div).prepend(loading)
        $(operator_div).prepend(loading)

        $(catagory_div).html(loading)
        $(table_div).prepend(loading)

        score_div_body.addClass('d-none')
        operator_div_body.addClass('d-none')
        table_div_body.addClass('d-none')
    }

    function cardLoadingRemove(div = null) {
        if (div) {
            $(div).find('.loading').remove()
        }
    }

    function cardToggleDisplay(div = null) {
        if (div.length) {
            if (div.hasClass('d-none')) {
                div.removeClass('d-none')
            } else {
                div.addClass('d-none')
            }
        }
    }
</script>