<?php

define('BASE_URI', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));

class MY_Controller extends CI_Controller
{

    public function __construct()
    {

        parent::__construct();
        $this->load->library('customlib');
    }

}

class Public_Controller extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

}

class Admin_Controller extends MY_Controller
{

    protected $aaaa = false;

    public function __construct()
    {
        parent::__construct();
        $this->customlib->is_logged_in();
        $this->load->library('rbac');
    }

}







