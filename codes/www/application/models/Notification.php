<?php
class Notification extends CI_Model {

    private $table = "notification";

    public function Notification() {
        parent::__construct();
        $this->load->database();
    }

    public function insert($group_id, $message) {
        $data = array(
            'group_id' => $group_id,
            'message' => $message
        );
        try {
            $this->db->insert($this->table, $data);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getMessage($id, $groupId) {
        $data =  $this->db->where(array("id >" => $id, 'group_id' => $groupId))->get($this->table)->result_array();
        //echo $this->db->last_query();
        return $data;
    }




}