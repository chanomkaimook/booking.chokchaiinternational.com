<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Writer;

#
#	Setting
#
$cl_s = "A";
$cl_e = "F";
$r_s = 1;
$font_size_general = 10;
$text_company = $q_setting->CN_NAME;
$text_company_id = $q_setting->CN_ID;
$text_company_address = $q_setting->CN_ADDRESS;
$text_company_textphone = $q_setting->CN_TEXT_PHONE;

$bill_code = $bill['CODE'] ? $bill['CODE'] : null;
$bill_main = $receipt['BILLMAIN'] ? $receipt['BILLMAIN'] : null;
$bill_submain = $receipt['BILLSUB'] ? $receipt['BILLSUB'] : null;
$cs_name = $bill['CUSTOMER_NAME'] ? $bill['CUSTOMER_NAME'] : null;
$bill_date = $bill['PAYMENT_ID'] == 7 ? toThaiDateTimeString($bill['DATE_ORDER']) : toThaiDateTimeString($receipt['BOOKING_DATE']);
$net = textShow((string)textMoney(floatval($bill['NET']) - floatval($total_deposit)), "0.00");

$trim_condition = preg_replace('/[[:space:]]+/', ' ', trim($q_setting->CN_CONDITION));
$cn_condition = str_replace("<br>", "\n", $trim_condition);

$bill_condition = textNull($cn_condition);

$net_text_convert_th = $receipt['NET'] ? convertNumberToText($receipt['NET']) : null;

$text_doc = "ใบเสนอราคา / ใบแจ้งหนี้";
$text_doc_us = "QUOTATION / INVOICE";
$text_bill_topic = "ขอเสนอราคาและเงื่อนไขสำหรับท่านดังนี้";
$text_bill_topic_us = "We are please to submit you the follwing described here in at price, items and terms stated :";
$text_bill_net = "รวมเงิน";
$text_bill_discount = "ส่วนลด";
$text_bill_deposit = "Deposit";
$text_bill_total = "คงเหลือ";

$text_bill_by = "ผู้เสนอราคา : ..................................................\nadmin";
$text_bill_customer = "ลูกค้า : ..................................................";

$list_remark = "หมายเหตุ: 1. ตะลอนฟาร์มโชคชัย \n2. ราคานี้รวมภาษีมูลค่าเพิ่ม 7% แล้ว";

$write_array[] = array(
    "รายการ\nDescription",
    "จำนวน\nQuantity",
    "ราคาต่อหน่วย\nDescription",
    "จำนวนเงิน\nDescription",
);

# general
$styleGeneral = [
    'alignment' => [
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
    ]
];
$styleMiddle = [
    'alignment' => [
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    ]
];

# head
$styleCenter = [
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
    ]
];

# border
$styleBorder = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => '00000000'],
        ],
    ],
];

# border around
$styleBorderOut = [
    'borders' => [
        'outline' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => '00000000'],
        ],
    ],
];

# style center & bold
$styleHeading = [
    'font' => [
        'bold' => true,
        'name' => 'Arial',
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    ]
];

// echo $this->db->last_query();
/* echo "<pre>";
print_r($bill);
echo "</pre>";
die(); */
$row++;
$last_row = $row;
$insertrow = 1;

$spreadsheet = new Spreadsheet();
$spreadsheet->setActiveSheetIndex(0);

#
#	mergeCells
$rowexcel = $r_s;

# logo
$spreadsheet->getActiveSheet()->mergeCells('C' . $rowexcel . ':D' . $rowexcel);
$spreadsheet->getActiveSheet()->getStyle('C' . $rowexcel)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$spreadsheet->getActiveSheet()->getRowDimension($rowexcel)->setRowHeight(22, "mm");

$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$drawing->setName('logo');
$drawing->setDescription('Logo');
$drawing->setPath('asset/images/logo/farmchokchai.png');
$drawing->setCoordinates('C1');
$drawing->setOffsetX(30);
$drawing->setHeight(80);
$drawing->setWorksheet($spreadsheet->getActiveSheet());

#
#	company
$rowexcel = $rowexcel + 1;
$spreadsheet->getActiveSheet()->mergeCells($cl_s . $rowexcel . ':' . $cl_e . $rowexcel);
$spreadsheet->getActiveSheet()->getRowDimension($rowexcel)->setRowHeight(15, "mm");
$company = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$company->createTextRun($text_company)
    ->getFont()->setBold(true);
$company->createTextRun("\n" . $text_company_address . "\n" . $text_company_textphone)
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell($cl_s . $rowexcel)->setValue($company);
$spreadsheet->getActiveSheet()->getStyle($cl_s . $rowexcel)->applyFromArray($styleCenter);

