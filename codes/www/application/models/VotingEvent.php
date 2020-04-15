<?php
class VotingEvent extends CI_Model {

    private $table = "voting_event";
    public function Member() {
        parent::__construct();
        $this->load->database();
    }

    public function create($group_id) {
        $data = array(
            'title' => date("Y-m-d H:i:s", time()),
            'group_id' => $group_id,
        );
        try {
            $this->db->insert($this->table, $data);
        } catch (Exception $e) {
            $data = array(
                'errno' => -1,
            );
            echo json_encode($data);
            return;
        }
        $data = array(
            'errno' => -1,
        );
        echo json_encode($data);
        return;
    }

    public function getRunningEvent($group_id) {
        $data = $this->db->where(array('group_id'=>$group_id, 'status'=>0))->get($this->table)->result_array();
        if (count($data) >0 ) {
            return $data[0];
        }
        return null;
    }


    public function stopEvent($id) {
        $this->db->where(array('id'=>$id))->update($this->table, array('status'=>1));
    }

    public function historyList($groupId) {
        return $this->db->order_by('id', 'DESC')->where(array('group_id'=>$groupId, 'status'=>1))->get($this->table)->result_array();
    }


}
