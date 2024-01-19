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

$net_text_convert_th = $receipt['NET'] ? convertNumberToText($receipt['NET']) : null;

$text_doc = "ใบเสร็จรับเงิน/ใบกำกับภาษี";
$text_doc_us = "RECEIPT / TAX INVOICE";

$write_array[] = array(
    "รายการ\nDescription",
    "จำนวน\nQuantity",
    "ราคาต่อหน่วย\nDescription",
    "จำนวนเงิน\nDescription",
);

#	array set border
$styleBorder = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => '00000000'],
        ],
    ],
];

#	array set border
$styleBorderOut = [
    'borders' => [
        'outline' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => '00000000'],
        ],
    ],
];

#	style font bold
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

/* foreach($query as $row => $val){
		//	number
		$row++;
		//
		$q_staff_start = get_WhereParaSelect('name_th,lastname_th,name,lastname','staff','code',$val->bill_user_starts);
		$q_staff_update = get_WhereParaSelect('name_th,lastname_th,name,lastname','staff','code',$val->bill_user_update);
		$approve1 = ($q_staff_start->name_th ? $q_staff_start->name_th." ".$q_staff_start->lastname_th : $q_staff_start->name." ".$q_staff_start->lastname); 
		$approve2 = ($q_staff_update->name_th ? $q_staff_update->name_th." ".$q_staff_update->lastname_th : $q_staff_update->name." ".$q_staff_update->lastname); 
		
		$write_array[] = array(
							$row,
							date('d-m-Y',strtotime($val->bill_datetime)),
							$val->bill_code,
							$val->rtd_sum,

							$val->delivery_name,
							$val->method_name,

							$approve1,
							$approve2,

							$val->bill_remark
						);
	} */
// echo $this->db->last_query();
/* echo "<pre>";
print_r($bill);
echo "</pre>";
die(); */
$row++;
$last_row = $row;
$insertrow = 1;
// echo  count($write_array)."<br>"."<pre>";print_r($write_array);echo "</pre>";die();
//	
//	set style


$spreadsheet = new Spreadsheet();
$spreadsheet->setActiveSheetIndex(0);
// $spreadsheet->getActiveSheet()->fromArray($write_array,NULL,'A1');



# logo
$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$drawing->setName('Logo');
$drawing->setDescription('Logo');
$drawing->setPath("asset/images/logo/farmchokchai.png");
$drawing->setCoordinates($cl_s . '1');
$drawing->setHeight(50);
$drawing->setWorksheet($spreadsheet->getActiveSheet());

#
#	mergeCells
$rowexcel = $r_s;
$spreadsheet->getActiveSheet()->mergeCells($cl_s . $rowexcel . ':' . $cl_e . $rowexcel);
$spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(14, "mm");

$company = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$company->createTextRun($text_company)
    ->getFont()->setBold(true);
$company->createTextRun("\n" . $text_company_address . "\n" . $text_company_textphone)
    ->getFont()->setSize(8);
$spreadsheet->getActiveSheet()->getCell($cl_s . $rowexcel)->setValue($company);

#
#	mergeCells 3 columns
$rowexcel = $rowexcel + 1;
$spreadsheet->getActiveSheet()->mergeCells('A' . $rowexcel . ':B' . $rowexcel);
$spreadsheet->getActiveSheet()->mergeCells('C' . $rowexcel . ':D' . $rowexcel);
$spreadsheet->getActiveSheet()->mergeCells('E' . $rowexcel . ':F' . $rowexcel);
$spreadsheet->getActiveSheet()->getRowDimension($rowexcel)->setRowHeight(8, "mm");

$head_left = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$head_left->createTextRun("เลขประจำตัวผู้เสียภาษี \n")
    ->getFont()->setBold(true)->setSize(8);

# head left
$new_company_id = "";
if ($text_company_id) {
    $array_text = str_split($text_company_id);
    $new_company_id = implode(" ", $array_text);
}
$head_left->createTextRun($new_company_id)
    ->getFont()->setSize(8);
$spreadsheet->getActiveSheet()->getCell('A' . $rowexcel)->setValue($head_left);
$spreadsheet->getActiveSheet()->getStyle('A' . $rowexcel)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

# head center
$head_center = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$head_center->createTextRun($text_doc . "\n")
    ->getFont()->setBold(true)->setSize($font_size_general);
$head_center->createTextRun($text_doc_us)
    ->getFont()->setSize(8);
$spreadsheet->getActiveSheet()->getCell('C' . $rowexcel)->setValue($head_center);
$spreadsheet->getActiveSheet()->getStyle('C' . $rowexcel)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

