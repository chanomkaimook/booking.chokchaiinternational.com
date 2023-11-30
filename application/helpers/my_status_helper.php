<?php
error_reporting(E_ALL & ~E_NOTICE);

/**
 * data table status 
 *
 * @param integer|null $id
 * @param array|null $array = array(a,b,c)
 * @return void
 */
function status(int $id = null,array $array = null)
{
  # code...
  $ci = &get_instance();
  $ci->load->database();

  $sql = $ci->db->from('status_alias')
  ->where('status',1);

  if($id){
    $sql->where('id',$id);
  }

  $query = $sql->get();

    if($id){
      $result = $query->row();
    }else{
      $result = $query->result();
    }

  return (object) $result;
}

function status_waite()
{
  # code...
  $ci = &get_instance();
  $ci->load->database();
  
  $status = (object) status(1,array('document'));

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
  
  $status = (object) status(4,array('document'));

  $result = array(
    'id'    => $status->ID,
    'name'  => $status->NAME,
  );

  return $result;
}
