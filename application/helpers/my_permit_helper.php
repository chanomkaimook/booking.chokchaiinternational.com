<?php
error_reporting(E_ALL & ~E_NOTICE);
#
# Function recommended
# 
# data information for permit
# 'user_id'			      => $staff_id,
# 'roles_id_list'		  => array(roles id),
# 'permit_id_list'	  => array(permit id),
# 'permit_name_list'	=> array(permit name),
# 'menu_name_list'	  => array(menu name),
# 
# // check permit admin or master admin
# check_admin()
# 
# // check role roles_name_list & roles_id_list
# check_role()
#
# // check permit permit_name_list & permit_id_list
# check_permit()
#
# // check permit menu_name_list for nav menu bar
# check_permit_groupmenu()
# check_permit_menu()
#
# // check permit & role all
# check_data()
#

# // check permit master admin only
# check_masterAdminRole()
#
# // check permit admin only
# check_adminRole()
#

# Menu
# // check permit by paramiter is path or menu name (nav menu bar)
# can()
# 

/**
 * check role data
 *
 * @param array|string|null $role_name
 * @param integer|null $staff_id = It's will be user login if not found
 * @param array|null $dataarray = if setting this value will not get get_dataPermitSet
 * @return void
 */
function check_role($role_name = null, int $staff_id = null, array $dataarray = null)
{
  $ci = &get_instance();
  $ci->load->database();

  # code...
  $result = false;

  $nameselect = 'roles_name_list';

  if (check_admin()) {
    $result = true;
  } else {
    $result = method_find_checkarray($role_name, $staff_id, $nameselect, $dataarray);
  }

  return $result;
}

/**
 * check permit data
 *
 * @param array|string|null $permit_value = module/controler/method || permit id
 * @param integer|null $staff_id
 * @param array|null $dataarray = if setting this value will not get get_dataPermitSet
 * @return void
 */
function check_permit($permit_value = null, int $staff_id = null, array $dataarray = null)
{
  $ci = &get_instance();
  $ci->load->database();

  # code...
  $result = false;

  $nameselect = "permit_name_list";

  if (check_admin()) {
    $result = true;
  } else {
    $result = method_find_checkarray($permit_value, $staff_id, $nameselect, $dataarray);
  }

  return $result;
}

/**
 * check permit data for menu
 *
 * @param array|string|null $permit_value = module/controler/method
 * @param integer|null $staff_id
 * @return void
 */
/* function check_menu($menu_value = null, int $staff_id = null)
{
  $ci = &get_instance();
  $ci->load->database();

  # code...
  $result = false;

  if (!$staff_id) {
    $staff_id = userlogin();
  }

  $nameselect = "menu_name_list";

  if (check_admin()) {
    $result = true;
  } else {
    $result = method_find_checkarray($menu_value, $staff_id, $nameselect);
  }

  return $result;
} */

/**
 * check permit data for url
 *
 * @param array|string|null $permit_value = module/controler/method
 * @param integer|null $staff_id
 * @param string $type = default permit_name_list || permit_id_list
 * @param array|null $dataarray = if setting this value will not get get_dataPermitSet
 * @return void
 */
function check_data($url_value = null, int $staff_id = null, string $nameselect = null, array $dataarray = null)
{
  $ci = &get_instance();
  $ci->load->database();

  # code...
  $result = false;

  if (!$nameselect) {
    $nameselect = 'permit_name_list';
  }

  if (check_admin()) {
    $result = true;
  } else {
    $result = method_find_checkarray($url_value, $staff_id, $nameselect, $dataarray);
  }

  return $result;
}

/**
 * sub function for function check_xxx()
 *
 * @param array|string|null $name = value
 * @param integer|null $staff_id
 * @param string $key_array_name = key on permit array
 * @param array|null $dataarray = if setting this value will not get get_dataPermitSet
 * @return boolean
 */
