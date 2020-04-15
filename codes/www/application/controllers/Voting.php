<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Voting extends CI_Controller {
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('cookie');
        $this->load->helper('url');
        $this->load->model('Groups');
        $this->load->model('GroupList');
        $this->load->model("Movie");
        $this->load->model("VotingEvent");
        $this->load->model("VotingHistory");
        $userId = get_cookie("userId");
        if (intval($userId) == 0) {
            header("Cache-Control: no-cache, must-revalidate");
            header('Location: ?/member');
        }
    }

    public function createEvent() {
        parent::__construct();
        $groupId = $this->input->post('group_id');
        $this->VotingEvent->create($groupId);

        $this->load->model('Notification');
        $message = "A new Watching Event is created, check it out here! <a href='?/watching?group=".$groupId."'>link</a> ";
        $this->Notification->insert($groupId, $message);
    }

    public function endEvent() {
        parent::__construct();
        $id = $this->input->post('id');
        $this->VotingEvent->stopEvent($id);
    }

    public function vote() {
        $uid = get_cookie("userId");
        $event_id = $this->input->post('event_id');
        $movie_id = $this->input->post('movie_id');
        $movie_title = $this->input->post('movie_title');
        if (!$this->VotingHistory->vote($uid, $event_id, $movie_id, $movie_title)) {
            $data = array(
                'errno' => -1,
            );
            echo json_encode($data);
            return;
        }
        $data = array(
            'errno' => 0,
        );
        echo json_encode($data);
        return;
    }
    public function result() {
        $this->load->helper('url');
        $event_id = $this->input->get('id');
        $res = $this->VotingHistory->result($event_id);
        //print_r($res);
        $labels = '';
        $datas = '';
        foreach($res as $v) {
            $labels .=  '"'.$v['title'].'",';
            $datas .= $v['count'] . ",";
        }
        $data['labels'] = substr($labels, 0, -1);
        $data['datas'] = substr($datas, 0, -1);
        $this->load->view('voting_results.html', $data);
    }






}
