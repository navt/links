<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Workers_model extends CI_Model
{
	// SELECT
	public function workersData($email='')
	{
		// имя таблицы
		$t = $this->config->item('t_prefix').'workers';
		$q = "SELECT `id`,`email`,`hash`
			FROM `{$t}`
			WHERE `email` = '{$email}' LIMIT 1";
		$query = $this->db->query($q);
		if ($query->num_rows() > 0){
			$queryRes = [];
			$queryRes = $query->result_array();
                        $queryRes = $queryRes[0];
		} else $queryRes = false;
		return $queryRes;
	}
	// INSERT
        public function addWorker($userName, $hash)
	{
		$t = $this->config->item('t_prefix').'workers';
		$q = "INSERT INTO `{$t}` (`id`, `email`, `hash`) VALUES (NULL, '{$userName}',
			'{$hash}')";
		$query = $this->db->query($q);
		return $query;
	}
}