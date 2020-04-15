<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Notifications extends CI_Controller {
    public function getNewMsg() {
        $this->load->helper('cookie');
        $this->load->helper('url');
        $this->load->model('Notification');
        $this->load->model('GroupList');
        $userId = get_cookie("userId");
        if (intval($userId) == 0) {
            header("Cache-Control: no-cache, must-revalidate");
            header('Location: ?/member');
        }
        $userId = get_cookie("userId");
        $groups = $this->GroupList->getGroupListByUid($userId);
        $msgId = get_cookie("msgId") == null ? 0 : get_cookie("msgId");

        $msg = array();
        foreach ($groups as $v) {
            $s = $this->Notification->getMessage($msgId, $v['group_id']);
            foreach($s as $v1) {
                $msg[] = $v1;
            }
        }
        if (count($msg) == 0) {
            $data = array(
                'errno' => 0,
            );
            echo json_encode($data);
            return;
        }
        $html = '<table class="table">';
        foreach ($msg as $v) {
            $html .= "<tr><td>".$v['message']."</td></tr>";
            set_cookie("msgId", $v['id'], 3600 * 24 * 365);
        }
        $data = array(
            'errno' => -1,
            'msg' => $html,
        );


        echo json_encode($data);
        return;
    }

}