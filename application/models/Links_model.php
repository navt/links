<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Links_model extends CI_Model
{
    private $table;  // полное имя таблицы
    
    public function __construct() {
        parent::__construct();
        $this->table = $this->config->item('t_prefix').'links';
    }
    public function qLinks()
    {
        $q = "SELECT COUNT(*) FROM `{$this->table}`";
        $query = $this->db->query($q);
        $queryRes = $query->result_array();
        return $queryRes[0]["COUNT(*)"];
    }
    public function getLink()
    {
        $q = "SELECT * FROM `{$this->table}` LIMIT 1";
        $query = $this->db->query($q);
        if ($query->num_rows() > 0){
            $qr = $query->result_array();
            $queryRes = $qr[0];
        } else {
            $queryRes = false;
        }
        return $queryRes;
    }
    public function deleteLink($id='')
    {
        $q = "DELETE FROM `{$this->table}` WHERE `id` = {$id}";
        $qr = $this->db->query($q);
        return $qr;
    }
    public function addLink($link='')
    {
        $ct = date('Y-m-d H:i:s');
        $q = "INSERT INTO `{$this->table}` (`id`, `link`, `addition_date`) VALUES (NULL, '{$link}',
                '{$ct}')";
        $qr = $this->db->query($q);
        return $qr;
    }

}