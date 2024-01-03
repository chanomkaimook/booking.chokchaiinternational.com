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
$text_company = $q_setting->CN_NAME;
$text_company_id = $q_setting->CN_ID;
$text_company_address = $q_setting->CN_ADDRESS;
$text_company_textphone = $q_setting->CN_TEXT_PHONE;

$text_doc = "ใบเสร็จรับเงิน/ใบกำกับภาษี";
$text_doc_us = "RECEIPT / TAX INVOICE";

$write_array[] = array(
    "รายการ\nDescription",
    "จำนวน\nQuantity",
    "ราคาต่อหน่วย\nDescription",
    "จำนวนเงิน\nDescription",
);
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
// echo "<pre>";print_r($receipt);echo "</pre>";die();
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
$spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(15, "mm");

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
    ->getFont()->setBold(true)->setSize(10);
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
$book_number->createTextRun("054")
    ->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED))
    ->setSize(18);
$spreadsheet->getActiveSheet()->getCell($cl_s . $rowexcel)->setValue($book_number);

# booksub number
# merce row
$book_number = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$book_number->createTextRun("เลขที่ ")
    ->getFont()->setBold(true)->setSize(8);
$book_number->createTextRun("2658")
    ->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED))
    ->setSize(18);
$spreadsheet->getActiveSheet()->getCell("F" . $rowexcel)->setValue($book_number);
$spreadsheet->getActiveSheet()->getStyle('F' . $rowexcel)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

# detail customer 1
$rowexcel = $rowexcel + 1;
$spreadsheet->getActiveSheet()->mergeCells($cl_s . $rowexcel . ':E' . $rowexcel);

$cs_date = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$cs_date->createTextRun("ชื่อ ")
    ->getFont()->setSize(10);
$cs_date->createTextRun("Test Username");
// ->getFont()->setSize(14);
$spreadsheet->getActiveSheet()->getCell($cl_s . $rowexcel)->setValue($cs_date);

$cs_date = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$cs_date->createTextRun("วันที่ 11 กย. 66")
    ->getFont()->setSize(10);
$spreadsheet->getActiveSheet()->getCell("F" . $rowexcel)->setValue($cs_date);

#
# detail customer 2
$rowexcel = $rowexcel + 1;
$spreadsheet->getActiveSheet()->mergeCells($cl_s . $rowexcel . ':' . $cl_e . $rowexcel);

$cs_date = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
$cs_date->createTextRun("ที่อยู่ ............................................................................................................")
    ->getFont()->setSize(10);
// $cs_date->createTextRun("<span style='color:red'>asdasdasd</span>");
// ->getFont()->setSize(14);
$spreadsheet->getActiveSheet()->getCell($cl_s . $rowexcel)->setValue($cs_date);

/* #	creat text
	$rowexcel = $rowexcel+1;
	$richText->createText('About this ');
	$footer = $richText->createTextRun('document to secret for Senior officer farmchokchai');
	$footer->getFont()->setBold(true);
	$footer->getFont()->setItalic(true);
	$footer->getFont()->setColor( new \PhpOffice\PhpSpreadsheet\Style\Color( \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKGREEN ) );
	$richText->createText(', Do not share.');
	
	#	mergeCells
	$spreadsheet->getActiveSheet()->mergeCells($cl_s.$rowexcel.':'.$cl_e.$rowexcel);
	
	$spreadsheet->getActiveSheet()->getCell($cl_s.$rowexcel)->setValue($richText); */

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
$filename = "recipt_" . date('Y-m-d') . ".xlsx";
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
