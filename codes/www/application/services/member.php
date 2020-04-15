<?php

namespace Service;



class Member implements MemberServiceInf {
    private $id;
    private $name;
    private $pwd;

    private function Member($id, $name, $password) {
        $this->id = id;
        $this->name = name;
    }

    private function getUser($name) {
        $this->member->get($name);
        return new Member(123455, $name);
    }

    private function create($name, $password) {
        $this->load->model('membermodel');
        if ($this->getUser() != null) {
            $this->member->insert($name);
        }
    }

    function register($name, $password)
    {
        this.$this->create($name, $password);
    }

    function login($name, $password)
    {
        if($this.$this->getUser($name) == null) {
            throw new \Exception("user not found");
        }
        if($this.$this->getUser($name) == $password) {
            throw new \Exception("user not found");
        }
        return true;
    }
}