# head right
$head_right = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$head_right->createTextRun("สาขาที่ออกใบกำกับภาษี คือ สาขาที่ 1 \n")
    ->getFont()->setBold(true)->setSize(8);
$spreadsheet->getActiveSheet()->getCell('E' . $rowexcel)->setValue($head_right);
$spreadsheet->getActiveSheet()->getStyle('E' . $rowexcel)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

# book number
$rowexcel = $rowexcel + 1;
$book_number = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$book_number->createTextRun("เล่มที่ ")
    ->getFont()->setBold(true)->setSize(8);
$book_number->createTextRun($bill_main)
    ->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED))
    ->setSize(18);
$spreadsheet->getActiveSheet()->getCell($cl_s . $rowexcel)->setValue($book_number);

# booksub number
# merce row
$book_number = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$book_number->createTextRun("เลขที่ ")
    ->getFont()->setBold(true)->setSize(8);
$book_number->createTextRun($bill_submain)
    ->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED))
    ->setSize(18);
$spreadsheet->getActiveSheet()->getCell("F" . $rowexcel)->setValue($book_number);
$spreadsheet->getActiveSheet()->getStyle('F' . $rowexcel)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

# detail customer 1
$rowexcel = $rowexcel + 1;
$spreadsheet->getActiveSheet()->mergeCells($cl_s . $rowexcel . ':E' . $rowexcel);

$cs_date = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$cs_date->createTextRun("ชื่อ ")
    ->getFont()->setSize($font_size_general);
$cs_date->createTextRun($cs_name);
// ->getFont()->setSize(14);
$spreadsheet->getActiveSheet()->getCell($cl_s . $rowexcel)->setValue($cs_date);

$cs_date = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$cs_date->createTextRun("วันที่ ".$bill_date)
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell("F" . $rowexcel)->setValue($cs_date);

#
# detail customer 2
$rowexcel = $rowexcel + 1;
$spreadsheet->getActiveSheet()->mergeCells($cl_s . $rowexcel . ':' . $cl_e . $rowexcel);

$cs_date = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$cs_date->createTextRun("ที่อยู่ .........................................................................................................................................................................................")
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell($cl_s . $rowexcel)->setValue($cs_date);

#
# detail customer 3
$rowexcel = $rowexcel + 1;
$spreadsheet->getActiveSheet()->mergeCells($cl_s . $rowexcel . ':C' . $rowexcel);
$cs_id = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$cs_id->createTextRun("เลขที่ประจำตัวผู้เสียภาษี .....................................................")
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell($cl_s . $rowexcel)->setValue($cs_id);

$chk_corp = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$chk_corp->createTextRun("( ) สำนักงานใหญ่")
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell('D' . $rowexcel)->setValue($chk_corp);

$spreadsheet->getActiveSheet()->mergeCells('E' . $rowexcel . ':' . $cl_e . $rowexcel);
$chk_branch = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$chk_branch->createTextRun("( ) สาขา ................................................")
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell('E' . $rowexcel)->setValue($chk_branch);

#
# detail item
$rowexcel = $rowexcel + 1;
$spreadsheet->getActiveSheet()->mergeCells($cl_s . $rowexcel . ':C' . $rowexcel);
$spreadsheet->getActiveSheet()->getRowDimension($rowexcel)->setRowHeight(8, "mm");

$item_head = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$item_head->createTextRun("รายการ\nDescription")
    ->getFont()->setSize(8);
$spreadsheet->getActiveSheet()->getCell($cl_s . $rowexcel)->setValue($item_head);

$item_head = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$item_head->createTextRun("จำนวน\nQuantity")
    ->getFont()->setSize(8);
$spreadsheet->getActiveSheet()->getCell("D" . $rowexcel)->setValue($item_head);

$item_head = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$item_head->createTextRun("ราคาต่อหน่วย\nUnit Price")
    ->getFont()->setSize(8);
$spreadsheet->getActiveSheet()->getCell("E" . $rowexcel)->setValue($item_head);

$item_head = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$item_head->createTextRun("จำนวนเงิน\nAmount")
    ->getFont()->setSize(8);
$spreadsheet->getActiveSheet()->getCell("F" . $rowexcel)->setValue($item_head);

$spreadsheet->getActiveSheet()->getStyle($cl_s . $rowexcel . ":" . $cl_e . $rowexcel)->applyFromArray($styleHeading);
$spreadsheet->getActiveSheet()->getStyle($cl_s . $rowexcel . ":" . $cl_e . $rowexcel)->applyFromArray($styleBorder);

