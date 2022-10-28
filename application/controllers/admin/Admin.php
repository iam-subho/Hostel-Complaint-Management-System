<?php 

Class Admin extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }


    public function dashboard()
    {
        if (!$this->rbac->hasPrivilege('dashboard_view', 'can_view')) {
            $this->access_denied();
        }

        $this->load->view("layout/header");
        $this->load->view("dashboard_view");
        $this->load->view("layout/footer");
    }

    public function dashboard_post()
    {
        if (!$this->rbac->hasPrivilege('dashboard_view', 'can_view')) {
            $this->access_denied();
        }


        $this->load->view("welcome_message");

    }


    public function unauthorized(){
        $this->load->view("unauthorized");  
    }

    function access_denied() {
        redirect('admin/admin/unauthorized');
    }
}



?>