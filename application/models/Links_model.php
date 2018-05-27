<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Links_model extends CI_Model
{
    public function qLinks()
    {
        // имя таблицы
        $t = $this->config->item('t_prefix').'links';
        $q = "SELECT COUNT(*) FROM `{$t}`";
        $query = $this->db->query($q);
        $queryRes = $query->result_array();
        //var_dump($queryRes);
        return $queryRes[0]["COUNT(*)"];
    }
    public function getLink()
    {
        // имя таблицы
        $t = $this->config->item('t_prefix').'links';
        $q = "SELECT * FROM `{$t}` LIMIT 1";
        $query = $this->db->query($q);
        if ($query->num_rows() > 0){
            $queryRes = [];
            $queryRes = $query->result_array();
            $queryRes = $queryRes[0];
        } else $queryRes = false;
        return $queryRes;
    }
    public function deleteLink($id='')
    {
        $t = $this->config->item('t_prefix').'links';
        $q = "DELETE FROM `{$t}` WHERE `id` = {$id}";
        $query = $this->db->query($q);
    }
    public function addLink($link='')
    {
        $t = $this->config->item('t_prefix').'links';
        $ct = date('Y-m-d H:i:s');
        $q = "INSERT INTO `{$t}` (`id`, `link`, `addition_date`) VALUES (NULL, '{$link}',
                '{$ct}')";
        $query = $this->db->query($q);
    }

}