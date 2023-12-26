<div class="content">
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="section-tool d-flex flex-column flex-md-row justify-content-end">

            <div class="">
                <span class="sector_button-edit">
                </span>
                <button type="button" class="btn-print btn btn-primary" onclick="printDiv('document')"><i class="fas fa-print"></i> Print</button>
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
                        <?php include('style_receipt.php'); ?>

                        <div class="A4half">
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
                                <div class="row">
                                    <div class="col-4 text-left">
                                        <p>เลขประจำตัวผู้เสียภาษี<br>
                                            <span style="font-size:11px">0 1 3 5 5 4 3 0 0 1 7 0 2</span>
                                        </p>
                                        <p>
                                            <b>เล่มที่ <span class="billsub">054</span></b>
                                        </p>
                                    </div>
                                    <div class="col-4 justify-content-center align-self-center">
                                        <b>
                                            <p>ใบเสร็จรับเงิน/ใบกำกับภาษี<br><span style="font-size:9px">RECEIPT / TAX INVOICE</span></p>
                                        </b>
                                    </div>
                                    <div class="col-4 text-right">
                                        <p style="font-size:11px">สาขาที่ออกใบกำกับภาษี คือ สาขาที่ 1<br>
                                        </p>
                                        <p class="mt-2 pr-2"><b>เลขที่ <span class="billsub">2555</span></b></p>
                                    </div>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col-9">
                                    <div class="d-flex">
                                        <div class="pr-1">ชื่อ</div>
                                        <div class="flex-fill justify-content-end align-self-end">
                                            <p class="dotted"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="d-flex">
                                        <div class="pr-1">วันที่</div>
                                        <div class="flex-fill justify-content-end align-self-end">
                                            <p class="dotted"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex">
                                        <div class="pr-1">ที่อยู่</div>
                                        <div class="flex-fill justify-content-end align-self-end">
                                            <p class="dotted"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="d-flex">
                                        <div class="pr-1">เลขประจำตัวผู้เสียภาษี</div>
                                        <div class="flex-fill justify-content-end align-self-end">
                                            <p class="dotted"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="d-flex">
                                        <div class="pr-1">
                                            <i class="mdi mdi-checkbox-blank-outline"></i>
                                            สำนักงานใหญ่
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="d-flex">
                                        <div class="pr-1">
                                            <i class="mdi mdi-checkbox-blank-outline"></i>
                                            สาขา
                                        </div>
                                        <div class="flex-fill justify-content-end align-self-end">
                                            <p class="dotted"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <table border="1" class="w-100 table-head">
                                        <thead class="text-center">
                                            <tr>
                                                <th>รายการ<br>Description</th>
                                                <th>จำนวน<br>Quantity</th>
                                                <th>ราคาต่อหน่วย<br>Unit Price</th>
                                                <th>จำนวนเงิน<br>Amount</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
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
                                        <p>ผู้ประสานงาน : <?= $agent_name . $agent_contact; ?></b></p>
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
                                        <th class="point_condition text-center" style="font-size:14px">
                                            <b><?= textNull($net_text_convert_th); ?></b>
                                        </th>
                                        <th width="13%">รวมเงิน</th>
                                        <th width="13%" class="text_price"><?php echo $price; ?></th>
                                    </tr>

                                    <tr>
                                        <th rowspan="3" class="point_condition">
                                            <?= textNull($q_setting->CN_CONDITION); ?>
                                        </th>
                                        <th>ส่วนลด</th>
                                        <th class="text_discount"><?= $discount; ?></th>
                                    </tr>
                                    <tr>

                                        <th>Deposit</th>
                                        <th class="text_deposit"><?= $deposit; ?></th>
                                    </tr>
                                    <tr>

                                        <th>คงเหลือ</th>
                                        <th class="text_net"><?= $net; ?></th>
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


                        </div>

                    </div>
                </div>
            </div>

            <!-- end row -->

        </div> <!-- end container-fluid -->

    </div> <!-- end content -->

    <?php require_once('application/views/partials/e_script_print.php'); ?>