#
#	document topic
$rowexcel = $rowexcel + 1;
$spreadsheet->getActiveSheet()->mergeCells($cl_s . $rowexcel . ':' . $cl_e . $rowexcel);
$spreadsheet->getActiveSheet()->getRowDimension($rowexcel)->setRowHeight(12, "mm");
$topic = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$topic->createTextRun($text_doc . "\n" . $text_doc_us)
    ->getFont()->setBold(true)->setSize(12);
$spreadsheet->getActiveSheet()->getCell($cl_s . $rowexcel)->setValue($topic);
$spreadsheet->getActiveSheet()->getStyle($cl_s . $rowexcel)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$spreadsheet->getActiveSheet()->getStyle($cl_s . $rowexcel)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$spreadsheet->getActiveSheet()->getStyle($cl_s . $rowexcel . ":" . $cl_e . $rowexcel)->applyFromArray($styleBorder);

#
#	company address 1
$rowexcel = $rowexcel + 1;
$spreadsheet->getActiveSheet()->mergeCells($cl_s . $rowexcel . ':D' . $rowexcel);
$spreadsheet->getActiveSheet()->mergeCells('E' . $rowexcel . ':' . $cl_e . $rowexcel);
$spreadsheet->getActiveSheet()->getRowDimension($rowexcel)->setRowHeight(6, "mm");
$cm_1 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$cm_1->createTextRun("เรียน/Attention : ")
    ->getFont()->setBold(true)->setSize($font_size_general);
$cm_1->createTextRun("Customer")
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell($cl_s . $rowexcel)->setValue($cm_1);
$spreadsheet->getActiveSheet()->getStyle($cl_s . $rowexcel)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

$cm_1 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$cm_1->createTextRun("เลขที่/No : ")
    ->getFont()->setBold(true)->setSize($font_size_general);
$cm_1->createTextRun("CODE12345678")
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell('E' . $rowexcel)->setValue($cm_1);

$spreadsheet->getActiveSheet()->getStyle($cl_s . $rowexcel . ":" . $cl_e . $rowexcel)->applyFromArray($styleMiddle);
$spreadsheet->getActiveSheet()->getStyle('E' . $rowexcel . ':' . $cl_e . $rowexcel)->applyFromArray($styleBorder);

#
#	company address 2
$rowexcel = $rowexcel + 1;
$spreadsheet->getActiveSheet()->mergeCells($cl_s . $rowexcel . ':D' . $rowexcel);
$spreadsheet->getActiveSheet()->mergeCells('E' . $rowexcel . ':' . $cl_e . $rowexcel);
$spreadsheet->getActiveSheet()->getRowDimension($rowexcel)->setRowHeight(6, "mm");
$spreadsheet->getActiveSheet()->getStyle($cl_s . $rowexcel)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

$cm_2 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$cm_2->createTextRun("วันที่/Date : ")
    ->getFont()->setBold(true)->setSize($font_size_general);
$cm_2->createTextRun("21/08/2566")
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell('E' . $rowexcel)->setValue($cm_2);

$spreadsheet->getActiveSheet()->getStyle($cl_s . $rowexcel . ":" . $cl_e . $rowexcel)->applyFromArray($styleMiddle);
$spreadsheet->getActiveSheet()->getStyle('E' . $rowexcel . ':' . $cl_e . $rowexcel)->applyFromArray($styleBorder);

#
#	company address 3
$rowexcel = $rowexcel + 1;
$spreadsheet->getActiveSheet()->mergeCells($cl_s . $rowexcel . ':D' . $rowexcel);
$spreadsheet->getActiveSheet()->mergeCells('E' . $rowexcel . ':' . $cl_e . $rowexcel);
$spreadsheet->getActiveSheet()->getRowDimension($rowexcel)->setRowHeight(6, "mm");
$cm_3 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$cm_3->createTextRun("ผู้ประสานงาน : ")
    ->getFont()->setBold(true)->setSize($font_size_general);
$cm_3->createTextRun("Agent (agent phone)")
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell($cl_s . $rowexcel)->setValue($cm_3);
$spreadsheet->getActiveSheet()->getStyle($cl_s . $rowexcel)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

$cm_3 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$cm_3->createTextRun("TEL. : ")
    ->getFont()->setBold(true)->setSize($font_size_general);
$cm_3->createTextRun("04-493-5503")
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell('E' . $rowexcel)->setValue($cm_3);

