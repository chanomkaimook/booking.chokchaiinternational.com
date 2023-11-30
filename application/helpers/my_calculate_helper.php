<?php
error_reporting(E_ALL & ~E_NOTICE);

/**
 * find percent from total
 *
 * @param integer $total
 * @param integer $net
 * @param integer $format
 * @return void
 */
function get_percentFromTotal( $total = 0,  $net = 0,  $format = 2)
{
  $result = 0;
  $subfix = '%';
  if ($net != 0) {
    $point = ($total / $net) * 100;
  }

  $result = number_format($point, $format) . $subfix;

  if ($format === 0) {
    $result = number_format($point);
  }

  // prevent 100.00
  if($point == 100){
    $result = $point. $subfix;
  }


  return $result;
}
