<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Roles
{

	private $table = 'roles';

	public function __construct()
	{
	}

	/**
	 * get roles data
	 *
	 * @param integer|null $id = roles_id from roles_control
	 * @return void
	 */
	function get_data(int $id = null, array $optional = null, $type = "result_array")
	{
		//=	 call database	=//
		$ci = &get_instance();
		$ci->load->database();
		//===================//

		$result = [];

		if ($id) {
			$ci->load->model('mdl_roles_control');

			$result = $ci->mdl_roles_control->get_dataRoles($id, $optional, $type);
		}

		return $result;
	}

	/**
	 * data status
	 *
	 * @param string $select
	 * @param array $optional = [column=>value]
	 * @param string $order = 'column sort,column sort'
	 * @param integer|null $limit
	 * @param integer $start
	 * @return void
	 */
	function get_dataJS(String $select = '*', array $optional = [], String $order = 'id asc', int $limit = null, int $start = 0)
	{
		//=	 call database	=//
		$ci = &get_instance();
		$ci->load->database();
		//===================//
		$ci->load->model('mdl_permit');

		$result = array();

		$sql = $ci->mdl_permit->get_dataJoinMenus(null, $optional, 'result_array');
		$result = $this->get_jsTree($sql);

		return $result;
	}

	/**
	 * set data from data query for create jstree
	 *
	 * @param array $data = [index => [column => value]]
	 * @return void
	 */
	function get_jsTree(array $data = [])
	{
		$array_permit = array();

		$array_group = array_unique(array_column($data, 'MENUS_CODE'));
		if ($array_group) {
			foreach ($array_group as $g_index => $g_value) {
				// find permit have menu_id = g_index
				$array_list = array_keys(array_column($data, 'MENUS_CODE'), $g_value);

				if ($array_list) {
					foreach ($array_list as $l_index => $l_value) {
						$array_permit[$g_value][] = $data[$l_value];
					}
				}
			}
		}

		$result = $array_permit;

		return $result;
	}

	function get_status_document()
	{
		$optional = array(
			'alias'	=> 'document'
		);

		$result = $this->get_data(false, $optional);

		return $result;
	}

	/**
	 * get roles data
	 *
	 * @param integer|array $id = roles_id from roles_control
	 * @return void
	 */
	function get_dataRolesGroup($id = null, array $optional = null, $type = "result")
	{
		//=	 call database	=//
		$ci = &get_instance();
		$ci->load->database();
		//===================//

		$result = [];

		if ($id) {
			$ci->load->model('mdl_roles_control');
			$optional['group_by'] = array('roles.CODE');

			$result = $ci->mdl_roles_control->get_dataRolesOnly($id, $optional, $type);
		}

		return $result;
	}

	/**
	 * get roles data
	 *
	 * @param integer|array $id = roles_id from roles_control
	 * @return void
	 */
	function get_dataRolesJS($id = null, array $optional = null, $type = "result")
	{
		//=	 call database	=//
		$ci = &get_instance();
		$ci->load->database();
		//===================//

		$result = [];

		if ($id) {
			$ci->load->model('mdl_roles_control');

			$sql = $ci->mdl_roles_control->get_dataRoles($id, $optional, $type);
			$result = $this->get_jsTree($sql);
		}

		return $result;
	}

	/**
	 * get permit data in roles child
	 *
	 * @param integer|array $id = roles_id from roles_control
	 * @return void
	 */
	function get_dataRolesChildJS($id = null, array $optional = null, $type = "result")
	{
		//=	 call database	=//
		$ci = &get_instance();
		$ci->load->database();
		//===================//

		$result = [];

		if ($id) {
			$ci->load->model('mdl_roles_control');

			$sql = $ci->mdl_roles_control->get_dataRolesChild_permit($id, $optional, $type);
			$result = $this->get_jsTree($sql);
		}

		return $result;
	}

	/**
	 * get roles child data 
	 *
	 * @param integer|array $id = roles_id from roles_control
	 * @return void
	 */
	function get_dataRolesChild($id = null, array $optional = null, $type = "result")
	{
		//=	 call database	=//
		$ci = &get_instance();
		$ci->load->database();
		//===================//

		$result = [];

		if ($id) {
			$ci->load->model('mdl_roles_control');

			$result = $ci->mdl_roles_control->get_dataRolesChild($id, $optional, $type);
		}

		return $result;
	}

	/**
	 * get permit only on permit control
	 *
	 * @param integer|array $id = staff id
	 * @return void
	 */
	function get_dataPermitOnly($id = null, array $optional = null, $type = "result")
	{
		//=	 call database	=//
		$ci = &get_instance();
		$ci->load->database();
		//===================//

		$result = [];
		$p_array = [];

		if ($id) {
			$ci->load->model('mdl_permit');
			$optional_in['where'] = array(
				'permit_control.roles_id is null'	=> null
			);
			$q = $ci->mdl_permit_control->get_dataStaff($id,$optional_in,'result_array');
			if($q){
				foreach($q as $row){
					if($row['PERMIT_ID']){
						$p_array[] = $row['PERMIT_ID'];
					}
				}
			}

			if(count($p_array)){
				$result = $ci->mdl_permit->get_dataPermit($p_array, $optional, $type);
			}
			
		}

		return $result;
	}
}