#
# foreach border
for ($i = 0; $i < 8; $i++) {
    $rowexcel = $rowexcel + 1;
    $spreadsheet->getActiveSheet()->mergeCells($cl_s . $rowexcel . ':C' . $rowexcel);

    if ($bill['PAYMENT_ID'] && $bill['PAYMENT_ID'] == 7) {    // 7=มัดจำ
        if ($i == 0) {
            #
            # name
            $item_name = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
            $item_name->createTextRun("รับเงินมัดจำตะลอนฟาร์ม")
                ->getFont()->setSize($font_size_general);
            $spreadsheet->getActiveSheet()->getCell($cl_s . $rowexcel)->setValue($item_name);

            #
            # quantity
            $item_qty = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
            $item_qty->createTextRun("1")
                ->getFont()->setSize($font_size_general);
            $spreadsheet->getActiveSheet()->getCell('D' . $rowexcel)->setValue($item_qty);
            $spreadsheet->getActiveSheet()->getStyle('D' . $rowexcel)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            #
            # unit price
            $item_unit_price = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
            $item_unit_price->createTextRun($receipt['NET'])
                ->getFont()->setSize($font_size_general);
            $spreadsheet->getActiveSheet()->getCell('E' . $rowexcel)->setValue($item_unit_price);
            $spreadsheet->getActiveSheet()->getStyle('E' . $rowexcel)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            #
            # amount
            $item_amount = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
            $item_amount->createTextRun($receipt['NET'])
                ->getFont()->setSize($font_size_general);
            $spreadsheet->getActiveSheet()->getCell('F' . $rowexcel)->setValue($item_amount);
            $spreadsheet->getActiveSheet()->getStyle('F' . $rowexcel)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        }
    } else {
        if ($bill['item_list'] && $bill['item_list'][$i]) {
            $detail_item = $bill['item_list'][$i];

            #
            # name
            $item_name = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
            $item_name->createTextRun($detail_item->DESCRIPTION)
                ->getFont()->setSize($font_size_general);
            $spreadsheet->getActiveSheet()->getCell($cl_s . $rowexcel)->setValue($item_name);

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
            $spreadsheet->getActiveSheet()->getStyle('F' . $rowexcel)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        }
    }

    $spreadsheet->getActiveSheet()->getStyle($cl_s . $rowexcel . ":" . $cl_e . $rowexcel)->applyFromArray($styleBorder);
}

#
# detail item summary 1
$rowexcel = $rowexcel + 1;
$spreadsheet->getActiveSheet()->mergeCells($cl_s . $rowexcel . ':C' . $rowexcel);
$spreadsheet->getActiveSheet()->mergeCells('D' . $rowexcel . ':E' . $rowexcel);

$text_bill_code = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$text_bill_code->createTextRun("ออกแทนใบกำกับภาษีอย่างย่อเลขที่")
    ->getFont()->setSize(8);
$text_bill_code->createTextRun("110078,112200")
    ->getFont()->setSize(8);
$spreadsheet->getActiveSheet()->getCell($cl_s . $rowexcel)->setValue($text_bill_code);

$text_summary_1 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$text_summary_1->createTextRun("ราคาสินค้าได้รับยกเว้นภาษี")
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell('D' . $rowexcel)->setValue($text_summary_1);
$spreadsheet->getActiveSheet()->getStyle('D' . $rowexcel)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);


$item_summary_1 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$item_summary_1->createTextRun("-")
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell('F' . $rowexcel)->setValue($item_summary_1);
$spreadsheet->getActiveSheet()->getStyle('F' . $rowexcel)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
$spreadsheet->getActiveSheet()->getStyle('F' . $rowexcel)->applyFromArray($styleBorder);

#
# detail item summary 2
$rowexcel = $rowexcel + 1;
$spreadsheet->getActiveSheet()->mergeCells($cl_s . $rowexcel . ':C' . $rowexcel);
$spreadsheet->getActiveSheet()->mergeCells('D' . $rowexcel . ':E' . $rowexcel);

$text_bill_thai = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$text_bill_thai->createTextRun($net_text_convert_th)
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell($cl_s . $rowexcel)->setValue($text_bill_thai);
$spreadsheet->getActiveSheet()->getStyle($cl_s . $rowexcel . ":C" . $rowexcel)->applyFromArray($styleBorder);
$spreadsheet->getActiveSheet()->getStyle($cl_s . $rowexcel)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


$text_summary_2 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$text_summary_2->createTextRun("ราคาสินค้าที่เสียภาษี")
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell('D' . $rowexcel)->setValue($text_summary_2);
$spreadsheet->getActiveSheet()->getStyle('D' . $rowexcel)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);


