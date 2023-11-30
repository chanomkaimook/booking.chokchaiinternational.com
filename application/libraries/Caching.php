<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Caching
{
	public $name = "";
	public $ci = "";
	public $cache_expire_time = "";

	public function __construct()
	{
		//=	 call database	=//
		$this->ci = &get_instance();
		$this->ci->load->database();
		//===================//
		$this->ci->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
	}

	public function get(string $var = "")
	{
		$name = $var;
		$result = $this->ci->cache->get($name);
		return $result;
	}

	public function save(string $var = "", string $data, int $time = null)
	{
		$this->ci->load->config('jwt');

		if($time){
			$this->cache_expire_time = $time;
		}else{
			$this->cache_expire_time  = $this->ci->config->item('token_expire_time');
		}

		$name = $var;
		$result = $this->ci->cache->save($name, $data,$this->cache_expire_time);
		return $result;
	}

	public function info()
	{

		$result = $this->ci->cache->cache_info();
		return $result;
	}

	public function clean()
	{

		$result = $this->ci->cache->clean();
		return $result;
	}

	public function delete(string $var = "")
	{
		$name = $var;
		$result = $this->ci->cache->delete($name);
		return $result;
	}
}