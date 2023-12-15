<div class="content">
    <input type="hidden" id="hidden_task_id">
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="section-tool d-flex flex-column flex-md-row justify-content-between">

            <div class="mb-1 mb-md-0">
                <div class="d-flex gap-2">
                    <?php
                    if ($bill['COMPLETE_ID'] < 3) :  // waite & checking
                    ?>
                        <div class="tool-btn">
                            <button type="button" class="btn-add-receipt btn">ออกใบเสร็จรับเงิน</button>
                        </div>
                    <?php elseif ($bill['COMPLETE_ID'] == 3) : ?>
                        <div class="">asdas</div>
                    <?php endif; ?>
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
<?php require_once('application/views/partials/e_script_print.php'); ?>