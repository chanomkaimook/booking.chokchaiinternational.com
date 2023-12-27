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
echo "<pre>";
        print_r($receipt);
        echo "</pre>";
        $bill_main = $receipt['BILLMAIN'];
        $bill_sub = $receipt['BILLSUB'];
        $bill_codetext = $receipt['CODETEXT'];

        $bill_price_novat = $receipt['PRICE_NOVAT'] ? textMoney($receipt['PRICE_NOVAT']) : null;
        $bill_vat = $receipt['VAT'] ? textMoney($receipt['VAT']) : null;
        $bill_net = $receipt['NET'] ? textMoney($receipt['NET']) : null;
        $net_text_convert_th = $receipt['NET'] ? convertNumberToText('100.00') : null;

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
                                            <b>เล่มที่ <span class="billsub"><?= $bill_main; ?></span></b>
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
                                        <p class="mt-2 pr-2"><b>เลขที่ <span class="billsub"><?= $bill_sub; ?></span></b></p>
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
                                                <th width="80px">จำนวน<br>Quantity</th>
                                                <th width="100px">ราคาต่อหน่วย<br>Unit Price</th>
                                                <th width="120px">จำนวนเงิน<br>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody class="page_item">
                                            <tr style="height:4cm">
                                                <td class="text-left">asdasd</td>
                                                <td>aaa</td>
                                                <td>21321</td>
                                                <td class="text-right">*****</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <table border="0" class="w-100 font-paper page_footer">
                                <tbody>
                                    <tr class="text-center">
                                        <td>ออกแทนใบกำกับภาษีอย่างย่อเลขที่ <span><?= $bill_codetext; ?></span></td>
                                        <td width="180px" class="text-right">ราคาสินค้าได้รับยกเว้นภาษี</td>
                                        <td width="120px" class="text-right textmoney" style="border-top:0">-</td>
                                    </tr>
                                    <tr class="text-center">
                                        <td class="textmoney"><b><?= $net_text_convert_th; ?></b></td>
                                        <td width="180px" class="text-right">ราคาสินค้าที่เสียภาษี</td>
                                        <td width="120px" class="text-right textmoney"><?= $bill_price_novat; ?></td>
                                    </tr>
                                    <tr class="text-center">
                                        <td rowspan="2" style="vertical-align: bottom;">ผู้รับเงิน..................................................................
                                            <br>ใบเสร็จรับเงินนี้จะสมบูรณ์ต่อเมื่อได้รับเงินหรือเช็คผ่านการเรียกเก็บเงินแล้ว
                                        </td>
                                        <td width="180px" class="text-right">ภาษีมูลค่าเพิ่ม</td>
                                        <td width="120px" class="text-right textmoney"><?= $bill_vat; ?></td>
                                    </tr>
                                    <tr class="text-center">
                                        <td width="180px" class="text-right">รวมเงินทั้งสิ้น</td>
                                        <td width="120px" class="text-right textmoney"><?= $bill_net; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        


                        </div>

                    </div>
                </div>
            </div>

            <!-- end row -->

        </div> <!-- end container-fluid -->

    </div> <!-- end content -->

    <?php require_once('application/views/partials/e_script_print.php'); ?>