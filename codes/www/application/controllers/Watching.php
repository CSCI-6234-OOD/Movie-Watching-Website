<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Watching extends CI_Controller {
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('cookie');
        $this->load->helper('url');
        $this->load->model('Groups');
        $this->load->model('GroupList');
        $this->load->model("Movie");
        $this->load->model("VotingEvent");
        $userId = get_cookie("userId");
        if (intval($userId) == 0) {
            header("Cache-Control: no-cache, must-revalidate");
            header('Location: ?/member');
        }
    }

    public function index() {
        $this->load->helper('url');
        $groupId = $this->input->get('group');
        $userId = get_cookie("userId");

        $group  = $this->Groups->getGroupById($groupId);
        if (count($this->GroupList->getGroupByIdAndUid($groupId, $userId )) == 0 && $userId != $group['moderator_id']) {
            echo "no permission";
            die;
        }
        //$this->Movie->shuffleMovies($groupId);
        $movie_list = $this->Movie->getGroupMovies($groupId);
        //var_dump($movie_list);
        $running_event = $this->VotingEvent->getRunningEvent($groupId);
        //var_dump($running_event);
        $history_event = $this->VotingEvent->historyList($groupId);
        $data['admin'] = 0;
        if ($userId == $group['moderator_id']) {
            $data['admin'] = 1;
        }
        $data['userName'] = get_cookie("userName");;
        $data['movie_list'] = $movie_list;
        $data['group'] = $group;
        $data['running_event'] = $running_event;
        $data['history_event'] = $history_event;
        $this->load->view('watching.html', $data);
    }

    public function detail() {
        $this->load->helper('url');
        $mid = $this->input->get('mid');
        $movie = $this->Movie->getMovie($mid);
        //var_dump($movie);
        $data['movie'] = $movie;
        $this->load->view('movie_detail.html', $data);
    }

    public function retrieveMovies() {
        $groupId = $this->input->post('group_id');
        $this->Movie->shuffleMovies($groupId);
    }

    public function clearAllMovies() {
        $groupId = $this->input->post('group_id');
        $this->Movie->deleteMovies($groupId);
    }




}
