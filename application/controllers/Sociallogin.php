<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH."libraries/google/vendor/autoload.php");
require_once(APPPATH."libraries/twitter/autoload.php");

use Abraham\TwitterOAuth\TwitterOAuth;




class Sociallogin extends Public_Controller {


    function __construct() {
        parent::__construct();
        $this->load->library('facebook');
        $this->load->model('login_model');
        $this->load->library('form_validation');
    }

    public function index(){
        redirect('login');
      }


    public function oauthgmail(){
        $this->checkheader();
        $client_id = $this->customlib->getSystemInfo()['gclientid'];
        $client_secret =$this->customlib->getSystemInfo()['gclientsecret'];
        $redirect_uri = base_url('sociallogin/gcallback');

        $client = new Google_Client();
        $client->setApplicationName("Public Grievance");
        $client->setClientId($client_id);
        $client->setClientSecret($client_secret);
        $client->setRedirectUri($redirect_uri);
        $client->addScope("email");
        $client->addScope("profile");
    
        //Send Client Request
        $objOAuthService = new Google_Service_Oauth2($client);
        
        $authUrl = $client->createAuthUrl();
        
        header('Location: '.$authUrl);
    
    }


    function gcallback(){
     $this->checkheader();
     $client_id = $this->customlib->getSystemInfo()['gclientid'];
     $client_secret =$this->customlib->getSystemInfo()['gclientsecret'];
     $redirect_uri = base_url('sociallogin/gcallback');
  
      //Create Client Request to access Google API
      $client = new Google_Client();
      $client->setApplicationName("Public Grievance");
      $client->setClientId($client_id);
      $client->setClientSecret($client_secret);
      $client->setRedirectUri($redirect_uri);
      $client->addScope("email");
      $client->addScope("profile");
  
      //Send Client Request
      $service = new Google_Service_Oauth2($client);
  
      $client->authenticate($_GET['code']);
      $_SESSION['access_token'] = $client->getAccessToken();
      
      // User information retrieval starts..............................
  
      $user = $service->userinfo->get(); //get user info 
        //print_r($user);die();
        if(($user->id)==''){
        redirect('login','refresh');
        }
  
      $this->handleoauthentication($user,'gm');
         
    }

