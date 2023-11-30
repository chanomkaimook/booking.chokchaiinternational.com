<?php
error_reporting(E_ALL & ~E_NOTICE);

// convert date time
// reference language cookie
function toDateTimeString($date, $typereturn = null)
{
  $ci = &get_instance();
  $ci->load->database();

  $ci->load->helper('cookie');

  $lang = get_cookie('langadmin');
  if (!$lang || $lang == 'thai') {
    return toThaiDateTimeString($date, $typereturn);
  } else {
    return toEngDateTimeString($date, $typereturn);
  }
}
//	convert thai date
//	@param	date	@date = date yyyy-mm-dd
//	@param	typereturn	@text = [date , datetime]
//	return datetime TH
//
function toThaiDateTimeString($date, $typereturn = null)
{

  $thai_day_arr = array("อา", "จ", "อ", "พ", "พฤ", "ศ", "ส");
  $thai_month_arr = array(
    "00" => "",
    "01" => "ม.ค",
    "02" => "ก.พ",
    "03" => "มี.ค",
    "04" => "เม.ย",
    "05" => "พ.ค",
    "06" => "มิ.ย",
    "07" => "ก.ค",
    "08" => "ส.ค",
    "09" => "ก.ย",
    "10" => "ต.ค",
    "11" => "พ.ย",
    "12" => "ธ.ค"
  );

  $time = strtotime($date);
  $time_day = date("j", $time);
  $time_month = date("m", $time);
  $time_year = date("Y", $time);

  $thai_date_return = $time_day . " " . $thai_month_arr[$time_month] . " " . $time_year;
  $thai_time_return = date('H:i:s', $time);

  if ($typereturn == "datetime") {
    $result = $thai_date_return . " " . $thai_time_return;
  } else {
    $result = $thai_date_return;
  }

  return $result;
}


//	convert thai date
//	@param	date	@date = date yyyy-mm-dd
//	@param	typereturn	@text = [date , datetime]
//	return datetime US
//
function toEngDateTimeString($date, $typereturn = null)
{

  $day_arr = array("Su", "M", "T", "W", "Th", "F", "Sa");
  $month_arr = array(
    "00" => "",
    "01" => "Jan",
    "02" => "Feb",
    "03" => "Mar",
    "04" => "Apr",
    "05" => "May",
    "06" => "Jun",
    "07" => "Jul",
    "08" => "Aug",
    "09" => "Sep",
    "10" => "Oct",
    "11" => "Nov",
    "12" => "Dec"
  );

  $time = strtotime($date);
  $time_day = date("j", $time);
  $time_month = date("m", $time);
  $time_year = date("Y", $time);

  $date_return = $time_day . " " . $month_arr[$time_month] . " " . $time_year;
  $time_return = date('H:i:s', $time);

  if ($typereturn == "datetime") {
    $result = $date_return . " " . $time_return;
  } else {
    $result = $date_return;
  }

  return $result;
}

/**
 * return time
 *
 * @param [type] $time = 00:00:00 (H:i:s)
 * @param string $typereturn = 'H:i','H:i:s'
 * @return void
 */
function toTime($time = null, $typereturn = 'H:i')
{
  $result = "";

  if ($time) {
    $result = date($typereturn, strtotime($time));
  }

  return $result;
}

/**
 * calculate time
 *
 * @param [type] $strTime1 = 00:00
 * @param [type] $strTime2 = 00:00
 * @return void
 */
function TimeDiff($strTime1, $strTime2)
{
  return (strtotime($strTime2) - strtotime($strTime1)) /  (60 * 60); // 1 Hour =  60*60
}

/**
 * Week date range from date now
 *
 * @param string|null $dateset = Y-m-d
 * @return void
 */
function dateRange_fromDate(string $dateset = null)
{
  $start_week = '';
  $end_week = '';

  if ($dateset) {

    $date = new DateTime($dateset);
    $numberToWeek = (int) $date->format("N");
    if ($numberToWeek == 1) {
      $start_week = strtotime($dateset);
    } else {
      $previous_week = strtotime($dateset);
      $start_week = strtotime("last monday", $previous_week);
    }

    $end_week = strtotime("next sunday", $start_week);

    $start_week = date("Y-m-d", $start_week);
    $end_week = date("Y-m-d", $end_week);
  }

  $result = array(
    'start' => $start_week,
    'end' => $end_week,
  );

  return $result;
}

/**
 * Month date range from date now
 *
 * @param string|null $dateset = Y-m-d
 * @return void
 */
function monthRange_fromDate(string $dateset = null)
{
  $start = '';
  $end = '';

  if ($dateset) {

    $start = date("Y-m-01", strtotime($dateset));
    $end = date("Y-m-t", strtotime($dateset));
    // $end = new \DateTime('last day of this month');
  }

  $result = array(
    'start' => $start,
    'end' => $end,
  );

  return $result;
}

/**
 * Year date range from date now
 *
 * @param string|null $dateset = Y-m-d
 * @return void
 */
function yearRange_fromDate(string $dateset = null)
{
  $start = '';
  $end = '';

  if ($dateset) {

    $start = date("Y-01-01", strtotime($dateset));
    $end = date("Y-12-31", strtotime($dateset));
  }

  $result = array(
    'start' => $start,
    'end' => $end,
  );

  return $result;
}
