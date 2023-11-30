<?php
error_reporting(E_ALL & ~E_NOTICE);

/**
 * check text or replace text
 *
 * @param String|null $text
 * @param String|null $replace
 * @return void
 */
function textShow(String $text = null, String $replace = null)
{
  # code...
  $result = trim($text) ? trim($text) : '';

  if ($replace && !$result) {
    $result = $replace;
  }

  return $result;
}

/**
 * text to show
 * work on cookie for display language
 *
 * @param String|null $text_th
 * @param String|null $text_en
 * @param boolean $switch = true is display variable not null
 * @return void
 */
function textLang(String $text_th = null, String $text_en = null, bool $switch = true)
{
  $ci = &get_instance();
  $ci->load->database();

  $ci->load->helper('cookie');

  $result = textNull($text_th);
  if (get_cookie('langadmin')) {
    $lang = get_cookie('langadmin');
    if ($lang != 'thai') {
      $result = textNull($text_en);
    }
  }

  // 
  // if variable switch is true
  // switch result thai if variable is null
  if ($switch && $result == "") {
    $result = textShow($text_th) ? textShow($text_th) : textShow($text_en);
  }

  return $result;
}

/**
 * check null
 *
 * @param String|null $text
 * @return void
 */
function textNull(String $text = null)
{
  # code...
  $result = null;

  if (trim($text)) {
    if ($text != "null" && $text != "NULL") {
      $result = trim($text);
    }
  }

  return $result;
}

if (!function_exists('mb_ucfirst')) {
  function mb_ucfirst($str, $encoding = "UTF-8", $lower_str_end = false)
  {
    $first_letter = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding);
    $str_end = "";
    if ($lower_str_end) {
      $str_end = mb_strtolower(mb_substr($str, 1, mb_strlen($str, $encoding), $encoding), $encoding);
    } else {
      $str_end = mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);
    }
    $str = $first_letter . $str_end;
    return $str;
  }
}
