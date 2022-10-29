<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Rbac
{

    private $userRoles = array();
    protected $permissions;
    public $perm_category;

    public function __construct()
    {

        $this->CI          = &get_instance();
        $this->permissions = array();
        $this->perm_category = $this->CI->config->item('perm_category');
        
    }



    public function hasPrivilege($category = null, $permission = null)
    {

        $perm             = trim($category) . "-" . trim($permission);

        $permissionTable = $this->CI->db->select('perm_id')->from('permissions')->where('perm_short_code', trim($category))->get()->row_array();

        $permid=$permissionTable['perm_id'];

        $roles            = $this->CI->customlib->getStaffRole();
        $logged_user_role = json_decode($roles)->name;
        $logged_user_role_id = json_decode($roles)->id;

        $wherearray = array(
            'role_id' =>$logged_user_role_id,
            'perm_cat_id' =>$permid,
            $permission =>1
        );
        //print_r($roles);
        $result=$this->CI->db->select('id')->from('roles_permissions')->where($wherearray)->get()->row_array();
        //print_r($result);die();
        if($result)
        {
            return true;
        }

        return false;
    }


    public function hasPrivilegeuser($category = null, $permission = null)
    {

        $perm             = trim($category) . "-" . trim($permission);

        $permissionTable = $this->CI->db->select('perm_id')->from('permissions')->where('perm_short_code', trim($category))->get()->row_array();

        $permid=$permissionTable['perm_id'];

        $roles            = $this->CI->customlib->getStaffRole();
        $logged_user_role = json_decode($roles)->name;
        $logged_user_role_id = json_decode($roles)->id;

        $wherearray = array(
            'role_id' =>$logged_user_role_id,
            'perm_cat_id' =>$permid,
            $permission =>1
        );
        //print_r($roles);
        $result=$this->CI->db->select('id')->from('roles_permissions')->where($wherearray)->get()->row_array();
        //print_r($result);die();
        if($result)
        {
            return true;
        }

        return false;
    }





    public function unautherized22()
    {
        $this->CI->load->view('layout/header');
        $this->CI->load->view('unauthorized');
        $this->CI->load->view('layout/footer');
    }

}
