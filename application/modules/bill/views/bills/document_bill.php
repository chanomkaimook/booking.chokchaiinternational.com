<div class="content">
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="section-tool d-flex flex-column flex-md-row justify-content-between">

            <div class="mb-1 mb-md-0">
                <div class="d-flex gap-2">
                    <?php
                    if ($bill['PAYMENT_ID'] != 8) :  // 8=success
                    ?>
                        <div class="tool-btn">
                            <button type="button" class="btn-add-receipt btn">ออกใบกำกับย่อ</button>
                        </div>
                    <?php
                    endif;
                    ?>
                    <div class="sector_billvat">
                    </div>
                    <div class="sector_receipt">
                    </div>
                </div>
            </div>
            <div class="">
                <button type="button" class="btn-print btn btn-pink" onclick="printDiv('document')"><i class="fas fa-print"></i> Print</button>
            </div>

        </div>
        <?php
        $this->load->model('mdl_settings');
        $q_setting = $this->mdl_settings->get_data();

        ?>
        <div class="">
            <div class="card-box">
                <div class="template">

                    <div>
                        <?php include('style_document.php'); ?>
                        <?php
                        $code = textNull($bill['CODE']);
                        // $code = get_status_alias(2);
                        // $code = complete('checking');

                        $y = date('Y', strtotime($bill['DATE_ORDER'])) + 543;
                        $dm = date('d/m/', strtotime($bill['DATE_ORDER']));
                        $date_order = $dm . $y;

                        $customer_id = textNull($bill['CUSTOMER_ID']);
                        $customer_name = textNull($bill['CUSTOMER_NAME']);
                        $customer_address = "";
                        $agent_name = textNull($bill['AGENT_NAME']);
                        $agent_contact = textNull($bill['AGENT_CONTACT']);
                        $agent_email = "";

                        $doc_item_remark = "หมายเหตุ: 1. ตะลอนฟาร์มโชคชัย <br>2. ราคานี้รวมภาษีมูลค่าเพิ่ม 7% แล้ว";

                        // sort item on bill detail (bd)
                        $item_i = [];
                        $item_p = [];
                        if ($bill_detail) {
                            $item_i = array_keys(array_column($bill_detail, 'ITEM_ID'), true);
                            $item_p = array_keys(array_column($bill_detail, 'PROMOTION_ID'), true);
                        }

                        $price = textMoney($bill['PRICE']);
                        $discount = textMoney($bill['DISCOUNT']);
                        $deposit = textMoney($bill['DEPOSIT']);
                        $net = textMoney($bill['NET']);

                        $staff = whois($bill['USER_STARTS']);
                        ?>

                        <input type="hidden" id="data-bill_id" value="<?= $bill['ID']; ?>">
                        <input type="hidden" id="data-bill_code" value="<?= $bill['CODE']; ?>">
                        <input type="hidden" id="data-date_order" value="<?= $bill['DATE_ORDER']; ?>">
                        <input type="hidden" id="data-bill_booking" value="<?= $bill['BOOKING_DATE']; ?>">

                        <page size="A4" id="document">
                            <div class="page_header">

                                <div class="logo">
                                    <img src="<?php echo base_url('asset/images/logo/farmchokchai.png'); ?>">
                                </div>
                                <div class="address">
                                    <h5><?= textNull($q_setting->CN_NAME); ?></h5>
                                    <p>
                                        <?= textNull($q_setting->CN_ADDRESS); ?>
                                        <br>
                                        <?= textNull($q_setting->CN_TEXT_PHONE); ?>
                                    </p>
                                </div>

                            </div>

                            <table border="1" class="page_header">
                                <tr>
                                    <td class="pb_document">
                                        <h4>ใบเสนอราคา</h4>
                                        <h4>QUOTATION</h4>
                                    </td>
                                </tr>
                            </table>
                            <table border="1" class="page_body">
                                <!-- 
                                    |
                                    | Head detail
                                    |
                                -->
                                <tr class="pb_head">
                                    <td rowspan="4" class="pb_head_cus">
                                        <p><b>เรียน/Attention : <?= $customer_name; ?></b></p>
                                        <!-- <p> <?= $customer_address; ?></b></p> -->
                                        <p>ผู้ประสานงาน : <?= $agent_name . " (" . $agent_contact . ") "; ?></b></p>
                                        <p>Email : <?= $agent_email; ?></b></p>
                                    </td>
                                    <td>
                                        <p><b>เลขที่/No :</b><?= $code; ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p><b>วันที่/Date :</b><?= $date_order; ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p><b>TEL :</b> <?= textNull($q_setting->CN_PHONE); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p><b>FAX :</b> <?= textNull($q_setting->CN_FAX); ?></p>
                                    </td>
                                </tr>

                                <!-- 
                                    |
                                    | Head text show
                                    |
                                -->
                                <tr>
                                    <td colspan="2">
                                        <p>ขอเสนอราคาและเงื่อนไขสำหรับท่านดังนี้</p>
                                        <p>We are please to submit you the following decribed here in at price, items and terms stated :</p>
                                    </td>
                                </tr>
                            </table>
                            <table border="1" class="page_item">
                                <thead>
                                    <tr>
                                        <th width="11%">ลำดับที่<br>ITEM</th>
                                        <th width="50%">รายการ<br>DESCRIPTION</th>
                                        <th width="13%">จำนวน<br>QUANTITY</th>
                                        <th width="13%">ราคาต่อหน่วย<br>PRICE</th>
                                        <th width="13%">จำนวนเงิน<br>AMOUNT</th>
                                    </tr>
                                </thead>
                                <tbody style="height:7cm">
                                    <?php
                                    // 
                                    // sort item on bill detail (bd)
                                    // 
                                    if ($item_i) {
                                        $item_html = "";
                                        $item_number = "";
                                        $item_name = "";
                                        $item_qty = "";
                                        $item_price = "";
                                        $item_net = "";

                                        $number_item = 1;
                                        foreach ($item_i as $key => $key_db) {
                                            $item_number .= "<p>" . $number_item . "</p>";
                                            $item_name .= "<p>" . $bill_detail[$key_db]['DESCRIPTION'] . "</p>";
                                            $item_qty .= "<p>" . $bill_detail[$key_db]['QUANTITY'] . "</p>";
                                            $item_price .= "<p>" . textMoney($bill_detail[$key_db]['PRICE']) . "</p>";
                                            $item_net .= "<p>" . textMoney($bill_detail[$key_db]['NET']) . "</p>";

                                            $number_item++;
                                        }

                                        if ($item_p) {
                                            foreach ($item_p as $key => $key_db) {
                                                $item_name .= "<p>" . $bill_detail[$key_db]['DESCRIPTION'] . "</p>";
                                                $item_qty .= "<p>" . $bill_detail[$key_db]['QUANTITY'] . "</p>";
                                                $item_price .= "<p>" . textMoney($bill_detail[$key_db]['PRICE_UNIT']) . "</p>";
                                                $item_net .= "<p>" . textMoney($bill_detail[$key_db]['DISCOUNT']) . "</p>";
                                            }
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td><?= $item_number; ?></td>
                                        <td style="position:relative">
                                            <?= $item_name; ?>
                                            <p class="doc_remark">
                                                หมายเหตุ: 1. ตะลอนฟาร์มโชคชัย <br>
                                                2. ราคานี้รวมภาษีมูลค่าเพิ่ม 7% แล้ว
                                            </p>
                                        </td>
                                        <td><?= $item_qty; ?></td>
                                        <td><?= $item_price; ?></td>
                                        <td><?= $item_net; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <table border="1" class="page_item_total">
                                <thead>
                                    <tr>
                                        <th rowspan="4" class="point_condition">
                                            <?= textNull($q_setting->CN_CONDITION); ?>
                                        </th>
                                        <th width="13%">รวมเงิน</th>
                                        <th width="13%"><?php echo $price; ?></th>
                                    </tr>
                                    <tr>
                                        <th>ส่วนลด</th>
                                        <th><?= $discount; ?></th>
                                    </tr>
                                    <tr>

                                        <th>Deposit</th>
                                        <th><?= $deposit; ?></th>
                                    </tr>
                                    <tr>

                                        <th>คงเหลือ</th>
                                        <th><?= $net; ?></th>
                                    </tr>
                                </thead>
                            </table>
                            <table border="1" class="page_footer">
                                <tr>
                                    <td>
                                        <p>ผู้เสนอราคา : ..........................................................</p>
                                        <p class="page_footer_sign"><?= $staff; ?></p>
                                    </td>
                                    <td style="padding:40px 0px">
                                        <p>ลูกค้า : ..........................................................</p>
                                    </td>
                                </tr>
                            </table>
                        </page>

                    </div>

                </div>
            </div>
        </div>

        <!-- end row -->

    </div> <!-- end container-fluid -->

</div> <!-- end content -->
<!-- Modal -->
<?php require_once('component/modal_formbillvat.php') ?>
<?php require_once('component/modal_receipt.php') ?>
<!-- End Modal -->

<script>
    $(document).ready(function() {

        let dataid = ""

        $("input[name=date_order_show]").datepicker({
            autoclose: !0,
            todayHighlight: !0,
            format: 'dd/mm/yyyy',
        })

        $("input[name=date_receipt_show]").datepicker({
            autoclose: !0,
            todayHighlight: !0,
            format: 'dd/mm/yyyy',
        })

        let modal_dp_name = '#modal_billvat'
        let modal_dp_view = modal_dp_name + ' .modal-body-view'
        let modal_dp_form = modal_dp_name + ' .modal-body-form'

        // get data billvat and receipt
        get_allbill()

        $(document).on('click', '.btn-add-receipt', function(e) {
            e.preventDefault()
            modalActive_deposit('add')
        })
        $(document).on('click', '.sector_billvat button', function(e) {
            e.preventDefault()

            dataid = $(this).attr('data-depositid')
            async_get_deposit(dataid)
                .then((resp) => {
                    modalActive_deposit('view', resp)
                })
        })
        $(document).on('click', '#modal_billvat .btn-edit', function(e) {
            e.preventDefault()

            async_get_deposit(dataid)
                .then((resp) => {
                    modalActive_deposit('edit', resp)
                })
        })

        // 
        // event receipt
        // 
        $(document).on('click', '.sector_receipt button', function(e) {
            e.preventDefault()

            dataid = $(this).attr('data-receiptid')
            async_get_receipt(dataid)
                .then((resp) => {
                    modalActive_receipt('view', resp)
                })
        })
        $(document).on('click', '#modal_receipt .btn-edit', function(e) {
            e.preventDefault()

            modalActive_receipt('edit')
        })


        // 
        // ############
        // Function Receipt
        // ############
        // 
        function modalActive_receipt(action = 'view', data = []) {

            let modal_rc_name = '#modal_receipt'
            let header = "เลขที่ใบเสร็จ "+data.CODE
            $(modal_rc_name).find('.modal_text_header').html(header)

            switch (action) {
                case 'view':

                    let new_date_order = ''
                    if (data.DATE_ORDER) {
                        let date_order = data.DATE_ORDER
                        $(modal_rc_name)
                            .find('[name=date_receipt_show]').datepicker("setDate", new Date(date_order));

                        let set_date_order = data.DATE_ORDER.split("-")
                        new_date_order = set_date_order[2] + "/" + set_date_order[1] + "/" + set_date_order[0]
                    }

                    $(modal_rc_name)
                        .find('[name=frm_receipt_hidden_id]').val(data.ID).end()
                        .find('[name=receipt_remark]').text(data.REMARK).end()

                    $('.modal-body-form')
                        .find('.code').text(data.CODE).end()
                        .find('.price_novat').text(data.PRICE_NOVAT).end()
                        .find('.vat').text(data.VAT).end()
                        .find('.net').text(data.NET).end()
                        .find('.codetext').text(data.CODETEXT).end()
                        .find('.booking_date').text(new_date_order).end()
                        .find('.remark_receipt').text(data.REMARK).end()
                        .find('.user_active').text(data.USER_ACTIVE).end()
                        .find('.date_active').text(data.DATE_ACTIVE).end()

                    break
                case 'edit':

                    
                    break
                default:
                    break
            }

            $(modal_rc_name).modal()

            modalLayout_receipt(action)

        }

        // 
        // ############
        // Function Deposit
        // ############
        // 
        function modalActive_deposit(action = 'view', data = []) {
            let header = 'สร้างใบรับโอนเงิน'
            if (action == 'add') {
                $(modal_dp_name).find('.modal_text_header').html(header)
            } else {
                header = 'ใบรับโอนเงิน'
                $(modal_dp_name).find('.modal_text_header').html(header)
            }

            switch (action) {
                case 'view':

                    let new_date_order = ''
                    if (data.DATE_ORDER) {
                        let set_date_order = data.DATE_ORDER.split("-")
                        new_date_order = set_date_order[2] + "/" + set_date_order[1] + "/" + set_date_order[0]
                    }

                    $(modal_dp_name)
                        .find('[name=frm_deposit_hidden_id]').val(data.ID).end()

                    $(modal_dp_view)
                        .find('.codetext').text(data.CODETEXT).end()
                        .find('.date_order').text(new_date_order).end()
                        .find('.deposit').text(data.DEPOSIT).end()
                        .find('.remark_deposit').text(data.REMARK).end()
                        .find('.user_active').text(data.USER_ACTIVE).end()
                        .find('.date_active').text(data.DATE_ACTIVE).end()

                    break
                case 'edit':

                    if (data.DATE_ORDER) {
                        let date_order = data.DATE_ORDER
                        $(modal_dp_form)
                            .find('[name=date_order_show]').datepicker("setDate", new Date(date_order));
                    }

                    $(modal_dp_form)
                        .find('[name=codetext]').val(data.CODETEXT).end()
                        .find('[name=deposit]').val(data.DEPOSIT).end()
                        .find('[name=remark]').val(data.REMARK).end()
                    break
                case 'add':
                    let date_order = $("#data-date_order").val()
                    $('[name=date_order_show]').datepicker("setDate", new Date(date_order));

                    break
                default:
                    break
            }

            $(modal_dp_name).modal()

            modalLayout_deposit(action)

        }

        function modalLayout_receipt(action = 'view') {
            let modal_name = "#modal_receipt"
            let btn_edit = $(modal_name).find('.btn-edit')
            let btn_submit = $(modal_name).find('button[type=submit]')

            let modal_rc_view = "#modal_receipt .modal-body-form"

            if (action == 'view') {
                $(modal_rc_view).find('.codetext').removeClass('d-none')
                $(modal_rc_view).find('button.btn-update-codetext').addClass('d-none')
                
                $(modal_rc_view).find('.remark_receipt').removeClass('d-none')
                $(modal_rc_view).find('[name=receipt_remark]').addClass('d-none')
                
                $(modal_rc_view).find('.booking_date').removeClass('d-none')
                $(modal_rc_view).find('[name=date_receipt_show]').addClass('d-none')

                btn_edit.show()
                btn_submit.hide()
            } else {
                $(modal_rc_view).find('.codetext').addClass('d-none')
                $(modal_rc_view).find('button.btn-update-codetext').removeClass('d-none')
               
                $(modal_rc_view).find('.remark_receipt').addClass('d-none')
                $(modal_rc_view).find('[name=receipt_remark]').removeClass('d-none')
                
                $(modal_rc_view).find('.booking_date').addClass('d-none')
                $(modal_rc_view).find('[name=date_receipt_show]').removeClass('d-none')

                btn_edit.hide()
                btn_submit.show()
            }
        }

        function modalLayout_deposit(action = 'view') {
            let btn_edit = $(modal_dp_name).find('.btn-edit')
            let btn_submit = $(modal_dp_name).find('button[type=submit]')

            if (action == 'view') {
                $(modal_dp_view).removeClass('d-none')
                $(modal_dp_form).addClass('d-none')

                btn_edit.show()
                btn_submit.hide()
            } else {
                $(modal_dp_view).addClass('d-none')
                $(modal_dp_form).removeClass('d-none')

                btn_edit.hide()
                btn_submit.show()
            }
        }
    })

    function get_allbill(id = null) {
        async_allbill()
        async function async_allbill() {
            let a = new Promise((resolve, reject) => {
                resolve(get_deposit(id))

                resolve(get_receipt(id))
            })
        }

        return true
    }

    function get_deposit(id = null) {
        async_get_deposit(id)
            .then((resp) => {
                if (resp) {

                    step_billvat()

                    async function step_billvat() {
                        let v = ""
                        let result = new Promise((resolve, reject) => {
                            resp.forEach(function(item, index) {
                                v += creat_html_billvat(item.ID, item.CODETEXT, item.USER_UPDATE, item.DATE_UPDATE)
                                resolve($('.sector_billvat').html(v))
                            })
                        })

                    }

                }
            })
    }

    function get_receipt(id = null) {
        async_get_receipt(id)
            .then((resp) => {
                if (resp) {

                    step_receipt()

                    async function step_receipt() {
                        let r = ""
                        let result = new Promise((resolve, reject) => {
                            resp.forEach(function(item, index) {
                                r += creat_html_receipt(item.ID, item.CODETEXT)
                                resolve($('.sector_receipt').html(r))

                                resolve($('.tool-btn').addClass('d-none'))
                            })
                        })

                    }

                }
            })
    }

    function creat_html_billvat(id = null, codetext = null, classname = "btn-warning", userupdate = null) {
        let text = "ยังไม่ระบุเลขใบกำกับ"

        if (codetext) {
            text = codetext
            classname = "btn-secondary"
        }

        let useractive = ""
        if (userupdate) {
            useractive = `(แก้ไข)`
        }

        let html = `<button type="button" class="btn ${classname} mr-1" data-depositid="${id}" >${useractive} ${text}</button>`
        return html
    }

    function creat_html_receipt(id = null, codetext = null, classname = "btn-warning", userupdate = null) {
        let text = "ยังไม่ระบุเลขใบกำกับ"

        if (codetext) {
            text = codetext
            classname = "btn-info"
        }

        let useractive = ""
        if (userupdate) {
            useractive = `(แก้ไข)`
        }

        let html = `<button type="button" class="btn ${classname}" data-receiptid="${id}" >${useractive} ${text}</button>`
        return html
    }

    //  *
    //  * CRUD
    //  * read
    //  * 
    //  * get data
    //  *
    async function async_get_deposit(id = null) {
        let url = new URL(path(url_moduleControl + '/get_deposit'), domain)
        if (id) {
            url.searchParams.append('id', id)
        } else {
            let bill_id = $('#data-bill_id').val();
            if (bill_id) {
                url.searchParams.append('bill_id', bill_id)
            }
        }

        let response = await fetch(url)
        let result = await response.json()

        return result
    }
    async function async_get_receipt(id = null) {
        let url = new URL(path(url_moduleControl + '/get_receipt'), domain)
        if (id) {
            url.searchParams.append('id', id)
        } else {
            let bill_id = $('#data-bill_id').val();
            if (bill_id) {
                url.searchParams.append('bill_id', bill_id)
            }
        }

        let response = await fetch(url)
        let result = await response.json()

        return result
    }
</script>

<?php require_once('application/views/partials/e_script_print.php'); ?>