    public function oauthtwitter(){
        $this->checkheader();
        $consumerKey=$this->customlib->getSystemInfo()['tconsumerkey'];
        $consumerSecret=$this->customlib->getSystemInfo()['tconsumersecret'];
        $connection= new TwitterOAuth($consumerKey,$consumerSecret);
        if($this->customlib->getSystemInfo()['proxyactive']==1){
        $connection->setProxy($this->curlproxy());
        }
        $request_token = $connection->oauth("oauth/request_token", array("oauth_callback" => base_url()."sociallogin/tcallback"));
        $_SESSION['oauth_token'] = $request_token['oauth_token'];
        $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
        $url = $connection->url("oauth/authorize", array("oauth_token" => $request_token['oauth_token']));
        $this->session->set_userdata('oauth_token', $request_token['oauth_token']);
		$this->session->set_userdata('oauth_token_secret',$request_token['oauth_token_secret']);    	
    	redirect($connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']), 'refresh'));
    }

    public function tcallback(){
        //$this->checkheader();
        $consumerKey=$this->customlib->getSystemInfo()['tconsumerkey'];
        $consumerSecret=$this->customlib->getSystemInfo()['tconsumersecret'];
        $connection = new TwitterOAuth($consumerKey, $consumerSecret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

		$access_token = $connection->oauth('oauth/access_token', array('oauth_verifier' => $_GET['oauth_verifier'], 'oauth_token'=> $_GET['oauth_token']));

	    $connection = new TwitterOAuth($consumerKey, $consumerSecret, $access_token['oauth_token'], $access_token['oauth_token_secret']);

	    $user = $connection->get('account/verify_credentials');
        $user->email='';
        
        if(($user->id)==''){
         redirect('login','refresh');
         }

        $this->handleoauthentication($user,'tw');
	    //print_r($user);die();
    }


    public function oauthfb(){
        $user2=($this->getauth());
        $user=(object)($user2);
        if(($user->id)==''){
            redirect('login','refresh');
            }
        $user->name=$user->first_name.' '.$user->last_name;
        if(!$user->email){
            $user->email='';
        }

        $this->handleoauthentication($user,'fb');
    }

    public function getauth() {
        //$this->checkheader();
        $userProfile = array();
        if ($this->facebook->is_authenticated()) { //echo 'hello';
            $userProfile = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email');
            //$userProfile = $this->facebook->request('get', '/me?fields=email');

       }//echo 'hi';die();
        return $userProfile;
    }

    public function logoutfb() {
        $this->facebook->destroy_session();
        redirect('/facebook_login');
    }

    public function handleoauthentication($user,$type){
        if(!$user){
            redirect('login');
        }
        $email=($user->email!='')?1:0;
        $insertArray= array(
            'oauthid'        =>$user->id,
            'oauthtype'      =>$type,
            'creation_date'  =>time(),
            'name'           =>$user->name,
            'email'          =>$user->email,
            'status'         =>2,
            'emailverified'  =>$email,
        );

        $recordCheckArray=array(
            'oauthid'        =>$user->id,
            'oauthtype'      =>$type,
        );
        $validate = $this->login_model->validateoauth($recordCheckArray);
        if($validate->num_rows() > 0){
            $data  = $validate->row_array();
        }else{
            if($user->email!=''){
            $emailcheck=$this->db->select('userid')->from('users')->where('email',$user->email)->get()->row_array();
             if($emailcheck['userid']!=''){
                $this->session->set_flashdata('flashError','Email already registered');
                redirect('login');
             }else{
                $createduser=$this->login_model->insertoauth($insertArray);
                $data  = $createduser->row_array();
             }
            }

  
        }
       $this->redirectcheck_url($data);

        //print_r($insertArray);

    }



    public function redirectcheck_url($data){
      if(($data['email']=='') || ($data['mobile']=='') || ($data['roomno']=='') || ($data['username']=='') || ($data['building']=='')){
      $user['building']=$this->login_model->buildinglist();
      $user['data']=$data;
      $this->session->set_userdata('sessionuserid', $data['userid']);
      $this->load->view("layout/header");
      $this->load->view('user/completeprofile',$user);
      $this->load->view("layout/footer");
      }else{
        if($data['status']==2 || $data['status']==3){
        $this->load->view("layout/header");    
        $this->load->view('user/statusinactive');
        $this->load->view("layout/footer");
        }else{
            $sesdata = array(
                'id'        => $data['userid'],
                'name'      => $data['name'],
                'email'     => $data['email'],
                'mobile'    => $data['mobile'],
                'logged_in' => TRUE
            );
            $this->session->set_userdata('user',$sesdata);
            if (isset($_SESSION['redirect_to'])) {
              redirect($_SESSION['redirect_to']);
            } else {
              redirect('userpanel/dashboard');
           }
        }
      }
    }

    public function completeprofile(){
        $this->checkheader();
        $this->form_validation->set_rules('name','Name required', 'trim|required|xss_clean');
        $this->form_validation->set_rules('username','Username required', 'trim|required|xss_clean|is_unique[users.username]');
        $this->form_validation->set_rules('mobile','Mobile required', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email','Email required', 'trim|required|xss_clean');
        $this->form_validation->set_rules('roomno','Roomno required', 'trim|required|xss_clean');
        $this->form_validation->set_rules('building','Building Required', 'trim|required|xss_clean');

        $userid=base64_decode($this->input->post('userid'));
        $sessionuserid=$this->session->userdata('sessionuserid');
        $recordCheckArray=array('userid'=>$userid);
        $validate = $this->login_model->validateoauth($recordCheckArray); 
        $data  = $validate->row_array();      
        if ($this->form_validation->run() == false) {
            if($userid==$sessionuserid){
            if($validate->num_rows() > 0){
            $user['building']=$this->login_model->buildinglist();
            $user['data']=$data;
            $this->session->set_userdata('sessionuserid', $data['userid']);
            $this->load->view("layout/header");
            $this->load->view('user/completeprofile',$user);
            $this->load->view("layout/footer");
            }else{
                redirect('login');
            }
           }else{
            redirect('login'); 
            //echo $userid.'---'.$sessionuserid;
          }

        }else{
            $username=$this->input->post('username');
            $mobile=$this->input->post('mobile');
            $roomno=$this->input->post('roomno');
            $building=$this->input->post('building');
            $insertArr=array(
                'username'=>$username,
                'mobile'=>$mobile,
                'roomno'=>$roomno,
                'building' =>$building,
            );
            if($data['email']==''){
            $insertArr['email']=$this->input->post('email');    
            }
            $this->db->where('userid',$userid)->update('users',$insertArr);
            $this->load->view("layout/header");    
            $this->load->view('user/statusinactive');
            $this->load->view("layout/footer");
        }  
    }

    function checkheader(){
        if(!isset($_SERVER['HTTP_REFERER'])){ //if url is directly requested from url bar then redirect
            redirect('login');
        }
        //echo $_SERVER['HTTP_REFERER'];
    }

    function curlproxy(){
        $proxy=$this->customlib->getSystemInfo();

        $parray=array(
            'CURLOPT_PROXY' =>$proxy['proxyurl'],
            'CURLOPT_PROXYUSERPWD' =>$proxy['pusername'].':'.$proxy['ppassword'],
            'CURLOPT_PROXYPORT' =>$proxy['pport'],
        );
        return $parray;
    }


}

?>