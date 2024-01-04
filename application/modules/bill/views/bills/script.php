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
    const form_button_btn_edit = '#modal_view.btn-edit'
    const form_button_btn_add =  '.btn-add'
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
            let item_id = $(modal).find(form_hidden_id).val()

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

                                resolve(
                                    item_list.push({
                                        id: bill_item_id,
                                        name: bill_item_name,
                                        price: bill_item_price,
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

                        if ($('[name=bookingdate]').val()) {
                            let booking = $('[name=bookingdate]').val();
                            set_booking = booking.split("/")
                            let new_booking = set_booking[2] + "-" + set_booking[1] + "-" + set_booking[0]

                            data.push({
                                'name': 'bookingdate',
                                'value': new_booking,
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
                                        window.location.reload()

                                        modalHide()
                                    })
                                }
                            });

                        resolve(
                            modalLoading_clear()
                        )

                    } else {

                        if ($('[name=bookingdate]').val()) {
                            var dateTypeVar = $('[name=bookingdate]').datepicker('getDate');
                            data.push({
                                'name': 'bookingdate',
                                'value': $.datepicker.formatDate('yy-mm-dd', dateTypeVar)
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

                                        dataReload()

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

            let id = $(this).attr('data-id')
            edit_data(id)

            $(form_name).find(form_hidden_id).val(id)
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
        let btn_edit = $(modal).find(form_button_btn_edit)
        let btn_submit = $(modal).find(form_button_btn_submit)

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
            .find('#list_item tbody').html('').end()
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