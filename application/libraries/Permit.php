<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Permit
{

	private $table = 'permit_control';
	private $permit;

	private $control;
	private $roles;
	private $userlogin;

	public function __construct()
	{
		//=	 call database	=//
		$this->ci = &get_instance();
		$this->ci->load->database();
		//===================//
		$this->ci->load->model('mdl_permit');
		$this->ci->load->model('mdl_permit_control');
		$this->ci->load->model('mdl_roles_control');

		$this->permit = $this->ci->mdl_permit;
		$this->control = $this->ci->mdl_permit_control;
		$this->roles = $this->ci->mdl_roles_control;

		$this->userlogin = $this->ci->session->userdata('user_code');
	}

	/**
	 * get data set for authentication
	 *
	 * @param integer|null $staff_id
	 * @return array $result = [role_id,permit_id,permit_name]
	 */
	function get_dataPermitSet(int $staff_id = null)
	{
		// variable for ban or allow
		$permit_allow = [];
		$permit_ban = [];

		// variable for data set
		$roles_id_list = [];
		$roles_name_list = [];

		$permit_id_list = [];
		$permit_name_list = [];
		$menu_name_list = [];


		if (!$staff_id) {
			$staff_id = $this->userlogin;
		}

		if ($staff_id) {
			$query_permit = $this->control->get_dataStaff($staff_id);

			if ($query_permit) {
				foreach ($query_permit as $row_permit) {
					if ($row_permit->ROLES_ID) {
						$role_id = $row_permit->ROLES_ID;
						//
						// value 1 = administrator
						if ($role_id == 1) {
							$roles_id_list[] =  "1";
							$roles_name_list[] =  "administrator";
						}

						$q_in_1 = $this->roles->get_dataRoles($role_id);

						if ($q_in_1) {
							foreach ($q_in_1 as $row_in_1) {
								if ($row_in_1->CODE) {
									$roles_id_list[] =  $row_in_1->ROLES_ID;
									$roles_name_list[] =  $row_in_1->ROLES_CODE;
									$permit_id_list[] =  $row_in_1->PERMIT_ID;
									$permit_name_list[] = $row_in_1->CODE;
								}

								if ($row_in_1->MENUS_CODE) {
									$menu_name_list[] = $row_in_1->MENUS_CODE;
								}
							}
						}
					}	// end role list

					if ($row_permit->PERMIT_ID) {
						$permit_id = $row_permit->PERMIT_ID;
						$row_in_2 = $this->permit->get_data($permit_id);

						if ($row_in_2) {

							if ($row_in_2->CODE) {
								$permit_id_list[] =  $row_in_2->ID;
								$permit_name_list[] = $row_in_2->CODE;
							}

							if ($row_in_2->MENUS_CODE) {
								$menu_name_list[] = $row_in_2->MENUS_CODE;
							}
						}
					}	// end permit list
				}
			}
		}

		$result = array(
			'user_id'			=> $staff_id,
			'roles_id_list'		=> array_unique($roles_id_list),
			'roles_name_list'	=> array_unique($roles_name_list),
			'permit_id_list'	=> array_unique($permit_id_list),
			'permit_name_list'	=> array_unique($permit_name_list),
			'menu_name_list'	=> array_unique($menu_name_list)
		);

		return $result;
	}

	/**
	 * insert data user permit
	 *
	 * @param array|null $data = data[col=>value]
	 * @param integer|null $staff_id
	 * @return void
	 */
	function insert_data(array $data = null, int $staff_id = null)
	{
		if ($data) {
			$data['staff_id'] = $staff_id;

			return $this->control->insert_data($data);
		}
	}

	/**
	 * insert_batch data user permit
	 *	-> clear transection before insert
	 * 
	 * @param array|null $data = data[key] => array(col=>value)
	 * @param integer|null $staff_id
	 * @return void
	 */
	function insert_batch_data(array $data = null, int $staff_id = null)
	{
		foreach ($data as $key => $value) {
			$data[$key]['staff_id'] = $staff_id;
		}

		$q_staff = $this->control->get_dataStaff($staff_id);
		if ($q_staff) {
			$data_delete = array(
				'staff_id'	=> $staff_id
			);
			$this->control->delete_pure($data_delete);
		}

		return $this->control->insert_batch_data($data);
	}

	/**
	 * restore status user
	 *
	 * @param int|array $id = staff
	 * @return void
	 */
	function staff_restore($id = null)
	{
		$this->ci->load->model('mdl_staff');

		if ($id) {

			$data_array = array(
				'status'	=> 1
			);

			if (is_array($id)) {
				$text = implode(",", $id);

				$data_staff = array(
					'id in(' . $text . ')'	=> null
				);
				$this->ci->mdl_staff->update_data($data_array, $data_staff);
			} else {
				$data_staff = array(
					'id'	=> $id
				);
				$this->ci->mdl_staff->update_data($data_array, $data_staff);
			}
		}

		return true;
	}
}
