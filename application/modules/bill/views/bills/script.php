<script>
    /**
     * 
     * Function and event
     * function require script_crud.php
     * setting variable on below this page
     * 
     */
    /**
     * 
     * adjust code begin to zone Function
     * and zone Event on jquery script
     * adjust base code begin to zone Function
     * 
     */
    //  *
    //  * Dom
    //  * setting variable
    //  *
    const d = document
    const datatable_name = '#datatable'

    //  *
    //  * Form
    //  * setting variable
    //  *
    const form_name = '#frm'
    const form_hidden_id = '[name=frm_hidden_id]'
    const form_button_btn_view = '#modal_view .btn-view'
    const form_button_btn_edit = '#modal_view .btn-edit'
    const form_button_btn_add = '.btn-add'
    const form_button_btn_submit = '#modal_view button[type=submit]'
    const form_button_btn_del = '.btn-del'

    //  *
    //  * Modal
    //  * setting variable
    //  *
    let modal = '.modal'
    let modal_body = '.modal .modal-body'
    let modal_view_name = '#modal_view'
    let modal_body_view = '.modal .modal-body-view'
    let modal_body_form = '.modal .modal-body-form'


    $(document).ready(function() {

        //  =========================
        //  =========================
        //  Event
        //  =========================
        //  =========================

        //  *
        //  * Form
        //  * click button submit
        //  * 
        //  * call function submit data on form
        //  * #async_insert_data() = script_crud.php
        //  * #async_update_data() = script_crud.php
        //  *
        $(d).on('submit', form_name, function(e) {
            e.preventDefault()

            let f = $(modal_body_form)
            let item_id = $('#modal_view').find(form_hidden_id).val()

            let data = $(form_name).serializeArray()

            let item_list = []

            step()

            async function step() {
                await new Promise((resolve, reject) => {

                    let list = $('#list_item tbody').find('tr')

                    if (list.length) {
                        let totalprice = 0.00
                        $.each(list, function(index, item) {
                            let item_select = $(item).find('select[name=item_list] option:selected')
                            if (item_select.val()) {
                                let bill_item_id = item_select.val()
                                let bill_item_name = item_select.attr('data-name')
                                let bill_item_price = item_select.attr('data-price')
                                let bill_item_qty = $(item).find('input[name=item_qty]').val()
                                let bill_item_discount = $(item).find('input[name=item_discount]').val()

                                resolve(
                                    item_list.push({
                                        id: bill_item_id,
                                        name: bill_item_name,
                                        price: bill_item_price,
                                        discount: formatMoney(bill_item_discount),
                                        total: bill_item_qty
                                    })
                                )
                            }
                        })
                    }
                })


                data.push({
                    'name': 'bank_name',
                    'value': $(modal).find('#bank option:selected').text()
                })

                data.push({
                    'name': 'customer',
                    'value': $(modal).find('[name=customer]').val()
                })

                // 
                // argument for get_cartData
                data.push({
                    'name': 'pay',
                    'value': $(modal).find('[name=deposit]').val()
                })
                data.push({
                    'name': 'item_data',
                    'value': JSON.stringify(item_list)
                })

                await new Promise((resolve, reject) => {

                    modalLoading()

                    let func

                    if (item_id) {

                        if ($('.calendar').val()) {
                            let booking_date_array = []
                            $.each($('.calendar'), function(index, item) {
                                let booking_date = $(item).val();
                                split_date_order = booking_date.split("/")
                                let data_booking_date = split_date_order[2] + "-" + split_date_order[1] + "-" + split_date_order[0]

                                data.push({
                                'name': 'bookingdate[]',
                                'value': data_booking_date,
                            })
                            })
                        }
                        if ($('[name=date_order]').val()) {
                            let date_order = $('[name=date_order]').val();
                            set_date_order = date_order.split("/")
                            let new_date_order = set_date_order[2] + "-" + set_date_order[1] + "-" + set_date_order[0]

                            data.push({
                                'name': 'date_order',
                                'value': new_date_order,
                            })
                        }
                        async_update_data(item_id, data)
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
                                        effect_after_event('update');

                                        modalHide()
                                    })
                                }
                            });

                        resolve(
                            modalLoading_clear()
                        )

                    } else {

                        if ($('.calendar').val()) {
                            let booking_date_array = []
                            $.each($('.calendar'), function(index, item) {
                                var dateTypeVar = $(item).datepicker('getDate');

                                data.push({
                                    'name': 'bookingdate[]',
                                    'value': $.datepicker.formatDate('yy-mm-dd', dateTypeVar)
                                })
                            })


                        }
                        if ($('[name=date_order]').val()) {
                            var dateTypeVar = $('[name=date_order]').datepicker('getDate');
                            $('[name=date_order]').datepicker({
                                format: 'yy-mm-dd'
                            })
                            data.push({
                                'name': 'date_order',
                                'value': $.datepicker.formatDate('yy-mm-dd', dateTypeVar)
                            })
                        }
                        if ($('[name=deposit_date]').val()) {
                            var dateTypeVar = $('[name=deposit_date]').datepicker('getDate');
                            $('[name=deposit_date]').datepicker({
                                format: 'yy-mm-dd'
                            })
                            data.push({
                                'name': 'deposit_date',
                                'value': $.datepicker.formatDate('yy-mm-dd', dateTypeVar)
                            })
                        }
                        if ($('[name=pos_date]').val()) {
                            var dateTypeVar = $('[name=pos_date]').datepicker('getDate');
                            $('[name=pos_date]').datepicker({
                                format: 'yy-mm-dd'
                            })
                            data.push({
                                'name': 'pos_date',
                                'value': $.datepicker.formatDate('yy-mm-dd', dateTypeVar)
                            })
                        }

                        async_insert_data(data)
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
                                        effect_after_event('insert');
                                    })
                                }
                            });

                        resolve(
                            modalLoading_clear()
                        )
                    }
                })

            }
        })

        //  *
        //  * CRUD
        //  * click button view
        //  * 
        //  * call function view data
        //  *
        $(d).on('click', form_button_btn_view, function(e) {
            e.preventDefault()

            let id = $(this).attr('data-id')
            view_data(id)

            $(form_name).find(form_hidden_id).val(id)
            $(form_name).find(form_button_btn_edit).attr('data-id', id)
        })

        //  *
        //  * CRUD
        //  * click button edit
        //  * 
        //  * call function open form for edit data
        //  *
        $(d).on('click', form_button_btn_edit, function(e) {
            e.preventDefault()

            let id = $(form_name).find(form_hidden_id).val()

            edit_data(id)


        })

        //
        // for button edit from document page
        $(d).on('click', '.sector_button-edit .btn-edit', function(e) {
            e.preventDefault()

            let id = $(this).attr('data-id')
            edit_data(id)

            $(form_name).find(form_hidden_id).val(id)
        })

        //  *
        //  * CRUD
        //  * click button add
        //  * 
        //  * call function open form for add data
        //  *
        $(d).on('click', form_button_btn_add, function(e) {
            e.preventDefault()

            add_data()

            $(form_name).find(form_hidden_id).val('')
        })

        //  *
        //  * CRUD
        //  * click button delete
        //  * 
        //  * call function form delete
        //  *
        $(d).on('click', form_button_btn_del, function(e) {
            e.preventDefault()

            let id = $(this).attr('data-id')
            delete_data(id)
        })

        //  *
        //  * Modal
        //  * Modal Hide
        //  * 
        //  * call reset form when modal hide
        //  *
        $(modal).on('hidden.bs.modal', function(e) {
            e.preventDefault()

            resetForm()
        })

        //  *
        //  * Modal
        //  * Modal Show
        //  * 
        //  * add DOM loading when modal to show
        //  *
        $(modal).on('show.bs.modal', function() {
            // modalLoading()
        })
        //  =========================
        //  =========================
        //  End Event
        //  =========================
        //  =========================


    })

    //  =========================
    //  =========================
    //  Function
    //  Todo adjust code default here
    //  =========================
    //  =========================

    //  *
    //  * Modal
    //  * view
    //  * 
    //  * display data
    //  * @data = array[key=>[column=>value]]
    //  *
    function modalActive(data = [], action = 'view') {
        if (data) {
            if (action != 'add' && data.NAME) {
                let header = data.NAME
                $(modal).find('.modal_text_header').html(header)
            }

            switch (action) {
                case 'view':
                    $(modal_body_view)
                        .find('.label_1').text(data.NAME).end()

                    break
                case 'edit':
                    $(modal_body_form)
                        .find('[name=label_1]').val(data.WORKSTATUS).end()

                    break
                default:
                    break
            }

            $(modal_view_name).modal()

            modalLayout(action)
        }
    }

    //  *
    //  * Modal
    //  * layout
    //  * 
    //  * layout DOM for show on modal
    //  *
    function modalLayout(action = null) {
        let btn_edit = $(form_button_btn_edit)
        let btn_submit = $(form_button_btn_submit)

        if (action == 'view') {
            $(modal_body_view).removeClass('d-none')
            $(modal_body_form).addClass('d-none')

            btn_edit.show()
            btn_submit.hide()
        } else {
            $(modal_body_view).addClass('d-none')
            $(modal_body_form).removeClass('d-none')

            btn_edit.hide()
            btn_submit.show()
        }
    }

    // 
    // ############
    // Function Quotation
    // ############
    // 
    function modalActive_quotation(action = 'view', data = []) {
        let modal_q_name = '#modal_view',
            itemcode = data.CODE ? data.CODE : $('#data-bill_code').text()
        let header
        if (action == 'view') {
            header = itemcode
        } else {
            let header = "แก้ไข " + itemcode
        }
        $(modal_q_name).find('.modal_text_header').html(header)

        // status for bill complete
        let status_complete
        if(data.COMPLETE_ID == 3){
            status_complete = 1
        }

        if (action == 'view') {

            // when bill success
            // hide button edit
            if(status_complete){
                $('.modal .btn-edit').addClass('d-none')
            }else{
                $('.modal .btn-edit').removeClass('d-none')
            }

            let dsplit = data.DATE_ORDER.split("-");
            date_order = dsplit[2] + "/" + dsplit[1] + "/" + dsplit[0];

            $(modal_q_name)
                .find('.customer').text(data.CUSTOMER_NAME).end()
                .find('.customer_address').text(data.CUSTOMER_ADDRESS_ADDRESS).end()
                .find('.agent_name').text(data.AGENT_NAME).end()
                .find('.agent_contact').text(data.AGENT_CONTACT).end()
                .find('.round').text(data.ROUND_NAME).end()
                .find('.remark').text(data.REMARK).end()
                .find('.total_pay_view').text(data.DEPOSIT).end()

            $(modal_q_name)
                // .find('.bookingdate').text(booking).end()
                .find('.date_order').text(date_order).end()

            //
            // item list
            if (data.booking_list) {
                data.booking_list.forEach(function(item, index) {
                    let bsplit
                    // console.log(item)
                    if (item.BOOKING_DATE) {
                        bsplit = item.BOOKING_DATE.split("-");
                        booking_date = bsplit[2] + "/" + bsplit[1] + "/" + bsplit[0];

                        let td_1 = `<td></td>`
                        let td_2 = `<td>${item.ROUND_NAME}</td>`
                        let td_3 = `<td>${booking_date}</td>`
                        let td_4 = `<td>${formatMoney(item.BOOKING_TOTAL,0)}</td>`
                        let tr = `<tr>${td_1}${td_2}${td_3}${td_4}</tr>`

                        $("#list_booking_view tbody").append(tr)

                        cal_item_list()
                    }
                })
            }

            //
            // item list
            if (data.item_list) {
                data.item_list.forEach(function(item, index) {
                    // console.log(item)
                    if (item.ITEM_ID) {
                        let td_1 = `<td></td>`
                        let td_2 = `<td class="text-left">${item.DESCRIPTION}</td>`
                        let td_3 = `<td class="td_item_qty">${item.QUANTITY}</td>`
                        let td_4 = `<td class="td_item_price_unit">${formatMoney(item.PRICE_UNIT)}</td>`
                        let td_5 = `<td class="td_item_price">${formatMoney(item.PRICE)}</td>`
                        let tr = `<tr>${td_1}${td_2}${td_3}${td_4}${td_5}</tr>`

                        $("#list_item_view tbody").append(tr)

                        cal_item_list()
                    }
                })
            }

        }

        $(modal_q_name)
            .find('[name=customer]').val(data.CUSTOMER_NAME).end()
            .find('[name=customer_address]').val(data.CUSTOMER_ADDRESS_ADDRESS).end()
            .find('[name=agent_name]').val(data.AGENT_NAME).end()
            .find('[name=agent_contact]').val(data.AGENT_CONTACT).end()
            .find('[name=round]').val(data.ROUND_ID).end()
            .find('[name=remark]').text(data.REMARK).end()
            .find('#bank').attr('disabled', 'disabled').end()
            .find('[name=deposit]').attr('disabled', 'disabled').end()
            .find('[name=deposit_date]').attr('disabled', 'disabled').end()
            .find('[name=pos_date]').attr('disabled', 'disabled').end()

        /* if (data.BOOKING_DATE) {
            let booking = data.BOOKING_DATE
            $(modal_q_name)
                .find('[name=bookingdate]').datepicker("setDate", new Date(booking));
        } */
        if (data.DATE_ORDER) {
            let date_order = data.DATE_ORDER
            $(modal_q_name)
                .find('[name=date_order]').datepicker("setDate", new Date(date_order));
        }

        //
        // item list
        if (data.booking_list) {
            data.booking_list.forEach(function(item, index) {
                let bsplit
                if (item.BOOKING_DATE) {
                    /* bsplit = item.BOOKING_DATE.split("-");
                    booking_date = bsplit[2] + "/" + bsplit[1] + "/" + bsplit[0];

                    let td_1 = `<td></td>`
                    let td_2 = `<td>${item.ROUND_NAME}</td>`
                    let td_3 = `<td>${booking_date}</td>`
                    let td_4 = `<td>${formatMoney(item.BOOKING_TOTAL)}</td>`
                    let tr = `<tr>${td_1}${td_2}${td_3}${td_4}</tr>`

                    $("#list_booking tbody").append(tr) */

                    add_html_list_booking()
                    bsplit = item.BOOKING_DATE.split("-");
                    booking_date = bsplit[2] + "/" + bsplit[1] + "/" + bsplit[0];

                    // $("[name=round]:last").val(item.ROUND_ID)
                    // $("[name=bookingdate_temp]:last").val(item.BOOKING_DATE)
                    $("#list_booking tbody .class-round:last").val(item.ROUND_ID)
                    $("#list_booking tbody .class-bookingdate:last").val(booking_date)
                    $("#list_booking tbody .class-bookingtotal:last").val(item.BOOKING_TOTAL)
                }
            })
        }

        //
        // item list
        if (data.item_list) {
            data.item_list.forEach(function(item, index) {
                // console.log(item)
                if (item.ITEM_ID) {
                    add_html_list_item()

                    $("[name=item_list]:last").val(item.ITEM_ID)
                    $("[name=item_qty]:last").val(item.QUANTITY)

                    // from "form/form.php"
                    cal_item_list()
                }
            })
        }

        modalLayout(action)
    }

    //	format number and float (.00) return string!! 
    function formatMoney(number, decPlaces = 2) {
        // const r = number.toString().replace(/\B(?=(\d{3})+(?!\d))/g,",")
        const convertNumber = Number(number)
        const convertFloat = Math.abs(Number(convertNumber)).toFixed(decPlaces)
        const convertComma = convertFloat.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")

        return convertComma
    }
    //  =========================
    //  =========================
    //  End Function
    //  =========================
    //  =========================

    //  =========================
    //  =========================
    //  Base Function
    //  =========================
    //  =========================

    //  *
    //  * Form
    //  * view
    //  * 
    //  * get data
    //  * #async_get_data() = script_crud.php
    //  *
    function view_data(item_id = 0) {
        // item_id = 0
        async_get_data(item_id)
            .then((resp) => {
                modalActive(resp, 'view')
            })
            .then(() => {
                modalLoading_clear()
            })
    }

    //  *
    //  * Form
    //  * add
    //  * 
    //  * open form add data
    //  *
    function add_data() {
        modalActive([], 'add')
        modalLoading_clear()
    }

    //  *
    //  * Form
    //  * edit
    //  * 
    //  * open form edit data
    //  * #async_get_data() = script_crud.php
    //  *
    function edit_data(item_id = 0) {
        // item_id = 0
        async_get_data(item_id)
            .then((resp) => {
                modalActive(resp, 'edit')
            })
            .then(() => {
                modalLoading_clear()
            })
    }

    //  *
    //  * Form
    //  * delete
    //  * 
    //  * confirm to delete data
    //  * #swal_setConfirmInput() = e_navbar.php
    //  *
    function delete_data(item_id) {
        Swal.fire(
                swal_setConfirmInput()
                // swal_setConfirm()
            )
            .then((result) => {
                if (!result.dismiss) {
                    let remark = result.value.trim
                    confirm_delete(item_id, remark)
                }
            })

    }

    //  *
    //  * Form
    //  * delete
    //  * 
    //  * delete data
    //  * #async_delete_data() = script_crud.php
    //  *
    function confirm_delete(item_id = null, remark = null) {

        if (item_id) {
            async_delete_data(item_id, remark)
                .then((data) => {

                    if (data.error == 0) {
                        swalalert()
                    } else {
                        swalalert('error', resp.txt, {
                            auto: false
                        })
                    }

                    dataReload(false)
                })
        }

    }

    //  *
    //  * DataTable
    //  * reload
    //  * 
    //  @param bool $reload = reload datatable
    //  * refresh data on datatable
    //  *
    function dataReload(reload = true) {
        modalHide()

        if (reload == false) {
            $(datatable_name).DataTable().ajax.reload(null, false)
        } else {
            $(datatable_name).DataTable().ajax.reload()
        }
    }

    //  *
    //  * Form
    //  * reset
    //  * 
    //  * reset data all form
    //  *
    function resetForm() {
        let form = document.querySelectorAll("form")

        form.forEach((item, key) => {
            document.getElementsByTagName('form')[key].reset();
        })

        $(modal).find('.modal_text_header').html('')

        $(modal).find('#cus_id').attr('disabled', 'disabled')

        // clear element
        $('.text_promotion').addClass('d-none')
        $('.text_promotion div').empty()
        $(modal)
            .find('[name=item_net]').val('').end()
            .find('.total_price').html('').end()
            .find('.total_discount').html('').end()
            .find('.total_net').html('').end()
            .find('.total_pay').html('').end()
            .find('.total_unit').html('').end()
            .find('.status_payment').html('').end()
            .find('#list_booking tbody').html('').end()
            .find('#list_item tbody').html('').end()
            .find('[name=remark]').html('').end()
            .find('[name=customer_address]').val('').end()

        $(modal)
            .find('.total_price_view').text('').end()
            .find('.total_discount_view').text('').end()
            .find('.total_net_view').text('').end()
            .find('.total_pay_view').text('').end()
            .find('.total_unit_view').text('').end()
            .find('.status_payment_view').text('').end()

        $(modal)
            .find('[name=deposit]').removeAttr('disabled', 'disabled').end()
            .find('[name=bank]').removeAttr('disabled', 'disabled').end()
            .find('[name=deposit_date]').removeAttr('disabled', 'disabled').end()
            .find('[name=pos_date]').removeAttr('disabled', 'disabled').end()

        $("#list_booking_view tbody").text('')
        $("#list_item_view tbody").text('')

        $('[name=date_order]').datepicker("setDate", new Date());
    }

    //  *
    //  * Modal
    //  * hiding modal
    //  *
    function modalHide() {
        $(modal).modal('hide')
    }

    //  *
    //  * Modal
    //  * data loading on modal
    //  *
    function modalLoading() {
        if ($(modal_body).length) {
            $(modal_body).find('div').hide()
            $(modal_body).append(loading)
        }
    }

    //  *
    //  * Modal
    //  * clear data loading on modal
    //  *
    function modalLoading_clear() {
        if ($(modal_body).length) {
            $(modal_body).find('.loading').remove()
            $(modal_body).find('div').show()
        }
    }
    //  =========================
    //  =========================
    //  End Base Function
    //  =========================
    //  =========================
</script>