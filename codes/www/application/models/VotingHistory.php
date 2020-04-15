<?php
class VotingHistory extends CI_Model
{

    private $table = "vote_history";

    public function VotingHistory()
    {
        parent::__construct();
        $this->load->database();
    }

    public function vote($uid, $event_id, $movie_id, $movie_title) {
        $data = array(
            'uid' => $uid,
            'event_id' => $event_id,
            'movie_id' => $movie_id,
            'movie_title' => $movie_title,
        );
        return $this->db->insert($this->table, $data);

    }

    public function result($event_id) {
        $data = $this->db->query("SELECT *, COUNT(*) as cnt FROM `vote_history` WHERE event_id = ".$event_id." GROUP BY `movie_id` order by cnt desc")->result_array();
        $ret = array();
        foreach($data as $k => $v){
            $ret[$k]['title'] = $v['movie_title'];
            $ret[$k]['count'] = $v['cnt'];
        }
        return $ret;
    }



}