$spreadsheet->getActiveSheet()->getStyle($cl_s . $rowexcel . ":" . $cl_e . $rowexcel)->applyFromArray($styleMiddle);
$spreadsheet->getActiveSheet()->getStyle('E' . $rowexcel . ':' . $cl_e . $rowexcel)->applyFromArray($styleBorder);

#
#	company address 4
$rowexcel = $rowexcel + 1;
$spreadsheet->getActiveSheet()->mergeCells($cl_s . $rowexcel . ':D' . $rowexcel);
$spreadsheet->getActiveSheet()->mergeCells('E' . $rowexcel . ':' . $cl_e . $rowexcel);
$spreadsheet->getActiveSheet()->getRowDimension($rowexcel)->setRowHeight(6, "mm");
$cm_4 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$cm_4->createTextRun("E-mail : ")
    ->getFont()->setBold(true)->setSize($font_size_general);
$cm_4->createTextRun("-")
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell($cl_s . $rowexcel)->setValue($cm_4);
$spreadsheet->getActiveSheet()->getStyle($cl_s . $rowexcel)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

$cm_4 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$cm_4->createTextRun("FAX. : ")
    ->getFont()->setBold(true)->setSize($font_size_general);
$cm_4->createTextRun("04-493-5508")
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell('E' . $rowexcel)->setValue($cm_4);

$spreadsheet->getActiveSheet()->getStyle($cl_s . $rowexcel . ":" . $cl_e . $rowexcel)->applyFromArray($styleMiddle);
$spreadsheet->getActiveSheet()->getStyle('E' . $rowexcel . ':' . $cl_e . $rowexcel)->applyFromArray($styleBorder);

#
# text detail topic
$rowexcel = $rowexcel + 1;
$spreadsheet->getActiveSheet()->mergeCells($cl_s . $rowexcel . ':' . $cl_e . $rowexcel);
$spreadsheet->getActiveSheet()->getRowDimension($rowexcel)->setRowHeight(14, "mm");
$bill_topic = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$bill_topic->createTextRun($text_bill_topic . "\n" . $text_bill_topic_us)
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell($cl_s . $rowexcel)->setValue($bill_topic);
$spreadsheet->getActiveSheet()->getStyle($cl_s . $rowexcel)->applyFromArray($styleMiddle);
$spreadsheet->getActiveSheet()->getStyle($cl_s . $rowexcel . ':' . $cl_e . $rowexcel)->applyFromArray($styleBorder);

$rowexcel = $rowexcel + 1;
$spreadsheet->getActiveSheet()->mergeCells('B' . $rowexcel . ':C' . $rowexcel);
$spreadsheet->getActiveSheet()->getRowDimension($rowexcel)->setRowHeight(12, "mm");
$tb_column_1 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$tb_column_1->createTextRun("ลำดับที่ \n ITEM")
    ->getFont()->setBold(true)->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell($cl_s . $rowexcel)->setValue($tb_column_1);
$tb_column_2 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$tb_column_2->createTextRun("รายการ \n DESCRIPTION")
    ->getFont()->setBold(true)->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell('B' . $rowexcel)->setValue($tb_column_2);
$tb_column_3 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$tb_column_3->createTextRun("จำนวน \n QUANTITY")
    ->getFont()->setBold(true)->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell('D' . $rowexcel)->setValue($tb_column_3);
$tb_column_4 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$tb_column_4->createTextRun("ราคาต่อหน่วย \n PRICE")
    ->getFont()->setBold(true)->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell('E' . $rowexcel)->setValue($tb_column_4);
$tb_column_5 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$tb_column_5->createTextRun("จำนวนเงิน \n AMOUNT")
    ->getFont()->setBold(true)->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell($cl_e . $rowexcel)->setValue($tb_column_5);
$spreadsheet->getActiveSheet()->getStyle($cl_s . $rowexcel . ':' . $cl_e . $rowexcel)->applyFromArray($styleBorder);
$spreadsheet->getActiveSheet()->getStyle($cl_s . $rowexcel . ':' . $cl_e . $rowexcel)->applyFromArray($styleHeading);

#
# Detail items
$start = $rowexcel + 1;

