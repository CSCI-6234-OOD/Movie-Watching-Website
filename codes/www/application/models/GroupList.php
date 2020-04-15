<?php

class GroupList extends CI_Model
{

    private $table = "group_list";

    public function GroupList()
    {
        parent::__construct();
        $this->load->database();
    }


    public function getGroupListByUid($uid) {
        return $this->db->where(array("uid"=>intval($uid)))->get($this->table)->result_array();


    }

    public function bind($group_id, $uid) {
        date_default_timezone_set('America/Toronto');
        $data = array(
            'group_id' => $group_id,
            'uid' => $uid,
            'create_time' => date('Y-m-d', time()),
        );
        try {
            $this->db->insert($this->table, $data);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getGroupByIdAndUid($groupId, $uid) {
        $data = $this->db->where(array("uid"=>intval($uid), 'group_id'=>$groupId))->get($this->table)->result_array();

        return $data;
    }

    public function unbind($group_id, $uid) {
        $where = array(
            'group_id' => $group_id,
            'uid' => $uid,
        );
        try {
            $this->db->where($where)->delete($this->table);
        } catch (Exception $e) {
            throw $e;
        }
    }
}