function method_find_checkarray($name = null, int $staff_id = null, string $key_array_name = "", array $dataarray = null)
{
  $ci = &get_instance();

  $result = false;

  if (!$staff_id) {
    $staff_id = userlogin();
  }

  if ($staff_id && $key_array_name) {

    $ci->load->library('Permit');

    if ($dataarray) {
      $data_permit = $dataarray;
    } else {
      $data_permit = $ci->permit->get_dataPermitSet($staff_id);
    }
    $array_permitset = $data_permit[$key_array_name];

    // array permit id list
    $array_permitset_id = $data_permit['permit_id_list'];
    $text_permit_id = "";
    if ($array_permitset_id) {
      $text_permit_id = implode(",", $array_permitset_id);
    }

    if (is_array($name)) {
      // loop
      foreach ($name as $value) {
        if ($result != true) {
          if (is_numeric(array_search($value, $array_permitset))) {
            $result = true;
          }

          //
          // check permit in role
          //
          /* if ($query = query_permit($value, $text_permit_id)) {
            $result = true;
          } */
        }
      }
    } else {
      if ($name) {
        if (is_numeric(array_search($name, $array_permitset))) {
          $result = true;
        }

        //
        // check permit in role
        //
        /* if ($query = query_permit($name, $text_permit_id)) {
          $result = true;
        } */
      }
    }
  }

  return $result;
}

/**
 * query for find permit from role
 *
 * @param string $code = role code
 * @param string $text = permit id in($text)
 * @return void
 */
function query_permit(string $code = null, string $text = null)
{
  $result = [];
  if ($code && $text) {
    $ci = &get_instance();

    $sql = $ci->db->from('roles_control')
      ->join('roles', 'roles.id=roles_control.roles_id', 'left')
      ->where('roles.code', $code)
      ->where('roles_control.permit_id in(' . $text . ')', null, false)
      ->get();
    $num = $sql->num_rows();
    if ($num) {
      $result = $sql->result();
    }
  }

  return $result;
}

/**
 * check user id 
 *
 * @param integer|null $id
 * @return void
 */
/* function check_user($id = null)
{
  $ci = &get_instance();
  $ci->load->database();

  # code...
  $result = false;
  $user_login = $ci->session->userdata('user_code');

  if ($id) {
    // convert json data permit to array
    $data_permit = (array)json_decode($ci->caching->get('permit_' . $user_login));

    if (is_array($id) && count($id)) {
      foreach ($id as $value) {
        if ($result != true) {
          $result = method_find_user($value, $data_permit, $user_login);
        }
      }
    } else {
      $result = method_find_user($id, $data_permit, $user_login);
    }
  }

  return $result;
}

function method_find_user($id, $data_permit, $userlogin)
{
  //
  // caching
  if ($data_permit['user_id']) {
    if ($data_permit['user_id'] == $id) {
      $result = true;
    }
  } else {

    if ($userlogin && $userlogin == $id) {
      $result = true;
    }
  }

  return $result;
} */

// 
// Begin small function
// 
/**
 * check admin
 *
 * @param integer|null $staff_id
 * @return void
 */
function check_admin(int $staff_id = null)
{
  $ci = &get_instance();
  $ci->load->database();
  # code...

  $result = false;

  // master admin
  if (check_masterAdminRole()) {
    $result = true;
  } else {
    // administrator
    if (check_adminRole()) {
      $result = true;
    }
  }

  return $result;
}

/**
 * check value name with role
 * check value name with permit
 *
 * @param array|string|null $name = value
 * @param array|null $dataarray = if setting this value will not get get_dataPermitSet
 * @return boolean
 */
function can($name = null, array $dataarray = null)
{
  $ci = &get_instance();

  $result = false;

  if (!$dataarray) {
    $staff_id = userlogin();
    $dataarray = $ci->permit->get_dataPermitSet($staff_id);
  }

  if ($name) {
    if (is_array($name)) {
      // loop
      foreach ($name as $value) {
        if ($result != true) {
          if (method_can($value, $dataarray)) {
            $result = true;
          }
        }
      }
    } else {
      if (method_can($name, $dataarray)) {
        $result = true;
      }
    }
  }


  return $result;
}

