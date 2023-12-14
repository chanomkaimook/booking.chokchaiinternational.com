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

                    <div id="document">

                        <style>
                            .truncate {
                                max-width: 100px;
                            }

                            body .card-box {
                                background: rgb(204, 204, 204);
                            }

                            page[size="A4"] {
                                background: white;
                                width: 21cm;
                                height: 29.7cm;
                                display: block;
                                margin: 0 auto;
                                padding: 50px 35px;
                                margin-bottom: 0.5cm;
                                box-shadow: 0 0 0.5cm rgba(0, 0, 0, 0.5);
                            }

                            @media print {

                                body,
                                page[size="A4"] {
                                    width: 100%;
                                    height: auto;
                                    margin: 0;
                                    box-shadow: 0;
                                }

                                p {
                                    margin-bottom: 6px;
                                }
                            }

                            /* 
                            |
                            | Header
                            |
                            */
                            .page_header {
                                text-align: center;
                                width: 100%;
                            }

                            .page_header .logo img {
                                width: 4cm;
                            }

                            /* 
                            |
                            | Body
                            |
                            */
                            .page_body {
                                width: 100%;
                            }

                            .page_body .pb_head .pb_head_cus {
                                width: 70%;
                            }

                            .page_body .pb_head .pb_head_doc {
                                width: 30%;
                            }

                            /* 
                            |
                            | Item
                            |
                            */
                            .page_item {
                                width: 100%;
                            }

                            .page_item tr {
                                text-align: center
                            }

                            .page_item tbody tr {
                                vertical-align: top;
                            }

                            .page_item tbody tr td:nth-child(2) {
                                text-align: left
                            }

                            .page_item tbody tr td .doc_remark {
                                position: absolute;
                                bottom: 2px;
                            }



                            .page_item_total {
                                width: 100%;
                            }

                            .page_item_total tr {
                                text-align: center
                            }

                            .page_item_total tr th:last-child() {
                                text-align: right
                            }

                            /* 
                            |
                            | Condition
                            |
                            */
                            .page_condition {
                                width: 100%;
                            }

                            .page_condition td {
                                padding:10px 0px;
                                font-size: 11px;
                            }

                            p {
                                margin-bottom: 6px;
                            }
                        </style>

                        <page size="A4">
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
                                        <p><b>เรียน/Attention : <span class="pb_head_cus_name">aasd</span></b></p>
                                        <p><span class="pb_head_cus_address">asdasda asdasdasdasdads asda</span></b></p>
                                        <p>ผู้ประสานงาน : <span class="pb_head_agent">asdasda asdasdasdasdadsasda</span></b></p>
                                        <p>Email : <span class="pb_head_agentcontact">asdasda asdasdasdasdadsasda</span></b></p>
                                    </td>
                                    <td>
                                        <p><b>เลขที่/No :</b><span class="pb_head_doc_code"></span></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p><b>วันที่/Date :</b><span class="pb_head_doc_code"></span></p>
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
                                <tbody style="height:5cm">
                                    <tr>
                                        <td>1</td>
                                        <td style="position:relative">
                                            บัตรนั่งรถ
                                            <p class="doc_remark">
                                                หมายเหตุ: 1. ตะลอนฟาร์มโชคชัย <br>
                                                2. ราคานี้รวมภาษีมูลค่าเพิ่ม 7% แล้ว
                                            </p>
                                        </td>
                                        <td>25</td>
                                        <td>120.00</td>
                                        <td>3,000.00</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table border="1" class="page_item_total">
                                <thead>
                                    <tr>
                                        <th rowspan="3"></th>
                                        <th width="13%">รวมเงิน</th>
                                        <th width="13%">3000</th>
                                    </tr>
                                    <tr>

                                        <th>Deposit</th>
                                        <th>1500</th>
                                    </tr>
                                    <tr>

                                        <th>คงเหลือ</th>
                                        <th>1500</th>
                                    </tr>
                                </thead>
                            </table>
                            <table border="1" class="page_condition">
                                <tr>
                                    <td>
                                        <?= textNull($q_setting->CN_CONDITION); ?>
                                    </td>
                                </tr>
                            </table>
                            <table class="page_footer">
                            </table>
                        </page>

                    </div>

                </div>
            </div>
        </div>

        <!-- end row -->

    </div> <!-- end container-fluid -->

</div> <!-- end content -->
<?php require_once('application/views/partials/e_script_print.php'); ?>