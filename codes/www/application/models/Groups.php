<?php

class Groups extends CI_Model
{

    private $table = "groups";

    public function Groups()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getGroupByName($group_name) {
        return $this->db->get_where($this->table, array("group_name"=>$group_name));

    }

    public function getGroupByUid($moderator_id) {
         return $this->db->where(array("moderator_id"=>intval($moderator_id)))->get($this->table)->result_array();
    }

    public function getGroupById($id) {
        $data = $this->db->where(array("id"=>intval($id)))->get($this->table)->result_array();
        return $data[0];
    }

    public function validateGroup($group_id, $group_pwd) {
        $res = $this->db->where(array("id"=>intval($group_id), "pwd" => $group_pwd))->get($this->table)->result_array();
        if (count($res) > 0) {
            return true;
        }
        return false;
    }

    public function newGroup($group_name, $moderator_id) {
        $data = array(
            'group_name' => $group_name,
            'moderator_id' => $moderator_id,
            'pwd' => strtoupper(substr(md5(time()), 0, 6)),
        );
        try {
            $this->db->insert($this->table, $data);
        } catch (Exception $e) {
            throw $e;
        }
    }

}