/**
 * sub function for function can()
 *
 * @param string|null $name
 * @param array|null $dataarray = if setting this value will not get get_dataPermitSet
 * @return boolean
 */
function method_can(string $name = null, array $dataarray = null)
{
  $result = false;

  if ($name) {
    // convert value to array
    $value = explode(".", $name);

    if (count($value) > 1) {
      //
      // value = permit
      //

      if (check_permit($name, null, $dataarray)) {
        $result = true;
      }
    } else {

      //
      // value = menu
      //
      // TODO 
      // - check_role

      $value = (string) $value[0];

      if (!$result) {
        if (check_role($value, null, $dataarray)) {
          $result = true;
        }
      }
    }
  } else {
    $result = true;
  }

  return $result;
}
// 
// End small function
// 

// 
// Begin core function
//

/**
 * defind data user
 *
 * @return int = user id
 */
function userlogin()
{
  $ci = &get_instance();
  $ci->load->database();
  # code...

  $result = "";

  if ($ci->session->userdata('user_code')) {
    $result = $ci->session->userdata('user_code');
  }

  return $result;
}

/**
 * check menu permit for group menu
 *
 * @param array|null $array = role name || permit name 
 * @return void
 */
function check_permit_groupmenu($array = null)
{

  if (is_array($array)) {

    $ci = &get_instance();

    $ci->load->library('Permit');

    $result = false;

    $staff_id = userlogin();

    if ($staff_id) {


      $dataarray = $ci->permit->get_dataPermitSet($staff_id);

      // loop
      foreach ($array as $key => $array_in) {

        if ($array_in && count($array_in)) {
          foreach ($array_in as $value) {
            if ($result != true) {
              if (method_can($value, $dataarray)) {
                $result = true;
              }
            }
          }
        }
      }
    }
  }

  $css_name = '';

  if (!$result) {
    $css_name = 'd-none';
  }

  return $css_name;
}

/**
 * check menu permit
 *
 * @param array|string|null $name = role name || permit name 
 * @return void
 */
function check_permit_menu($name = null)
{
  $ci = &get_instance();

  $css_name = '';
  $result = '';

  if (userlogin()) {
    $result = can($name);
  }

  if (!$result) {
    $css_name = 'd-none';
  }

  return $css_name;
}

function check_userlive()
{
  $ci = &get_instance();
  $ci->load->database();
  # code...

  $result = true;

  $userlogin = userlogin();
  if ($userlogin) {

    $sql = $ci->db->from('staff')
      ->where('id', $userlogin)
      ->where('(status not in(1,2,3,4,5) or verify is null)', null, false)
      ->get();
    $num = $sql->num_rows();

    if ($num) {
      $result = false;
    }
  }

  return $result;
}

function check_adminRole(int $staff_id = null)
{
  $ci = &get_instance();
  $ci->load->database();
  # code...

  $result = false;

  if (!$staff_id) {
    $staff_id = userlogin();
  }

  $ci->load->library('Permit');

  $data_permit = $ci->permit->get_dataPermitSet($staff_id);

  $array_permitset = $data_permit['roles_name_list'];

  if (is_numeric(array_search("administrator", $array_permitset))) {
    $result = true;
  }

  return $result;
}

function check_masterAdminRole(int $staff_id = null)
{
  $ci = &get_instance();
  $ci->load->database();
  # code...

  $result = false;

  if (!$staff_id) {
    $staff_id = userlogin();
  }

  // role masteradmin
  $data = $ci->config->item('masteradmin_id');

  if ($data) {
    if (is_array($data) && count($data)) {
      // loop
      if (is_numeric(array_search($staff_id, $data))) {
        $result = true;
      }
    } else {

      if ($staff_id == $data) {
        $result = true;
      }
    }
  }

  return $result;
}


function my_permit(int $staff_id = null)
{
  $ci = &get_instance();
  $ci->load->database();
  # code...

  if (!$staff_id) {
    $staff_id = userlogin();
  }

  $ci->load->library('Permit');
  $result = $ci->permit->get_dataPermitSet($staff_id);

  return $result;
}
// 
// End core function
// 