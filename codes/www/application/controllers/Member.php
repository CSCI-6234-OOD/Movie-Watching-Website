<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Member extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('cookie');
        $this->load->helper('url');
        $this->load->model("Members");
    }

    public function index() {
        $this->load->helper('url');
        $this->load->view('member.html');
    }

    public function register() {
        $name = $this->input->post('name');
        $pwd = $this->input->post('pwd');
        if ($this->Members->register($name, $pwd)) {
            $data = array(
                'errno' => 0,
            );
        } else {
            $data = array(
                'errno' => -1,
            );
        }
        echo json_encode($data);
        return;

    }

    public function login() {
        $name = $this->input->post('name');
        $pwd = $this->input->post('pwd');
        $user = $this->Members->login($name, $pwd);
        if (count($user) > 0) {
            $data = array(
                'errno' => 0,
            );
        } else {
            $data = array(
                'errno' => -2,
            );
        }
        set_cookie('userId', $user[0]['id'],time()+3600*24*30);
        set_cookie('userName', $user[0]['name'],time()+3600*24*30);
        echo json_encode($data);
        return;
    }

    public function logout() {
        delete_cookie('userId');
        header('Location: ?/member');
    }


}