for ($i = 0; $i < 8; $i++) {
    $rowexcel = $rowexcel + 1;
    $spreadsheet->getActiveSheet()->mergeCells('B' . $rowexcel . ':C' . $rowexcel);
    $spreadsheet->getActiveSheet()->getRowDimension($rowexcel)->setRowHeight(10, "mm");

    if ($bill['item_list'] && $bill['item_list'][$i]) {
        $detail_item = $bill['item_list'][$i];

        #
        # number
        $number = 1 + $i;
        $item_number = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
        $item_number->createTextRun($number)
            ->getFont()->setSize($font_size_general);
        $spreadsheet->getActiveSheet()->getCell($cl_s . $rowexcel)->setValue($item_number);
        $spreadsheet->getActiveSheet()->getStyle($cl_s . $rowexcel)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        #
        # name
        $item_name = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
        $item_name->createTextRun($detail_item->DESCRIPTION)
            ->getFont()->setSize($font_size_general);
        $spreadsheet->getActiveSheet()->getCell('B' . $rowexcel)->setValue($item_name);

        #
        # quantity
        $item_qty = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
        $item_qty->createTextRun($detail_item->QUANTITY)
            ->getFont()->setSize($font_size_general);
        $spreadsheet->getActiveSheet()->getCell('D' . $rowexcel)->setValue($item_qty);
        $spreadsheet->getActiveSheet()->getStyle('D' . $rowexcel)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        #
        # unit price
        $item_unit_price = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
        $item_unit_price->createTextRun($detail_item->PRICE_UNIT)
            ->getFont()->setSize($font_size_general);
        $spreadsheet->getActiveSheet()->getCell('E' . $rowexcel)->setValue($item_unit_price);
        $spreadsheet->getActiveSheet()->getStyle('E' . $rowexcel)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        #
        # amount
        $item_amount = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
        $item_amount->createTextRun($detail_item->NET)
            ->getFont()->setSize($font_size_general);
        $spreadsheet->getActiveSheet()->getCell('F' . $rowexcel)->setValue($item_amount);
        $spreadsheet->getActiveSheet()->getStyle('F' . $rowexcel)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    }

    if ($i >= 7) {
        #
        # remark
        $item_list_remark = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
        $item_list_remark->createTextRun($list_remark)
            ->getFont()->setSize($font_size_general);
        $spreadsheet->getActiveSheet()->getCell('B' . $rowexcel)->setValue($item_list_remark);
        $spreadsheet->getActiveSheet()->getRowDimension($rowexcel)->setRowHeight(12, "mm");
    }
}

$end = $rowexcel;
$spreadsheet->getActiveSheet()->getStyle('A' . $start . ':A' . $end)->applyFromArray($styleBorderOut);
$spreadsheet->getActiveSheet()->getStyle('B' . $start . ':C' . $end)->applyFromArray($styleBorderOut);
$spreadsheet->getActiveSheet()->getStyle('D' . $start . ':D' . $end)->applyFromArray($styleBorderOut);
$spreadsheet->getActiveSheet()->getStyle('E' . $start . ':E' . $end)->applyFromArray($styleBorderOut);
$spreadsheet->getActiveSheet()->getStyle('F' . $start . ':F' . $end)->applyFromArray($styleBorderOut);

#
# condition
$rowexcel = $rowexcel + 1;
$row_end_remark = $rowexcel + 3;
$spreadsheet->getActiveSheet()->mergeCells($cl_s . $rowexcel . ':D' . $row_end_remark);
// $spreadsheet->getActiveSheet()->getRowDimension($rowexcel)->setRowHeight(14, "mm");
$bill_remark = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$bill_remark->createTextRun($bill_condition)
    ->getFont()->setSize(8);
$spreadsheet->getActiveSheet()->getCell($cl_s . $rowexcel)->setValue($bill_remark);
$spreadsheet->getActiveSheet()->getStyle($cl_s . $rowexcel)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

$spreadsheet->getActiveSheet()->getStyle('E' . $rowexcel . ':F' . $row_end_remark)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$spreadsheet->getActiveSheet()->getStyle('E' . $rowexcel . ':' . $cl_e . $row_end_remark)->applyFromArray($styleBorder);
#
# รวมเงิน
$excel_text_bill_net = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$excel_text_bill_net->createTextRun($text_bill_net)
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell('E' . $rowexcel)->setValue($excel_text_bill_net);

$excel_bill_net = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$excel_bill_net->createTextRun(textMoney($bill['PRICE']))
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell('F' . $rowexcel)->setValue($excel_bill_net);
$spreadsheet->getActiveSheet()->getRowDimension($rowexcel)->setRowHeight(8, "mm");

#
# ส่วนลด
$rowexcel = $rowexcel + 1;
$excel_text_bill_discount = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$excel_text_bill_discount->createTextRun($text_bill_discount)
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell('E' . $rowexcel)->setValue($excel_text_bill_discount);

$excel_bill_discount = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$excel_bill_discount->createTextRun(textMoney($bill['DISCOUNT']))
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell('F' . $rowexcel)->setValue($excel_bill_discount);
$spreadsheet->getActiveSheet()->getRowDimension($rowexcel)->setRowHeight(8, "mm");

