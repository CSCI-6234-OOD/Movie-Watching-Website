<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Group extends CI_Controller {
    public function Group() {
        parent::__construct();
        $this->load->helper('cookie');
        $this->load->helper('url');
        $this->load->model('Groups');
        ;
        $this->load->model('GroupList');
        $userId = get_cookie("userId");
        if (intval($userId) == 0) {
            header("Cache-Control: no-cache, must-revalidate");
            header('Location: ?/member');
        }
    }
    public function index() {
        $userId = get_cookie("userId");
        $myGroups = $this->Groups->getGroupByUid($userId);
        $JoinedGroups = $this->GroupList->getGroupListByUid($userId);
        $myJoinedGroups = [];
        foreach ($JoinedGroups as $k => $v) {

            $myJoinedGroups[] = $this->Groups->getGroupById($v['group_id']);
        }
        $data['userName'] = get_cookie("userName");;
        $data['my_groups'] = $myGroups;
        $data['joined_groups'] = $myJoinedGroups;
        $this->load->view('group.html', $data);
    }

    public function newGroup() {
        $userId = get_cookie("userId");
        $groupName = $this->input->post('group_name');

        try {
            $this->Groups->newGroup($groupName, $userId);
        } catch (Exception $e) {
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
    }

    public function subscribe() {
        $userId = get_cookie("userId");
        $groupId = $this->input->post('group_id');
        $groupPWD = $this->input->post('group_pwd');
        if (!$this->Groups->validateGroup($groupId, $groupPWD)) {
            $data = array(
                'errno' => -1,
            );
            echo json_encode($data);
            return;
        }
        try {
            $this->GroupList->bind($groupId, $userId );
        } catch (Exception $e) {
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
    }

    public function unsubscribe() {
        $userId = get_cookie("userId");
        $groupId = $this->input->post('group_id');
        try {
            $this->GroupList->unbind($groupId, $userId );
        } catch (Exception $e) {
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
    }




}