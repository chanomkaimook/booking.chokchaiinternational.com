<?php
error_reporting(E_ALL & ~E_NOTICE);

/**
 * status payment
 *
 * @param string $type = [pending,deposit,success]
 * @return void
 */
function payment(string $type = 'pending')
{
  # code...
  $result = '';

  switch ($type) {
    case 'success':
      $result = status_payment(8);
      break;
    case 'deposit':
      $result = status_payment(7);
      break;
    default:
      $result = status_payment(6);
      break;
  }

  return $result;
}

/**
 * data table status 
 *
 * @param integer|null $id
 * @param array|null $array = array(a,b,c)
 * @return void
 */
function status(int $id = null, array $array = null)
{
  # code...
  $ci = &get_instance();
  $ci->load->database();

  $sql = $ci->db->from('status_alias')
    ->where('status', 1);

  if ($id) {
    $sql->where('id', $id);
  }

  $query = $sql->get();

  if ($id) {
    $result = $query->row();
  } else {
    $result = $query->result();
  }

  return (object) $result;
}

function status_document($id = null)
{
  # code...
  $result = '';

  if ($id) {
    $ci = &get_instance();
    $ci->load->database();

    $status = (object) status($id, array('document'));

    $result = $status->NAME;
  }

  return $result;
}

function status_payment($id = null)
{
  # code...
  $result = '';

  if ($id) {
    $ci = &get_instance();
    $ci->load->database();

    $status = (object) status($id, array('payment'));

    $result = $status->NAME;
  }

  return $result;
}

function status_waite()
{
  # code...
  $ci = &get_instance();
  $ci->load->database();

  $status = (object) status(1, array('document'));

  $result = array(
    'id'    => $status->ID,
    'name'  => $status->NAME,
  );

  return $result;
}

function status_delete()
{
  # code...
  $ci = &get_instance();
  $ci->load->database();

  $status = (object) status(4, array('document'));

  $result = array(
    'id'    => $status->ID,
    'name'  => $status->NAME,
  );

  return $result;
}