$item_summary_2 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$item_summary_2->createTextRun($receipt['PRICE_NOVAT'])
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell('F' . $rowexcel)->setValue($item_summary_2);
$spreadsheet->getActiveSheet()->getStyle('F' . $rowexcel)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
$spreadsheet->getActiveSheet()->getStyle('F' . $rowexcel)->applyFromArray($styleBorder);

#
# detail item summary 3
$rowexcel = $rowexcel + 1;
$spreadsheet->getActiveSheet()->mergeCells($cl_s . $rowexcel . ':C' . $rowexcel);
$spreadsheet->getActiveSheet()->mergeCells('D' . $rowexcel . ':E' . $rowexcel);

$text_summary_3 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$text_summary_3->createTextRun("ภาษีมูลค่าเพิ่ม")
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell('D' . $rowexcel)->setValue($text_summary_3);
$spreadsheet->getActiveSheet()->getStyle('D' . $rowexcel)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);


$item_summary_3 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$item_summary_3->createTextRun($receipt['VAT'])
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell('F' . $rowexcel)->setValue($item_summary_3);
$spreadsheet->getActiveSheet()->getStyle('F' . $rowexcel)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
$spreadsheet->getActiveSheet()->getStyle('F' . $rowexcel)->applyFromArray($styleBorder);

#
# detail item summary 4
$rowexcel = $rowexcel + 1;
$spreadsheet->getActiveSheet()->mergeCells($cl_s . $rowexcel . ':D' . $rowexcel);
$text_sign = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$text_sign->createTextRun("ผู้รับเงิน ................................................................................................")
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell($cl_s . $rowexcel)->setValue($text_sign);
$spreadsheet->getActiveSheet()->getStyle($cl_s . $rowexcel)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


$text_summary_4 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$text_summary_4->createTextRun("รวมเงินทั้งสิ้น")
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell('E' . $rowexcel)->setValue($text_summary_4);
$spreadsheet->getActiveSheet()->getStyle('E' . $rowexcel)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);


$item_summary_4 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$item_summary_4->createTextRun($receipt['NET'])
    ->getFont()->setSize($font_size_general);
$spreadsheet->getActiveSheet()->getCell('F' . $rowexcel)->setValue($item_summary_4);
$spreadsheet->getActiveSheet()->getStyle('F' . $rowexcel)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
$spreadsheet->getActiveSheet()->getStyle('F' . $rowexcel)->applyFromArray($styleBorder);

#
# detail sign
$rowexcel = $rowexcel + 1;
$spreadsheet->getActiveSheet()->mergeCells($cl_s . $rowexcel . ':' . $cl_e . $rowexcel);
$text_sign = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$text_sign->createTextRun("ใบเสร็จรับเงินนี้จะสมบูรณ์ต่อเมื่อได้รับเงินหรือเช็คผ่านการเรียกเก็บเงินแล้ว")
    ->getFont()->setSize(8);
$spreadsheet->getActiveSheet()->getCell($cl_s . $rowexcel)->setValue($text_sign);
$spreadsheet->getActiveSheet()->getStyle($cl_s . $rowexcel)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

#	array set font
$styleHead = [
    /* 'font' => [
        'bold' => true,
        'name' => 'Arial',
        'size' => 8,
    ], */
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
    ]
];
$spreadsheet->getActiveSheet()->getStyle('A1:F1')->applyFromArray($styleHead);

$styleGeneral = [
    'alignment' => [
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
    ]
];
$spreadsheet->getActiveSheet()->getStyle('A' . $r_s . ':F2')->applyFromArray($styleGeneral);

#	array set alignment
$styleAlign = [
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
    ]
];
// $spreadsheet->getActiveSheet()->getStyle('A2:L'.$last_row)->applyFromArray($styleAlign);


// $spreadsheet->getActiveSheet()->getStyle('E2:E'.$last_row)->getAlignment()->setWrapText(true);
#
#	wraptext (show text non-over column width)
$spreadsheet->getActiveSheet()->getStyle("A" . $r_s . ":F" . $rowexcel)->getAlignment()->setWrapText(true);

$spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(35, 'mm');

#	set sheet name
$spreadsheet->getActiveSheet()->setTitle(date('Y-m-d'));

#	set default excel
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
$spreadsheet->getDefaultStyle()->getFont()->setSize(8);

#	protection
// $spreadsheet->getActiveSheet()->getProtection()->setSheet(false);

//
//	setting
$filename = "r-" . $bill_code . "-" . date('Y-m-d') . ".xlsx";
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
