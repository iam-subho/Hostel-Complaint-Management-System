<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config['facebook_app_id']              = '520157446628334';
$config['facebook_app_secret']          = 'e1e298332d8e34ee83421b03f66bea25';
$config['facebook_login_type']          = 'web';
$config['facebook_login_redirect_url']  = 'sociallogin/oauthfb';
$config['facebook_logout_redirect_url'] = 'sociallogin/logoutfb';
$config['facebook_permissions']         = array('email');
$config['facebook_graph_version']       = 'v2.6';
$config['facebook_auth_on_load']        = TRUE;

?>