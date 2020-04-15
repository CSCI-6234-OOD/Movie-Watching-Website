<?php

namespace Service;

interface MemberServiceInf {
    function register($name, $password);
    function login($name, $password);
}
