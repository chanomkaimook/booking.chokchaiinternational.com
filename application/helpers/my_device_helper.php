<?php
error_reporting(E_ALL & ~E_NOTICE);

function is_Mobile()
{
  $ci = &get_instance();
  $ci->load->database();
  # code...

  include FCPATH . "mobile_detect.php";
  $ci->_detect = new Mobile_Detect();

  return $ci->_detect->isMobile();
}
