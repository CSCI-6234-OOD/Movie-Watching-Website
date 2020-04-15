<?php
class Members extends CI_Model {

    private $table = "member";

    public function Members() {
        parent::__construct();
        $this->load->database();
    }
    private function get($name, $pwd) {
        return $this->db->get_where($this->table, array("name"=>$name, "pwd"=>$pwd));

    }

    private function insert($name, $pwd) {
        $data = array(
            'name' => $name,
            'pwd' => $pwd
        );
        try {
            $this->db->insert($this->table, $data);
        } catch (Exception $e) {
            throw $e;
        }
    }



    public function register($name, $pwd) {
        $data = array(
            'name' => $name,
            'pwd' => md5(md5($pwd)),
        );
        try {
            return $this->db->insert($this->table, $data);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function login($name, $pwd) {
        $where = array(
            'name' => $name,
            'pwd' => md5(md5($pwd)),
        );
        $data =  $this->db->where($where)->get($this->table)->result_array();
        //echo $this->db->last_query();
        return $data;
    }




}