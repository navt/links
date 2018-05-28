<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Workers_model extends CI_Model
{
    private $table;  // полное имя таблицы
    
    public function __construct() {
        parent::__construct();
        $this->table = $this->config->item('t_prefix').'workers';
    }
    // SELECT
    public function workersData($field='', $value='')
    {
        $q = "SELECT `id`,`email`,`hash`
            FROM `{$this->table}`
            WHERE `{$field}` = '{$value}' LIMIT 1";
        $query = $this->db->query($q);
        if ($query->num_rows() > 0){
            $qr = $query->result_array();
            $queryRes = $qr[0];
            // добавляем ещё сведения об ip, user agent
            $queryRes['user_agent'] = $this->input->user_agent();
            $queryRes['ip'] = $this->input->ip_address();
            // добавляем микс для создания/распознавания куки
            $queryRes['mix'] = md5($queryRes['email'].$queryRes['user_agent'].$queryRes['ip'].$this->config->item('encryption_key'));
        } else {
            $queryRes = false;
        }
        return $queryRes;
    }
    // INSERT
    public function addWorker($userName, $hash)
    {
        $q = "INSERT INTO `{$this->table}` (`id`, `email`, `hash`) VALUES (NULL, '{$userName}',
            '{$hash}')";
        $qr = $this->db->query($q);
        return $qr;
    }
}