#
# deposit
$rowexcel = $rowexcel + 1;
$excel_text_bill_deposit = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$excel_text_bill_deposit->createTextRun($text_bill_deposit)
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell('E' . $rowexcel)->setValue($excel_text_bill_deposit);

$excel_bill_deposit = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$excel_bill_deposit->createTextRun(textMoney($total_deposit))
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell('F' . $rowexcel)->setValue($excel_bill_deposit);
$spreadsheet->getActiveSheet()->getRowDimension($rowexcel)->setRowHeight(8, "mm");

#
# total
$rowexcel = $rowexcel + 1;
$excel_text_bill_total = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$excel_text_bill_total->createTextRun($text_bill_total)
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell('E' . $rowexcel)->setValue($excel_text_bill_total);

$excel_bill_total = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$excel_bill_total->createTextRun($net)
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell('F' . $rowexcel)->setValue($excel_bill_total);
$spreadsheet->getActiveSheet()->getRowDimension($rowexcel)->setRowHeight(8, "mm");

#
# assign
$rowexcel = $rowexcel + 1;
$spreadsheet->getActiveSheet()->mergeCells($cl_s . $rowexcel . ':C' . $rowexcel);
$excel_text_bill_by = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$excel_text_bill_by->createTextRun($text_bill_by)
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell($cl_s . $rowexcel)->setValue($excel_text_bill_by);
$spreadsheet->getActiveSheet()->getStyle($cl_s . $rowexcel . ':' . $cl_s . $rowexcel)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$spreadsheet->getActiveSheet()->getStyle($cl_s . $rowexcel . ':' . $cl_s . $rowexcel)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$spreadsheet->getActiveSheet()->getRowDimension($rowexcel)->setRowHeight(35, "mm");
$spreadsheet->getActiveSheet()->getStyle($cl_s . $rowexcel . ':C' . $rowexcel)->applyFromArray($styleBorderOut);

$spreadsheet->getActiveSheet()->mergeCells('D' . $rowexcel . ':' . $cl_e . $rowexcel);
$excel_text_bill_customer = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$excel_text_bill_customer->createTextRun($text_bill_customer)
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell('D' . $rowexcel)->setValue($excel_text_bill_customer);
$spreadsheet->getActiveSheet()->getStyle('D' . $rowexcel . ':D' . $rowexcel)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$spreadsheet->getActiveSheet()->getStyle('D' . $rowexcel . ':D' . $rowexcel)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$spreadsheet->getActiveSheet()->getStyle('D' . $rowexcel . ':' . $cl_e . $rowexcel)->applyFromArray($styleBorderOut);

#
# Style setting
$spreadsheet->getActiveSheet()->getStyle($cl_s . '1:' . $cl_e . '1')->applyFromArray($styleHeading);
// $spreadsheet->getActiveSheet()->getStyle($cl_s . $r_s . ':'.$cl_e.$rowexcel)->applyFromArray($styleGeneral);

$spreadsheet->getActiveSheet()->getStyle($cl_s . $start . ':' . $cl_e . $rowexcel)->applyFromArray($styleBorderOut);

#
#	wraptext (show text non-over column width)
$spreadsheet->getActiveSheet()->getStyle("A" . $r_s . ":F" . $rowexcel)->getAlignment()->setWrapText(true);

# set size row and column
$spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(35, 'mm');

#	set sheet name
$spreadsheet->getActiveSheet()->setTitle(date('Y-m-d'));

#	set font default excel
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
$spreadsheet->getDefaultStyle()->getFont()->setSize(8);

#	set alignment
$spreadsheet->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);

#	set margin
$spreadsheet->getActiveSheet()->getPageMargins()->setTop(0.75);
$spreadsheet->getActiveSheet()->getPageMargins()->setRight(0.25);
$spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0.25);
$spreadsheet->getActiveSheet()->getPageMargins()->setBottom(0.75);

#   set page rotate
/* $spreadsheet->getActiveSheet()->getPageSetup()
    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE); */

#   set page size
$spreadsheet->getActiveSheet()->getPageSetup()
    ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

#	protection
// $spreadsheet->getActiveSheet()->getProtection()->setSheet(false);

//
//	setting
$filename = "q-" . $bill_code . "-" . date('Y-m-d') . ".xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
// header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: cache, must-revalidate');
header('Pragma: public');
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
//	for clear bug when export file error extension not valid
for ($i = 0; $i < ob_get_level(); $i++) {
    ob_end_flush();
}
ob_implicit_flush(1);
ob_clean();
//
$writer->save('php://output');
