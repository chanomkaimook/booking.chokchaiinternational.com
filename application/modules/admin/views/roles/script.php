<script>
    let role_child_select

    $(d).ready(function() {

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


            // set variable checkbox
            // permit
            let a = $('.jstree-grid-container li[aria-level=2][aria-selected=true]')
            $.each(a, function(index, item) {
                // data.append('a',$(item).attr('data-id'))
                data.push({
                    'name': 'permit_id[]',
                    'value': $(item).attr('data-id')
                })
            })

            let func

            if (item_id) {
                func = async_update_data(item_id, data)
            } else {
                func = async_insert_data(data)

            }

            func
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

                            modalHide()

                            dataReload()

                        })
                    }
                });


            return false
        })

        //  *
        //  * CRUD
        //  * click button view
        //  * 
        //  * call function view data
        //  *
        $(d).on('click', btn_view, function(e) {
            e.preventDefault()

            let id = $(this).attr('data-id')
            view_data(id)

            $(form_name).find(form_hidden_id).val(id)
            $(form_name).find(btn_edit).attr('data-id', id)
        })

        //  *
        //  * CRUD
        //  * click button edit
        //  * 
        //  * call function open form for edit data
        //  *
        $(d).on('click', btn_edit, function(e) {
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
        $(d).on('click', btn_add, function(e) {
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
        $(d).on('click', btn_del, function(e) {
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
            modalLoading()
        })

        $('[data-plugin=jstree_checkbox]').on("open_node.jstree", function(e, data) {
            let menu_name = data.node.text

            data.node.children.map(function(item) {
                let li_checkbox = $('.jstree-grid-container li#' + item + '[aria-level=2][aria-selected=true]')
                if (li_checkbox.length) {
                    li_checkbox.find('a').attr('data-jstree_fromrole', menu_name)
                }

            })

            let this_select = $('#roles_child').val()

            get_dataPermitFromRole(this_select.join())

        })

        $(d).on('click', '.jstree-grid-container li[aria-level=1] > a', function(e) {
            e.preventDefault()

            check_boxPermit()
        })

        function check_boxPermit() {
            let node = $('.jstree-grid-container li[aria-level=1]')
            let node_disable = node.find('a.jstree-disabled')

            if (node_disable) {
                $.each(node_disable, function(index, item) {

                    let aria_id = $(item).parent('li').attr('data-id')
                    js_checkbox = $(modal_body_form)
                        .find('.jstree-grid-container li[aria-level=2][data-id=' + aria_id + ']')
                    js_id = js_checkbox.attr('id')
                    js_checkbox.jstree("check_node", "#" + js_id)
                })
            }
        }

        $(d).on('click', '[data-jstree_fromrole]', function(e) {
            e.preventDefault()

            let role_name = $(this).attr('data-jstree_fromrole')
            let text = setlang == 'thai' ? 'ค่านี้เป็น child ของ Role ' + role_name : 'Role ' + role_name + ' have owner this value'
            swalalert('error', text)

        })

        $('#roles_child').on('select2:select', function(e) {
            e.preventDefault()
            
            let element = $(this)
            let e_value = element.val()

            role_child_select = e_value

            get_dataPermitFromRole(e_value.join())
        });

        $('#roles_child').on('select2:unselect', function(e) {
            e.preventDefault()

            let this_select = $(this).val()
            t(this_select)
        });

        function t(value_child = null) {

            let new_select = []
            let this_select = value_child
            if (role_child_select) {
                role_child_select.forEach((item, index) => {

                    if (this_select.indexOf(item) == -1) {
                        new_select.push(item)
                    }
                })
            }

            if (new_select.length) {

                let url_role = new URL(path(url_moduleControl + '/get_dataPermitFromRole'), domain)
                let dataarray = new FormData();
                dataarray.append('id', new_select)
                fetch(url_role, {
                        method: "post",
                        body: dataarray
                    })
                    .then(res => res.json())
                    .then(resp => {

                        let js_checkbox
                        let js_id
                        
                        if (resp.PERMIT) {

                            $.each(resp.PERMIT, function(index, item) {
                                item.map(function(permit) {

                                    js_checkbox = $(modal_body_form)
                                        .find('.jstree-grid-container li[aria-level=2][data-id=' + permit.PERMIT_ID + ']')
                                    js_id = js_checkbox.attr('id')

                                    js_checkbox.jstree("deselect_node", "#" + js_id)
                                    js_checkbox.jstree("enable_node", "#" + js_id)

                                    js_checkbox.find('a').removeAttr('data-jstree_fromrole')
                                })
                            })

                            // for read permit again to choose
                            get_dataPermitFromRole(this_select.join())
                        }

                    })
            }

        }
    })
    //  =========================
    //  =========================
    //  End Event
    //  =========================
    //  =========================

    //  =========================
    //  =========================
    //  Function
    //  Todo adjust code default here
    //  =========================
    //  =========================

    function get_dataPermitFromRole(data = null) {
        if (data) {
            let url_role = new URL(path(url_moduleControl + '/get_dataPermitFromRole'), domain)
            let dataarray = new FormData();
            dataarray.append('id', data)
            fetch(url_role, {
                    method: "post",
                    body: dataarray
                })
                .then(res => res.json())
                .then(resp => {

                    create_html_checkjstree(resp.PERMIT, 1)

                })
        } else {
            jstree_clear()
        }
    }

    function jstree_clear() {
        $('[data-plugin=jstree_checkbox]').jstree("refresh");
        // $('[data-plugin=jstree_checkbox]').jstree("deselect_all");
    }

    //  *
    //  * Modal
    //  * view
    //  * 
    //  * display data
    //  * @data = array[key=>[column=>value]]
    //  *
    function modalActive(data = [], action = 'view') {
        if (action != 'add' && data.NAME) {
            let header = data.NAME
            $(modal).find('.modal_text_header').html(header)
        }

        switch (action) {
            case 'view':
                $(modal_body_view)
                    .find('.roles_name_th').text(data.NAME).end()
                    .find('.roles_name_us').text(data.NAME_US).end()
                    .find('.roles_descrip_th').text(data.DESCRIPTION).end()
                    .find('.roles_descrip_us').text(data.DESCRIPTION_US).end()
                    .find('.roles_code').text(data.CODE).end()
                    .find('.jstree-grid-container').html(data.PERMIT_HTML).end()

                // create role
                create_html_select2()

                test()

                async function test() {
                    let data_array_html = ""
                    await new Promise((resolve, reject) => {
                        resolve(
                            data.ROLES.map(function(item) {
                                data_array_html += create_html_roles(textCapitalize(item.ROLES_CODE))
                            })
                        )

                    })
                    await new Promise((resolve, reject) => {
                        $(modal_body_view).find('.roles_child').html(data_array_html)
                    })
                }

                $('[data-plugin=jstree]').jstree()
                break
            case 'edit':
                $(modal_body_form)
                    .find('[name=roles_name_th]').val(data.NAME).end()
                    .find('[name=roles_name_us]').val(data.NAME_US).end()
                    .find('[name=roles_descrip_th]').val(data.DESCRIPTION).end()
                    .find('[name=roles_descrip_us]').val(data.DESCRIPTION_US).end()
                    .find('[name=roles_code]').val(data.CODE).end()

                //
                // create role
                let roles_id_child
                if (data.ROLES.length) {
                    roles_id_child = data.ROLES.map(function(item) {
                        return item.ROLES_ID_CHILD
                    })

                    $(modal_body_form)
                        .find('#roles_child').val(roles_id_child).triggerHandler('change')

                    // set default value
                    role_child_select = $(modal_body_form).find('#roles_child').val()

                    //
                    // create permit
                    create_html_checkjstree(data.PERMIT, 1)
                } else {
                    create_html_checkjstree(data.PERMIT)

                }

                break
            default:
                // create role
                create_html_select2()
                break
        }

        $(modal_roles).modal()

        modalLayout(action)
    }

    function create_html_select2() {
        let url_role = new URL(path(url_moduleControl + '/get_dataRole'), domain)
        fetch(url_role)
            .then(res => res.json())
            .then(resp => {
                let data_array = ""
                let item_value
                let item_id

                resp.map(function(item) {
                    item_value = textCapitalize(item.CODE)
                    item_id = item.ID
                    data_array += `<option value="${item_id}">${item_value}</option>`
                })
                $('[data-toggle=select2]')
                    .html(data_array).select2()

            })
    }

    function create_html_checkjstree(data = null, disable = null) {
        jstree_clear()
        if (data) {
            let permit_id

            $.each(data, function(key, arraypermit) {
                if (arraypermit.length) {
                    $.each(arraypermit, function(index, column) {
                        permit_id = column.PERMIT_ID
                        if (permit_id) {

                            let js_checkbox = $(modal_body_form)
                                .find('.jstree-grid-container li[aria-level=2][data-id=' + permit_id + ']')
                            let js_id = js_checkbox.attr('id')

                            js_checkbox.jstree("check_node", "#" + js_id)

                            if (disable == 1 && role_child_select.indexOf(column.ROLES_ID) != -1) {
                                js_checkbox.jstree("disable_node", "#" + js_id)
                                    .find('a').attr('data-jstree_fromrole', column.ROLES_CODE)

                            }
                        }
                    })
                }
            })
        }
    }

    function create_html_roles(text = null) {
        let html = ""

        if (text) {
            html += `<div class="btn btn-primary">${text}</div>`
        }

        return html
    }

    //  *
    //  * Modal
    //  * layout
    //  * 
    //  * layout DOM for show on modal
    //  *
    function modalLayout(action = null) {
        let form_btn_edit = $(modal).find(btn_edit)
        let form_btn_submit = $(modal).find(btn_submit)
        let form_btn_print = $(modal).find(btn_print)

        if (action == 'view') {
            $(modal_body_view).removeClass('d-none')
            $(modal_body_form).addClass('d-none')

            form_btn_edit.show()
            form_btn_submit.hide()
            form_btn_print.show()
        } else {
            $(modal_body_view).addClass('d-none')
            $(modal_body_form).removeClass('d-none')

            form_btn_edit.hide()
            form_btn_submit.show()
            form_btn_print.hide()
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
                    let remark = result.value
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
            $(datatable_name).DataTable().ajax.reload(false)
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

        //
        // clear manual
        $(modal).find(form_hidden_id).val('')
        $(modal).find('.modal_text_header').html('')
        $('[data-plugin=jstree_checkbox]').jstree("deselect_all");
        $('.modal').find('.slimScrollDiv').slimScroll();

        $('[data-toggle=select2]').val(null).trigger('change')
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