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
function get_percentFromTotal($total = 0,  $net = 0,  $format = 2)
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
  if ($point == 100) {
    $result = $point . $subfix;
  }


  return $result;
}

function get_priceVat(float $price = null, int $vat_num = null)
{
  //=	 call database	=//
  $ci = &get_instance();

  if (!$vat_num) {
    $vat_num = $ci->config->item('vat_num');
  }

  $result = array();

  if ($vat_num && $price) {
    $price_beforevat = $price / 1.07;
    $vat = $price_beforevat * 0.07;
    $price_aftervat = $price_beforevat + $vat_num;

    $result = array(
      'vat'  => textFloat($vat),
      'vat_num'  => $vat_num,
      'before_vat'  => textFloat($price_beforevat),
      'after_vat'  => textFloat($price_aftervat)
    );
  }

  return $result;
}
