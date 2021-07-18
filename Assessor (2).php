<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assessor extends CI_Controller {
    public function __construct(){
    parent::__construct();
    $this->load->database();
    $this->load->library('session');
    $this->load->helper('url');
    $this->load->helper('html');
    $this->load->library('encrypt');
    $this->load->library('encryption');
    $this->load->model('Common_model');
    $this->load->model('AssessorModel');
       $this->load->library('pagination');
   }

  public function assessorListold()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    if(!empty($userid) && ($usertype <=2 )){
      $assessorList = $this->AssessorModel->allList($sessionuser,$userid);
      $data['assessorList'] = $assessorList;
      $this->load->view('view/assessor-list',$data);
    }
    else
    {
        redirect(base_url('home'));
    }
}
public function assessorList()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
     if(!empty($userid) && ($usertype <=2 )){
    $conditions['returnType'] = 'count';
    $totalRec = $this->AssessorModel->allList($conditions);
        
        //pagination config
        $config['base_url']    = base_url().'assessorlist/';
        $config['uri_segment'] = 2;
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 30;
        
        //styling
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="pgactive"><a href="javascript:void(0);">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';
        $config['next_tag_open'] = '<li class="pg-next">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="pg-prev">';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        
        //initialize pagination library
        $this->pagination->initialize($config);
        
        //define offset
        $page = $this->uri->segment(2);
      //  echo"<pre>"; print_r($page); die;
        $offset = !$page?0:$page;
        
        //get rows
        $conditions['returnType'] = '';
        $conditions['start'] = $offset;
        $conditions['limit'] = 30;
        $data['assessorList'] = $this->AssessorModel->allList($conditions);
      
        $this->load->view('view/assessor-list',$data);
      }

    else
    {
        redirect(base_url('home'));
    }
}
function addassessor()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    $data['userid'] = $userid;
    if(!empty($userid) && ($usertype <=2 )){
      //echo "<pre>";print_r($_POST);      die;
      if(!empty($this->input->post('submitassessor')) && $this->input->post('submitassessor') == 'save')
      {
          $proctorfile= '';
          
        $id = $this->input->post('id');
        $insert_data = array(
          			"name"=>ucfirst($this->input->post('aname')),
          			"assessorid"=>$this->input->post('assessorid'),
          			"state"=>$this->input->post('astate'),
          			"district"=>$this->input->post('district'),
          			"type"=>$this->input->post('atype'),
          			"start_date"=>$this->input->post('astart_date'),
          			"status"=>$this->input->post('astatus'),
          			//"uploadfile" => $proctorfile,
          			"email"=>$this->input->post('email'),
          			"contactnum"=>$this->input->post('contactnum'),
          			"recruitedby"=>$this->input->post('recruitedby'),
          			"reference"=>$this->input->post('reference'),
          			"assessoreng"=>$this->input->post('assessoreng'),
          			"attended_date"=>$this->input->post('attended_date'),
          			"certgen_date"=>$this->input->post('certgen_date'),
          			"certexp_date"=>$this->input->post('certexp_date'),
          			"qp_code"=>$this->input->post('qp_code'),
          			"sector"=>$this->input->post('sector'),
          			"assessorfee"=>$this->input->post('assessorfee'),
          			"assessorfeeper"=>$this->input->post('assessorfeeper'),
          			"type"=>$this->input->post('atype'),
          			"issused"=>$this->input->post('issused'),
          			"issused_date"=>$this->input->post('issused_date'),
          			"assessorcate"=>$this->input->post('assessorcate'),
          			"bankname"=>$this->input->post('bankname'),
          			"bankaccount"=>$this->input->post('bankaccount'),
          			"ifsccode"=>$this->input->post('ifsccode'),
          			"assessorpan"=>$this->input->post('assessorpan'),
          			"attachments"=>$this->input->post('attachments'),
                      );
    	if(!empty($_FILES['proctorfile']['name'])){
								$upload_loc='files/assessor/';
								$config['upload_path']          = './'.$upload_loc;
								$config['allowed_types']        = 'gif|jpg|png|jpeg';
								$config['encrypt_name']=TRUE;
								
								//Load upload library and initialize configuration
								$this->load->library('upload',$config);
								$this->upload->initialize($config);
								
								if($this->upload->do_upload('proctorfile')){
									$uploadData = $this->upload->data();
									//$details['loc']=$uploadData;
									//$proctorfile=$uploadData;
									$proctorfile = $upload_loc.$uploadData['file_name'];
									$insert_data['uploadfile'] = $proctorfile;
								}else{
									throw new Exception(strip_tags($this->upload->display_errors()));
								}
							}
        $cond = 'id = '.$id;
        $insertid = $this->Common_model->addEditTableByCond($insert_data,ASSESSOR,$cond);
        if(!empty($id)){$text = 'updated';}else{$text = 'added';}
        $this->session->set_flashdata('accessormsg','Assessor '.$text.' succesfully.');
        redirect(base_url('assessorlist'));
      }
      elseif($this->input->get('q'))
      {
        $bid =  $this->input->get('q');
        $assessorfound = $this->AssessorModel->checkAssessor($bid);
        if($assessorfound)
        {
          $data['id'] = $bid;
          $data['assessordetail'] = $this->Common_model->get_table_data(ASSESSOR,'id = '.$bid);
          $cons = 'CountryID = 94';
          $data['states'] = $this->Common_model->get_table_data(STATES,$cons);
          
          $data['$stateassessor'] = $this->Common_model->getParticularFieldFromTheTableByCond('State',STATES,'Stateid = '.$data['assessordetail'][0]['state']);
          $this->load->view('view/addassessor',$data);
        }
        else
        {
            $batchnotfound = 'The assessor you are looking is not exist.';
            $this->session->set_flashdata('batcherror',ltrim($batchnotfound,','));
            redirect(base_url('assessorlist'));
        }
      }
      else
      {
        $data['id'] = '';
        $data['assessordetail'] = array();
        $cons = 'CountryID = 94';
        $data['states'] = $this->Common_model->get_table_data(STATES,$cons);
        $this->load->view('view/addassessor',$data);
      }
    }
    else
    {
        redirect(base_url('home'));
    }
}

 public function assessorLangangeold()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    if(!empty($userid) && ($usertype <=2 )){
      $assessorList = $this->AssessorModel->allList($sessionuser,$userid);
      $data['$assessorLangange'] = $assessorLangange;
      $this->load->view('view/assessor-list',$data);
    }
    else
    {
        redirect(base_url('home'));
    }
}
public function assessorLangange()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
     if(!empty($userid) && ($usertype <=2 )){
    $conditions['returnType'] = 'count';
    $totalRec = $this->AssessorModel->allList($conditions);
        
        //pagination config
        $config['base_url']    = base_url().'assessorLangange/';
        $config['uri_segment'] = 2;
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 30;
        
        //styling
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="pgactive"><a href="javascript:void(0);">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';
        $config['next_tag_open'] = '<li class="pg-next">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="pg-prev">';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        
        //initialize pagination library
        $this->pagination->initialize($config);
        
        //define offset
        $page = $this->uri->segment(2);
      //  echo"<pre>"; print_r($page); die;
        $offset = !$page?0:$page;
        
        //get rows
        $conditions['returnType'] = '';
        $conditions['start'] = $offset;
        $conditions['limit'] = 30;
        $data['assessorList'] = $this->AssessorModel->allList($conditions);
      
        $this->load->view('view/assessor-langange',$data);
      }

    else
    {
        redirect(base_url('home'));
    }
}
function addassessorLangange()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    $data['userid'] = $userid;
    if(!empty($userid) && ($usertype <=2 )){
      //echo "<pre>";print_r($_POST);      die;
      if(!empty($this->input->post('submitassessor')) && $this->input->post('submitassessor') == 'save')
      {
        $id = $this->input->post('id');
        $insert_data = array(
          			"name"=>ucfirst($this->input->post('aname')),
          			"location"=>$this->input->post('alocation'),
          			"state"=>$this->input->post('astate'),
          			"type"=>$this->input->post('atype'),
          			"start_date"=>$this->input->post('astart_date'),
          			"status"=>$this->input->post('astatus'),
                      );

        $cond = 'id = '.$id;
        $insertid = $this->Common_model->addEditTableByCond($insert_data,ASSESSOR,$cond);
        if(!empty($id)){$text = 'updated';}else{$text = 'added';}
        $this->session->set_flashdata('accessormsg','Assessor '.$text.' succesfully.');
        redirect(base_url('assessor-langange'));
      }
      elseif($this->input->get('q'))
      {
        $bid =  $this->input->get('q');
        $assessorfound = $this->AssessorModel->checkAssessor($bid);
        if($assessorfound)
        {
          $data['id'] = $bid;
          $data['assessordetail'] = $this->Common_model->get_table_data(ASSESSOR,'id = '.$bid);
          $cons = 'CountryID = 94';
          $data['states'] = $this->Common_model->get_table_data(STATES,$cons);
          $data['$stateassessor'] = $this->Common_model->getParticularFieldFromTheTableByCond('State',STATES,'Stateid = '.$data['assessordetail'][0]['state']);
          $this->load->view('view/addassessor',$data);
        }
        else
        {
            $batchnotfound = 'The assessor you are looking is not exist.';
            $this->session->set_flashdata('batcherror',ltrim($batchnotfound,','));
            redirect(base_url('assessorlangange'));
        }
      }
      else
      {
        $data['id'] = '';
        $data['assessordetail'] = array();
        $cons = 'CountryID = 94';
        $data['states'] = $this->Common_model->get_table_data(STATES,$cons);
        $this->load->view('view/addassessor',$data);
      }
    }
    else
    {
        redirect(base_url('home'));
    }
}
public function userList()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    if(!empty($userid) && $userid == 1){
    $conditions['returnType'] = 'count';
    $totalRec = $this->AssessorModel->alluserslist($conditions);
        
        //pagination config
        $config['base_url']    = base_url().'userlist/';
        $config['uri_segment'] = 2;
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 30;

        //styling
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="pgactive"><a href="javascript:void(0);">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';
        $config['next_tag_open'] = '<li class="pg-next">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="pg-prev">';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        
        //initialize pagination library
        $this->pagination->initialize($config);
        
        //define offset
        $page = $this->uri->segment(2);
       //echo"<pre>"; print_r($page);
        $offset = !$page?0:$page;
        
        //get rows
        $conditions['returnType'] = '';
        $conditions['start'] = $offset;
        $conditions['limit'] = 30;
        $data['posts'] = $this->AssessorModel->alluserslist($conditions);
      
        $this->load->view('view/user-list',$data);
      }
    
    else
    {
        redirect(base_url('home'));
    }
}

 public function modeassessorold()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    if(!empty($userid) && ($usertype <=2 )){
      $assessormode = $this->AssessorModel->allList($sessionuser,$userid);
      $data['assessormode'] = $assessormode;
      $this->load->view('view/assessormode',$data);
    }
    else
    {
        redirect(base_url('home'));
    }
}
public function modeassessor()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
     if(!empty($userid) && ($usertype <=2 )){
    $conditions['returnType'] = 'count';
    $totalRec = $this->AssessorModel->allList($conditions);
        
        //pagination config
        $config['base_url']    = base_url().'assessormode/';
        $config['uri_segment'] = 2;
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 30;
        
        //styling
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="pgactive"><a href="javascript:void(0);">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';
        $config['next_tag_open'] = '<li class="pg-next">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="pg-prev">';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        
        //initialize pagination library
        $this->pagination->initialize($config);
        
        //define offset
        $page = $this->uri->segment(2);
      //  echo"<pre>"; print_r($page); die;
        $offset = !$page?0:$page;
        
        //get rows
        $conditions['returnType'] = '';
        $conditions['start'] = $offset;
        $conditions['limit'] = 30;
        $data['assessorList'] = $this->AssessorModel->allList($conditions);
      
        $this->load->view('view/assessormode',$data);
      }

    else
    {
        redirect(base_url('home'));
    }
}
function adduser()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $data['userid'] = $userid;
    if(!empty($userid) && $userid == 1){
      //echo "<pre>";print_r($_POST);      die;
      if(!empty($this->input->post('submituser')) && $this->input->post('submituser') == 'save')
      {
        $id = $this->input->post('id');
        $randomcode = $this->Common_model->generateCode("5");
        $pwd = md5($this->input->post('password').$randomcode);
        $prv = explode('@#@#',$this->input->post('privilege_type'));
        if($id > 0)
        {
          $userinfo = $this->Common_model->getUserInformationByUserId($id);
          if($userinfo->pwd == trim($this->input->post('password')))
          {
            $insert_data = array(
      			"name"=>$this->input->post('name'),
      			"email"=>$this->input->post('email'),
                "uname"=>$this->input->post('uname'),
      			"gender"=>$this->input->post('gender'),
                "privilege_type"=>$prv[1],
      			"priviliges"=>$prv[0],
                "phone_No"=>$this->input->post('phone_No'),
      			"address"=>$this->input->post('address'),
      			"zip"=>$this->input->post('zip'),
      			"active"=>$this->input->post('active'),
                "city"=>$this->input->post('city'),
                "state"=>$this->input->post('state'),
                "user_type"=>$this->input->post('user_type'),
                );
          }
          else
          {
            $insert_data = array(
      			"name"=>$this->input->post('name'),
      			"email"=>$this->input->post('email'),
                "uname"=>$this->input->post('uname'),
      			"gender"=>$this->input->post('gender'),
                "pwd"=>$pwd,
                "password"=>$randomcode,
                "privilege_type"=>$prv[1],
      			"priviliges"=>$prv[0],
                "phone_No"=>$this->input->post('phone_No'),
      			"address"=>$this->input->post('address'),
      			"zip"=>$this->input->post('zip'),
      			"active"=>$this->input->post('active'),
                "city"=>$this->input->post('city'),
                "state"=>$this->input->post('state'),
                "user_type"=>$this->input->post('user_type'),
                );
          }

        }
        else
        {
          $insert_data = array(
                "name"=>$this->input->post('name'),
                "email"=>$this->input->post('email'),
                "uname"=>$this->input->post('uname'),
                "gender"=>$this->input->post('gender'),
                "pwd"=>$pwd,
                "password"=>$randomcode,
                "privilege_type"=>$prv[1],
      			"priviliges"=>$prv[0],
                "phone_No"=>$this->input->post('phone_No'),
                "address"=>$this->input->post('address'),
                "zip"=>$this->input->post('zip'),
                "active"=>$this->input->post('active'),
                "city"=>$this->input->post('city'),
                "state"=>$this->input->post('state'),
                "user_type"=>$this->input->post('user_type'),
                );
        }
        //print_r($insert_data);die;
        $cond = 'id = '.$id;
        $insertid = $this->Common_model->addEditTableByCond($insert_data,ADMIN_TBL,$cond);
        if(!empty($id)){$text = 'updated';}else{$text = 'added';}
        $this->session->set_flashdata('accessormsg','User '.$text.' succesfully.');
        redirect(base_url('userlist'));
      }
      elseif($this->input->get('q'))
      {
        $bid =  $this->input->get('q');
        $userfound = $this->AssessorModel->checkuser($bid);
        if($userfound)
        {
          $data['id'] = $bid;
          $data['userdetail'] = $this->Common_model->get_table_data(ADMIN_TBL,'id = '.$bid);
          $cons = 'CountryID = 94';
          $data['states'] = $this->Common_model->get_table_data(STATES,$cons);
          if($data['userdetail'][0]['state'])
              $data['$stateuser'] = $this->Common_model->getParticularFieldFromTheTableByCond('State',STATES,'Stateid = '.$data['userdetail'][0]['state']);

          $con = 'status = 1 AND id !=1';
          $data['utype'] = $this->Common_model->get_table_data(USERTYPE_TBL,$con);
          $data['stages'] = $this->Common_model->get_table_data(STAGES,'status = 1');
          $this->load->view('view/adduser',$data);
        }
        else
        {
            $batchnotfound = 'The user you are looking is not exist.';
            $this->session->set_flashdata('batcherror',ltrim($batchnotfound,','));
            redirect(base_url('user-list'));
        }
      }
      else
      {
        $data['id'] = '';
        $data['userdetail'] = array();
        $cons = 'CountryID = 94';
        $con = 'status = 1 AND id != 1';
        $data['states'] = $this->Common_model->get_table_data(STATES,$cons);
        $data['utype'] = $this->Common_model->get_table_data(USERTYPE_TBL,$con);
        $data['stages'] = $this->Common_model->get_table_data(STAGES,'status = 1');
        $this->load->view('view/adduser',$data);
      }
    }
    else
    {
        redirect(base_url('home'));
    }
}

public function schemeListold()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    if(!empty($userid) && ($usertype <=2 )){
      $schemeList = $this->AssessorModel->allschemeList($sessionuser,$userid);
      $data['schemeList'] = $schemeList;
      $this->load->view('view/scheme-list',$data);
    }
    else
    {
        redirect(base_url('home'));
    }
}
public function schemeList()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    if(!empty($userid) && ($usertype <=2 ))
    {
       $conditions['returnType'] = 'count';
    $totalRec = $this->AssessorModel->allschemeList($conditions);
        
        //pagination config
        $config['base_url']    = base_url().'schemelist/';
        $config['uri_segment'] = 2;
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 30;
        
        //styling
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="pgactive"><a href="javascript:void(0);">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';
        $config['next_tag_open'] = '<li class="pg-next">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="pg-prev">';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        
        //initialize pagination library
        $this->pagination->initialize($config);
        
        //define offset
        $page = $this->uri->segment(2);
      //  echo"<pre>"; print_r($page); die;
        $offset = !$page?0:$page;
        
        //get rows
        $conditions['returnType'] = '';
        $conditions['start'] = $offset;
        $conditions['limit'] = 30;
        $data['schemeList'] = $this->AssessorModel->allschemeList($conditions);
      $this->load->view('view/scheme-list',$data);
    }
    else
    {
        redirect(base_url('home'));
    }
}
function addscheme()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    $data['userid'] = $userid;
    if(!empty($userid) && ($usertype <=2 )){
      //echo "<pre>";print_r($_POST);      die;
      if(!empty($this->input->post('submitscheme')) && $this->input->post('submitscheme') == 'save')
      {
        $id = $this->input->post('id');
        $insert_data = array(
          			"scheme_type"=>$this->input->post('scheme_type'),
          			"status"=>$this->input->post('status'),
                      );
        $cond = 'id = '.$id;
        $insertid = $this->Common_model->addEditTableByCond($insert_data,SCHEME,$cond);
        if(!empty($id)){$text = 'updated';}else{$text = 'added';}
        $this->session->set_flashdata('accessormsg','Scheme Type '.$text.' succesfully.');
        redirect(base_url('schemelist'));
      }
      elseif($this->input->get('q'))
      {
        $bid =  $this->input->get('q');
        $schemefound = $this->AssessorModel->checkScheme($bid);
        if($schemefound)
        {
          $data['id'] = $bid;
          $data['schemedetail'] = $this->Common_model->get_table_data(SCHEME,'id = '.$bid);
          $this->load->view('view/addscheme',$data);
        }
        else
        {
            $batchnotfound = 'The scheme you are looking is not exist.';
            $this->session->set_flashdata('batcherror',ltrim($batchnotfound,','));
            redirect(base_url('schemelist'));
        }
      }
      else
      {
        $data['id'] = '';
        $data['schemedetail'] = array();
        $this->load->view('view/addscheme',$data);
      }
    }
    else
    {
        redirect(base_url('home'));
    }
}
function addcount()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    $data['userid'] = $userid;
    if(!empty($userid) && ($usertype <=2 )){
      //echo "<pre>";print_r($_POST);      die;
      if(!empty($this->input->post('submitscheme')) && $this->input->post('submitscheme') == 'save')
      {
        $id = $this->input->post('id');
        $insert_data = array(
          			"drop_count"=>$this->input->post('drop_cunt'),
          			"status"=>$this->input->post('status'),
                      );
        $cond = 'id = '.$id;
        $insertid = $this->Common_model->addEditTableByCond($insert_data,DROPCOUNT,$cond);
        if(!empty($id)){$text = 'updated';}else{$text = 'added';}
        $this->session->set_flashdata('accessormsg','Scheme Type '.$text.' succesfully.');
        redirect(base_url('schemelist'));
      }
      elseif($this->input->get('q'))
      {
        $bid =  $this->input->get('q');
        $schemefound = $this->AssessorModel->checkScheme($bid);
        if($schemefound)
        {
          $data['id'] = $bid;
          $data['schemedetail'] = $this->Common_model->get_table_data(DROPCOUNT,'id = '.$bid);
          $this->load->view('view/addcount',$data);
        }
        else
        {
            $batchnotfound = 'The scheme you are looking is not exist.';
            $this->session->set_flashdata('batcherror',ltrim($batchnotfound,','));
            redirect(base_url('schemelist'));
        }
      }
      else
      {
        $data['id'] = '';
        $data['schemedetail'] = array();
        $this->load->view('view/addcount',$data);
      }
    }
    else
    {
        redirect(base_url('home'));
    }
}

// ====================<abhijeet>=================



public function schemeNameold()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    if(!empty($userid) && ($usertype <=2 )){
      $schemeName = $this->AssessorModel->allschemename($sessionuser,$userid);
      $data['schemeName'] = $schemeName;
      $this->load->view('view/scheme-name',$data);
    }
    else
    {
        redirect(base_url('home'));
    }
}
public function schemeName()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    if(!empty($userid) && ($usertype <=2 ))
    {
       $conditions['returnType'] = 'count';
    $totalRec = $this->AssessorModel->allschemename($conditions);
        
        //pagination config
        $config['base_url']    = base_url().'schemename/';
        $config['uri_segment'] = 2;
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 30;
        
        //styling
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="pgactive"><a href="javascript:void(0);">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';
        $config['next_tag_open'] = '<li class="pg-next">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="pg-prev">';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        
        //initialize pagination library
        $this->pagination->initialize($config);
        
        //define offset
        $page = $this->uri->segment(2);
      //  echo"<pre>"; print_r($page); die;
        $offset = !$page?0:$page;
        
        //get rows
        $conditions['returnType'] = '';
        $conditions['start'] = $offset;
        $conditions['limit'] = 30;
        $data['schemeName'] = $this->AssessorModel->allschemename($conditions);
      $this->load->view('view/scheme-name',$data);
    }
    else
    {
        redirect(base_url('home'));
    }
}
function addschemename()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    $data['userid'] = $userid;
    if(!empty($userid) && ($usertype <=2 )){
      //echo "<pre>";print_r($_POST);      die;
      if(!empty($this->input->post('submitschemename')) && $this->input->post('submitschemename') == 'save')
      {
        $id = $this->input->post('id');
        $insert_data = array(
                "scheme_name"=>$this->input->post('scheme_name'),
                "status"=>$this->input->post('status'),
                      );
        $cond = 'id = '.$id;
        $insertid = $this->Common_model->addEditTablesByConds($insert_data,SCHEME_NAME,$cond);
        if(!empty($id)){$text = 'updated';}else{$text = 'added';}
        $this->session->set_flashdata('accessormsg','scheme name'.$text.' succesfully.');
        redirect(base_url('schemename'));
      }
      elseif($this->input->get('q'))
      {
        $bid =  $this->input->get('q');
        $schemefound = $this->AssessorModel->checkschemename($bid);
        if($schemefound)
        {
          $data['id'] = $bid;
          $data['schemenamedetail'] = $this->Common_model->get_table_data(SCHEME_NAME,'id = '.$bid);
          $this->load->view('view/addschemename',$data);
        }
        else
        {
            $batchnotfound = 'The scheme you are looking is not exist.';
            $this->session->set_flashdata('batcherror',ltrim($batchnotfound,','));
            redirect(base_url('schemename'));
        }
      }
      else
      {
        $data['id'] = '';
        $data['schemedetail'] = array();
        $this->load->view('view/addschemename',$data);
      }
    }
    else
    {
        redirect(base_url('home'));
    }
}

// ========================sub-scheme=======================================

public function subSchemeold()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    if(!empty($userid) && ($usertype <=2 )){
      $subScheme = $this->AssessorModel->allsubscheme($sessionuser,$userid);
      $data['subscheme'] = $subscheme;
      $this->load->view('view/sub-scheme',$data);
    }
    else
    {
        redirect(base_url('home'));
    }
}
public function subscheme()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    if(!empty($userid) && ($usertype <=2 ))
    {
       $conditions['returnType'] = 'count';
    $totalRec = $this->AssessorModel->allsubscheme($conditions);
        
        //pagination config
        $config['base_url']    = base_url().'subscheme/';
        $config['uri_segment'] = 2;
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 30;
        
        //styling
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="pgactive"><a href="javascript:void(0);">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';
        $config['next_tag_open'] = '<li class="pg-next">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="pg-prev">';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        
        //initialize pagination library
        $this->pagination->initialize($config);
        
        //define offset
        $page = $this->uri->segment(2);
      //  echo"<pre>"; print_r($page); die;
        $offset = !$page?0:$page;
        
        //get rows
        $conditions['returnType'] = '';
        $conditions['start'] = $offset;
        $conditions['limit'] = 30;
        $data['subScheme'] = $this->AssessorModel->allsubscheme($conditions);
      $this->load->view('view/sub-scheme',$data);
    }
    else
    {
        redirect(base_url('home'));
    }
}
function addsubscheme()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    $data['userid'] = $userid;
    if(!empty($userid) && ($usertype <=2 )){
      //echo "<pre>";print_r($_POST);      die;
      if(!empty($this->input->post('submitsubscheme')) && $this->input->post('submitsubscheme') == 'save')
      {
        $id = $this->input->post('id');
        $insert_data = array(
                "sub_scheme"=>$this->input->post('sub_scheme'),
                "status"=>$this->input->post('status'),
                      );
        $cond = 'id = '.$id;
        $insertid = $this->Common_model->AddEditTableByConds($insert_data,SUB_SCHEME,$cond);
        if(!empty($id)){$text = 'updated';}else{$text = 'added';}
        $this->session->set_flashdata('accessormsg','sub scheme'.$text.' succesfully.');
        redirect(base_url('subscheme'));
      }
      elseif($this->input->get('q'))
      {
        $bid =  $this->input->get('q');
        $schemefound = $this->AssessorModel->checksubscheme($bid);
        if($schemefound)
        {
          $data['id'] = $bid;
          $data['subschemedetail'] = $this->Common_model->get_table_data(SUB_SCHEME,'id = '.$bid);
          $this->load->view('view/addsubscheme',$data);
        }
        else
        {
            $batchnotfound = 'The scheme you are looking is not exist.';
            $this->session->set_flashdata('batcherror',ltrim($batchnotfound,','));
            redirect(base_url('subscheme'));
        }
      }
      else
      {
        $data['id'] = '';
        $data['schemedetail'] = array();
        $this->load->view('view/addsubscheme',$data);
      }
    }
    else
    {
        redirect(base_url('home'));
    }
}

// ======================== Appeared-Candidate=======================================

public function appearedold()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    if(!empty($userid) && ($usertype <=2 )){
      $appearedList = $this->AssessorModel->allappeared($sessionuser,$userid);
      $data['appearedList'] = $appearedList;
      $this->load->view('view/appeared-list',$data);
    }
    else
    {
        redirect(base_url('home'));
    }
}
public function appearedList()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    if(!empty($userid) && ($usertype <=2 ))
    {
       $conditions['returnType'] = 'count';
       $totalRec = $this->AssessorModel->allappeared($conditions);
        
        //pagination config
        $config['base_url']    = base_url().'appearedlist/';
        $config['uri_segment'] = 2;
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 30;
        
        //styling
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="pgactive"><a href="javascript:void(0);">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';
        $config['next_tag_open'] = '<li class="pg-next">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="pg-prev">';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        
        //initialize pagination library
        $this->pagination->initialize($config);
        
        //define offset
        $page = $this->uri->segment(2);
      //  echo"<pre>"; print_r($page); die;
        $offset = !$page?0:$page;
        
        //get rows
        $conditions['returnType'] = '';
        $conditions['start'] = $offset;
        $conditions['limit'] = 30;
        $data['appeared'] = $this->AssessorModel->allappeared($conditions);
      $this->load->view('view/appeared-list',$data);
    }
    else
    {
        redirect(base_url('home'));
    }
}
function addAppeared()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    $data['userid'] = $userid;
    if(!empty($userid) && ($usertype <=2 )){
      //echo "<pre>";print_r($_POST);      die;
      if(!empty($this->input->post('submitappeared')) && $this->input->post('submitappeared') == 'save')
      {
        $id = $this->input->post('id');
        $insert_data = array(
                "app_candidate"=>$this->input->post('app_candidate'),
                "status"=>$this->input->post('status'),
                );
        $cond = 'id = '.$id;
        $insertid = $this->Common_model->addEditTableByCondappeared($insert_data,APPEARED,$cond);
        if(!empty($id)){$text = 'updated';}else{$text = 'added';}
        $this->session->set_flashdata('accessormsg','appeared'.$text.' succesfully.');
        redirect(base_url('appearedlist'));
      }
      elseif($this->input->get('q'))
      {
        $bid =  $this->input->get('q');
        $appearedfound = $this->AssessorModel->checkappeared($bid);
        if($appearedfound)
        {
          $data['id'] = $bid;
          $data['appeareddetail'] = $this->Common_model->get_table_data(APPEARED,'id = '.$bid);
          $this->load->view('view/addappeared',$data);
        }
        else
        {
            $batchnotfound = 'The scheme you are looking is not exist.';
            $this->session->set_flashdata('batcherror',ltrim($batchnotfound,','));
            redirect(base_url('appearedlist'));
        }
      }
      else
      {
        $data['id'] = '';
        $data['appearedListdetail'] = array();
        $this->load->view('view/addappeared',$data);
      }
    }
    else
    {
        redirect(base_url('home'));
    }
}

  // ======================== Absent Candidate=======================================

public function absentold()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    if(!empty($userid) && ($usertype <=2 )){
      $absentList = $this->AssessorModel->allabsentlist($sessionuser,$userid);
      $data['appearedList'] = $appearedList;
      $this->load->view('view/absent_list',$data);
    }
    else
    {
        redirect(base_url('home'));
    }
}
public function absentList()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    if(!empty($userid) && ($usertype <=2 ))
    {
       $conditions['returnType'] = 'count';
       $totalRec = $this->AssessorModel->allabsentlist($conditions);
        
        //pagination config
        $config['base_url']    = base_url().'absentlist/';
        $config['uri_segment'] = 2;
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 30;
        
        //styling
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="pgactive"><a href="javascript:void(0);">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';
        $config['next_tag_open'] = '<li class="pg-next">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="pg-prev">';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        
        //initialize pagination library
        $this->pagination->initialize($config);
        
        //define offset
        $page = $this->uri->segment(2);
      //  echo"<pre>"; print_r($page); die;
        $offset = !$page?0:$page;
        
        //get rows
        $conditions['returnType'] = '';
        $conditions['start'] = $offset;
        $conditions['limit'] = 30;
        $data['absent'] = $this->AssessorModel->allabsentlist($conditions);
      $this->load->view('view/absent_list',$data);
    }
    else
    {
        redirect(base_url('home'));
    }
}
function addAbsent()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    $data['userid'] = $userid;
    if(!empty($userid) && ($usertype <=2 )){
      //echo "<pre>";print_r($_POST);      die;
      if(!empty($this->input->post('submitabsent')) && $this->input->post('submitabsent') == 'save')
      {
        $id = $this->input->post('id');
        $insert_data = array(
                "absent_cond"=>$this->input->post('absent_cond'),
                "status"=>$this->input->post('status'),
                );
        $cond = 'id = '.$id;
        $insertid = $this->Common_model->addEditTableByCondabsent($insert_data,ABSENT,$cond);
        if(!empty($id)){$text = 'updated';}else{$text = 'added';}
        $this->session->set_flashdata('accessormsg','absent'.$text.' succesfully.');
        redirect(base_url('absentlist'));
      }
      elseif($this->input->get('q'))
      {
        $bid =  $this->input->get('q');
        $absentfound = $this->AssessorModel->checkabsent($bid);
        if($absentfound)
        {
          $data['id'] = $bid;
          $data['absentdetail'] = $this->Common_model->get_table_data(ABSENT,'id = '.$bid);
          $this->load->view('view/addabsent',$data);
        }
        else
        {
            $batchnotfound = 'The absent you are looking is not exist.';
            $this->session->set_flashdata('batcherror',ltrim($batchnotfound,','));
            redirect(base_url('absentlist'));
        }
      }
      else
      {
        $data['id'] = '';
        $data['absentdetail'] = array();
        $this->load->view('view/addabsent',$data);
      }
    }
    else
    {
        redirect(base_url('home'));
    }
}

 // ======================== Passed Candidate=======================================

public function passedold()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    if(!empty($userid) && ($usertype <=2 )){
      $passedList = $this->AssessorModel->allpassedlist($sessionuser,$userid);
      $data['passedList'] = $passedList;
      $this->load->view('view/passed-list',$data);
    }
    else
    {
        redirect(base_url('home'));
    }
}
public function passedList()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    if(!empty($userid) && ($usertype <=2 ))
    {
       $conditions['returnType'] = 'count';
       $totalRec = $this->AssessorModel->allpassedlist($conditions);
        
        //pagination config
        $config['base_url']    = base_url().'passedlist/';
        $config['uri_segment'] = 2;
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 30;
        
        //styling
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="pgactive"><a href="javascript:void(0);">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';
        $config['next_tag_open'] = '<li class="pg-next">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="pg-prev">';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        
        //initialize pagination library
        $this->pagination->initialize($config);
        
        //define offset
        $page = $this->uri->segment(2);
      //  echo"<pre>"; print_r($page); die;
        $offset = !$page?0:$page;
        
        //get rows
        $conditions['returnType'] = '';
        $conditions['start'] = $offset;
        $conditions['limit'] = 30;
        $data['absent'] = $this->AssessorModel->allpassedlist($conditions);
      $this->load->view('view/passed-list',$data);
    }
    else
    {
        redirect(base_url('home'));
    }
}
function addpassed()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    $data['userid'] = $userid;
    if(!empty($userid) && ($usertype <=2 )){
      //echo "<pre>";print_r($_POST);      die;
      if(!empty($this->input->post('submitpassed')) && $this->input->post('submitpassed') == 'save')
      {
        $id = $this->input->post('id');
        $insert_data = array(
                "pass_cnd"=>$this->input->post('pass_cnd'),
                "status"=>$this->input->post('status'),
                );
        $cond = 'id = '.$id;
        $insertid = $this->Common_model->addEditTableByCondpass($insert_data,PASSED,$cond);
        if(!empty($id)){$text = 'updated';}else{$text = 'added';}
        $this->session->set_flashdata('accessormsg','passed'.$text.' succesfully.');
        redirect(base_url('passedlist'));
      }
      elseif($this->input->get('q'))
      {
        $bid =  $this->input->get('q');
        $passedfound = $this->AssessorModel->checkpassed($bid);
        if($passedfound)
        {
          $data['id'] = $bid;
          $data['passeddetail'] = $this->Common_model->get_table_data(PASSED,'id = '.$bid);
          $this->load->view('view/addpassed',$data);
        }
        else
        {
            $batchnotfound = 'The passed you are looking is not exist.';
            $this->session->set_flashdata('batcherror',ltrim($batchnotfound,','));
            redirect(base_url('passedlist'));
        }
      }
      else
      {
        $data['id'] = '';
        $data['passeddetail'] = array();
        $this->load->view('view/addpassed',$data);
      }
    }
    else
    {
        redirect(base_url('home'));
    }
}

 // ======================== failed Candidate=======================================

  public function failedold()
    {
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    if(!empty($userid) && ($usertype <=2 )){
      $failedList = $this->AssessorModel->allfailedlist($sessionuser,$userid);
      $data['failedList'] = $failedList;
      $this->load->view('view/faile-list',$data);
    }
    else
    {
        redirect(base_url('home'));
    }
}
 public function failedList()
   {
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    if(!empty($userid) && ($usertype <=2 ))
    {
       $conditions['returnType'] = 'count';
       $totalRec = $this->AssessorModel->allfailedlist($conditions);
        
        //pagination config
        $config['base_url']    = base_url().'failedlist/';
        $config['uri_segment'] = 2;
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 30;
        
        //styling
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="pgactive"><a href="javascript:void(0);">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';
        $config['next_tag_open'] = '<li class="pg-next">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="pg-prev">';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        
        //initialize pagination library
        $this->pagination->initialize($config);
        
        //define offset
        $page = $this->uri->segment(2);
      //  echo"<pre>"; print_r($page); die;
        $offset = !$page?0:$page;
        
        //get rows
        $conditions['returnType'] = '';
        $conditions['start'] = $offset;
        $conditions['limit'] = 30;
        $data['failed'] = $this->AssessorModel->allfailedlist($conditions);
      $this->load->view('view/faile-list',$data);
    }
    else
    {
        redirect(base_url('home'));
    }
}
function addfailed()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    $data['userid'] = $userid;
    if(!empty($userid) && ($usertype <=2 )){
      //echo "<pre>";print_r($_POST);      die;
      if(!empty($this->input->post('submitfailed')) && $this->input->post('submitfailed') == 'save')
      {
        $id = $this->input->post('id');
        $insert_data = array(
                "faile_cnd"=>$this->input->post('faile_cnd'),
                "status"=>$this->input->post('status'),
                );
        $cond = 'id = '.$id;
        $insertid = $this->Common_model->addEditTableByCondfaile($insert_data,FAILED,$cond);
        if(!empty($id)){$text = 'updated';}else{$text = 'added';}
        $this->session->set_flashdata('accessormsg','failed'.$text.' succesfully.');
        redirect(base_url('failedlist'));
      }
      elseif($this->input->get('q'))
      {
        $bid =  $this->input->get('q');
        $failedfound = $this->AssessorModel->checkfailedlist($bid);
        if($failedfound)
        {
          $data['id'] = $bid;
          $data['passeddetail'] = $this->Common_model->get_table_data(FAILED,'id = '.$bid);
          $this->load->view('view/addfailed',$data);
        }
        else
        {
            $batchnotfound = 'The passed you are looking is not exist.';
            $this->session->set_flashdata('batcherror',ltrim($batchnotfound,','));
            redirect(base_url('failedlist'));
        }
      }
      else
      {
        $data['id'] = '';
        $data['passeddetail'] = array();
        $this->load->view('view/addfailed',$data);
      }
    }
    else
    {
        redirect(base_url('home'));
    }
}

// ======================== dropcount Candidate=======================================

  public function dropcountold()
    {
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    if(!empty($userid) && ($usertype <=2 )){
      $dropcountList = $this->AssessorModel->alldropcountlist($sessionuser,$userid);
      $data['dropcountList'] = $dropcountList;
      $this->load->view('view/dropcount-list',$data);
    }
    else
    {
        redirect(base_url('home'));
    }
}
 public function dropcountList()
   {
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    if(!empty($userid) && ($usertype <=2 ))
    {
       $conditions['returnType'] = 'count';
       $totalRec = $this->AssessorModel->alldropcountlist($conditions);
        
        //pagination config
        $config['base_url']    = base_url().'dropcountlist/';
        $config['uri_segment'] = 2;
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 30;
        
        //styling
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="pgactive"><a href="javascript:void(0);">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';
        $config['next_tag_open'] = '<li class="pg-next">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="pg-prev">';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        
        //initialize pagination library
        $this->pagination->initialize($config);
        
        //define offset
        $page = $this->uri->segment(2);
      //  echo"<pre>"; print_r($page); die;
        $offset = !$page?0:$page;
        
        //get rows
        $conditions['returnType'] = '';
        $conditions['start'] = $offset;
        $conditions['limit'] = 30;
        $data['failed'] = $this->AssessorModel->alldropcountlist($conditions);
      $this->load->view('view/dropcount-list',$data);
    }
    else
    {
        redirect(base_url('home'));
    }
}
function addcountdrop()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    $data['userid'] = $userid;
    if(!empty($userid) && ($usertype <=2 )){
      //echo "<pre>";print_r($_POST);      die;
      if(!empty($this->input->post('submitdropcount')) && $this->input->post('submitdropcount') == 'save')
      {
        $id = $this->input->post('id');
        $insert_data = array(
                "drop_cunt"=>$this->input->post('drop_cunt'),
                "status"=>$this->input->post('status'),
                );
        $cond = 'id = '.$id;
        $insertid = $this->Common_model->addEditTableByCondcountdrop($insert_data,DROPCOUNT,$cond);
        if(!empty($id)){$text = 'updated';}else{$text = 'added';}
        $this->session->set_flashdata('accessormsg','dropcount'.$text.' succesfully.');
        redirect(base_url('dropcountlist'));
      }
      elseif($this->input->get('q'))
      {
        $bid =  $this->input->get('q');
        $dropcountfound = $this->AssessorModel->checkdropcountlist($bid);
        if($dropcountfound)
        {
          $data['id'] = $bid;
          $data['dropcountdetail'] = $this->Common_model->get_table_data(DROPCOUNT,'id = '.$bid);
          $this->load->view('view/addcount',$data);
        }
        else
        {
            $batchnotfound = 'The passed you are looking is not exist.';
            $this->session->set_flashdata('batcherror',ltrim($batchnotfound,','));
            redirect(base_url('dropcountlist'));
        }
      }
      else
      {
        $data['id'] = '';
        $data['dropcountdetail'] = array();
        $this->load->view('view/addcount',$data);
      }
    }
    else
    {
        redirect(base_url('home'));
    }
}

// ======================== assessment Candidate=======================================

  public function assessmentold()
    {
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    if(!empty($userid) && ($usertype <=2 )){
      $dropcountList = $this->AssessorModel->alldropcountlist($sessionuser,$userid);
      $data['dropcountList'] = $dropcountList;
      $this->load->view('view/dropcount-list',$data);
    }
    else
    {
        redirect(base_url('home'));
    }
}
 public function assessment()
   {
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    if(!empty($userid) && ($usertype <=2 ))
    {
       $conditions['returnType'] = 'count';
       $totalRec = $this->AssessorModel->alldropcountlist($conditions);
        
        //pagination config
        $config['base_url']    = base_url().'dropcountlist/';
        $config['uri_segment'] = 2;
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 30;
        
        //styling
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="pgactive"><a href="javascript:void(0);">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';
        $config['next_tag_open'] = '<li class="pg-next">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="pg-prev">';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        
        //initialize pagination library
        $this->pagination->initialize($config);
        
        //define offset
        $page = $this->uri->segment(2);
      //  echo"<pre>"; print_r($page); die;
        $offset = !$page?0:$page;
        
        //get rows
        $conditions['returnType'] = '';
        $conditions['start'] = $offset;
        $conditions['limit'] = 30;
        $data['failed'] = $this->AssessorModel->alldropcountlist($conditions);
      $this->load->view('view/assessment',$data);
    }
    else
    {
        redirect(base_url('home'));
    }
}
function addassessment()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    $data['userid'] = $userid;
    if(!empty($userid) && ($usertype <=2 )){
      //echo "<pre>";print_r($_POST);      die;
      if(!empty($this->input->post('submitdropcount')) && $this->input->post('submitdropcount') == 'save')
      {
        $id = $this->input->post('id');
        $insert_data = array(
                "drop_cunt"=>$this->input->post('drop_cunt'),
                "status"=>$this->input->post('status'),
                );
        $cond = 'id = '.$id;
        $insertid = $this->Common_model->addEditTableByCondcountdrop($insert_data,DROPCOUNT,$cond);
        if(!empty($id)){$text = 'updated';}else{$text = 'added';}
        $this->session->set_flashdata('accessormsg','dropcount'.$text.' succesfully.');
        redirect(base_url('assessment'));
      }
      elseif($this->input->get('q'))
      {
        $bid =  $this->input->get('q');
        $dropcountfound = $this->AssessorModel->checkdropcountlist($bid);
        if($dropcountfound)
        {
          $data['id'] = $bid;
          $data['dropcountdetail'] = $this->Common_model->get_table_data(DROPCOUNT,'id = '.$bid);
          $this->load->view('view/addassessment',$data);
        }
        else
        {
            $batchnotfound = 'The passed you are looking is not exist.';
            $this->session->set_flashdata('batcherror',ltrim($batchnotfound,','));
            redirect(base_url('assessment'));
        }
      }
      else
      {
        $data['id'] = '';
        $data['dropcountdetail'] = array();
        $this->load->view('view/addassessment',$data);
      }
    }
    else
    {
        redirect(base_url('home'));
    }
}
//=======================<proctor>=======================

 public function proctorListold()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    if(!empty($userid) && ($usertype <=2 )){
      $proctorList = $this->ProctorModel->allList($sessionuser,$userid);
      $data['proctorList'] = $proctorList;
      $this->load->view('view/proctor-list',$data);
    }
    else
    {
        redirect(base_url('home'));
    }
}
public function proctorList()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
     if(!empty($userid) && ($usertype <=2 )){
    $conditions['returnType'] = 'count';
    $totalRec = $this->AssessorModel->allproctorList($conditions);
        
        //pagination config
        $config['base_url']    = base_url().'proctorlist/';
        $config['uri_segment'] = 2;
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 30;
        
        //styling
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="pgactive"><a href="javascript:void(0);">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';
        $config['next_tag_open'] = '<li class="pg-next">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="pg-prev">';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        
        //initialize pagination library
        $this->pagination->initialize($config);
        
        //define offset
        $page = $this->uri->segment(2);
      //  echo"<pre>"; print_r($page); die;
        $offset = !$page?0:$page;
        
        //get rows
        $conditions['returnType'] = '';
        $conditions['start'] = $offset;
        $conditions['limit'] = 30;
        $data['proctorLists'] = $this->AssessorModel->allproctorList($conditions);
      
        $this->load->view('view/proctor-list',$data);
      }

    else
    {
        redirect(base_url('home'));
    }
}
function addproctor()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    $data['userid'] = $userid;
    if(!empty($userid) && ($usertype <=2 )){
      
      if(!empty($this->input->post('submitassessor')) && $this->input->post('submitassessor') == 'save')
      {//echo "<pre>";print_r($_POST);      die;
  
          
          
        $id = $this->input->post('id');
        $insert_data = array(
          			"proctorname"=>ucfirst($this->input->post('proctorname')),	
          			"proctoremail"=>$this->input->post('proctoremail'),
          			"state"=>$this->input->post('astate'),
          			"district"=>$this->input->post('district'),
          			"contactnum"=>$this->input->post('contactnum'),
          			"fee_condidate"=>$this->input->post('fee_condidate'),
          			"status"=>$this->input->post('astatus'),
          			"feeper"=>$this->input->post('feeper'),
          			"tabletissued"=>$this->input->post('issused'),
          			"issued_date"=>$this->input->post('issused_date'),
          			"bankname"=>$this->input->post('bankname'),
          			"bankaccount"=>$this->input->post('bankaccount'),
          			"ifsc_code"=>$this->input->post('ifsc_code'),
          			"proctorpan"=>$this->input->post('proctorpan'),
          			"attachment"=>$this->input->post('attachment'),
          			
                      );
                      

          	if(!empty($_FILES['proctorfile']['name'])){
								$upload_loc='files/proctor/';
								$config['upload_path']          = './'.$upload_loc;
								$config['allowed_types']        = 'gif|jpg|png|jpeg';
								$config['encrypt_name']=TRUE;
								
								//Load upload library and initialize configuration
								$this->load->library('upload',$config);
								$this->upload->initialize($config);
								
								if($this->upload->do_upload('proctorfile')){
									$uploadData = $this->upload->data();
									//$details['loc']=$uploadData;
									//$proctorfile=$uploadData;
									$proctorfile = $upload_loc.$uploadData['file_name'];
									$insert_data['uploadfile'] = $proctorfile;
								}else{
									throw new Exception(strip_tags($this->upload->display_errors()));
								}
							}          
//echo "<pre>";print_r($insert_data);      die;
        $cond = 'id = '.$id;
        $insertid = $this->Common_model->addEditTableByCond($insert_data,PROCTOR_LIST,$cond);
        if(!empty($id)){$text = 'updated';}else{$text = 'added';}
        $this->session->set_flashdata('proctormsg','Proctor '.$text.' succesfully.');
        redirect(base_url('proctorlist'));
      }
      elseif($this->input->get('q'))
      {
        $bid =  $this->input->get('q');
        $proctorfound = $this->AssessorModel->checkProctorlist($bid);
        if($proctorfound)
        {
          $data['id'] = $bid;
          $data['proctordetail'] = $this->Common_model->get_table_data(PROCTOR_LIST,'id = '.$bid);
          $cons = 'CountryID = 94';
          $data['states'] = $this->Common_model->get_table_data(STATES,$cons);
          $data['$stateassessor'] = $this->Common_model->getParticularFieldFromTheTableByCond('State',STATES,'Stateid = '.$data['proctordetail'][0]['state']);
          $this->load->view('view/addproctor',$data);
        }
        else
        {
            $batchnotfound = 'The Proctor you are looking is not exist.';
            $this->session->set_flashdata('batcherror',ltrim($batchnotfound,','));
            redirect(base_url('proctorlist'));
        }
      }
      else
      {
        $data['id'] = '';
        $data['proctordetail'] = array();
        $cons = 'CountryID = 94';
        $data['states'] = $this->Common_model->get_table_data(STATES,$cons);
        $this->load->view('view/addproctor',$data);
      }
    }
    else
    {
        redirect(base_url('home'));
    }
}

// ====================<end_abhijeet>=================

public function qpcodeListold()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    if(!empty($userid) && ($usertype <=2 )){
      $qpcodeList = $this->AssessorModel->allqpcodeList($sessionuser,$userid);
      $data['qpcodeList'] = $qpcodeList;
      $this->load->view('view/qpcode-list',$data);
    }
    else
    {
        redirect(base_url('home'));
    }
}
public function qpcodeList()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    if(!empty($userid) && ($usertype <=2 ))
    {
       $conditions['returnType'] = 'count';
    $totalRec = $this->AssessorModel->allqpcodeList($conditions);
        
        //pagination config
        $config['base_url']    = base_url().'qpcodelist/';
        $config['uri_segment'] = 2;
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 30;
        
        //styling
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="pgactive"><a href="javascript:void(0);">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';
        $config['next_tag_open'] = '<li class="pg-next">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="pg-prev">';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        
        //initialize pagination library
        $this->pagination->initialize($config);
        
        //define offset
        $page = $this->uri->segment(2);
      //  echo"<pre>"; print_r($page); die;
        $offset = !$page?0:$page;
        
        //get rows
        $conditions['returnType'] = '';
        $conditions['start'] = $offset;
        $conditions['limit'] = 30;
        $data['qpcodeList'] = $this->AssessorModel->allqpcodeList($conditions);
      
        $this->load->view('view/qpcode-list',$data);
    }
    else
    {
        redirect(base_url('home'));
    }
}
function addqpcode()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    $data['userid'] = $userid;
    if(!empty($userid) && ($usertype <=2 )){
      //echo "<pre>";print_r($_POST);      die;
      if(!empty($this->input->post('submitqpcode')) && $this->input->post('submitqpcode') == 'save')
      {
        $id = $this->input->post('id');
        $insert_data = array(
          			"qp_code"=>$this->input->post('qp_code'),
          			"status"=>$this->input->post('status'),
                      );
        $cond = 'id = '.$id;
        $insertid = $this->Common_model->addEditTableByCond($insert_data,QP_CODE,$cond);
        if(!empty($id)){$text = 'updated';}else{$text = 'added';}
        $this->session->set_flashdata('accessormsg','QP Code '.$text.' succesfully.');
        redirect(base_url('qpcodelist'));
      }
      elseif($this->input->get('q'))  // yeh edit ka section hai
      {
        $bid =  $this->input->get('q');
        $qpcodefound = $this->AssessorModel->checkqpcode($bid);  // yeh function bana do ja ke model mein
        if($qpcodefound)
        {
          $data['id'] = $bid;
          $data['qpcodedetail'] = $this->Common_model->get_table_data(QP_CODE,'id = '.$bid);
          $this->load->view('view/addqpcode',$data);
        }
        else
        {
            $batchnotfound = 'The QP code you are looking does not exist.';
            $this->session->set_flashdata('batcherror',ltrim($batchnotfound,','));
            redirect(base_url('qpcodelist'));
        }
      }
      else
      {
        $data['id'] = '';
        $data['qpcodedetail'] = array();
        $this->load->view('view/addqpcode',$data);
      }
    }
    else
    {
        redirect(base_url('home'));
    }
}

public function qpackListold()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    if(!empty($userid) && ($usertype <=2 )){
      $qpackList = $this->AssessorModel->allqpackList($sessionuser,$userid);
      $data['qpackList'] = $qpackList;
      $this->load->view('view/qpack-list',$data);
    }
    else
    {
        redirect(base_url('home'));
    }
}
public function qpackList()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    if(!empty($userid) && ($usertype <=2 )){
        $conditions['returnType'] = 'count';
    $totalRec = $this->AssessorModel->allqpackList($conditions);
        
        //pagination config
        $config['base_url']    = base_url().'qpacklist/';
        $config['uri_segment'] = 2;
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 30;
        
        //styling
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="pgactive"><a href="javascript:void(0);">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';
        $config['next_tag_open'] = '<li class="pg-next">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="pg-prev">';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        
        //initialize pagination library
        $this->pagination->initialize($config);
        
        //define offset
        $page = $this->uri->segment(2);
      //  echo"<pre>"; print_r($page); die;
        $offset = !$page?0:$page;
        
        //get rows
        $conditions['returnType'] = '';
        $conditions['start'] = $offset;
        $conditions['limit'] = 30;
        $data['qpackList'] = $this->AssessorModel->allqpackList($conditions);
       
        $this->load->view('view/qpack-list',$data);
    }
    else
    {
        redirect(base_url('home'));
    }
}
function addqpack()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    $data['userid'] = $userid;
    if(!empty($userid) && ($usertype <=2 )){
      //echo "<pre>";print_r($_POST);      die;
      if(!empty($this->input->post('submitqpack')) && $this->input->post('submitqpack') == 'save')
      {
        $id = $this->input->post('id');
        $insert_data = array(
          			"qualification_pack"=>$this->input->post('qualification_pack'),
          			"status"=>$this->input->post('status'),
                      );
        $cond = 'id = '.$id;
        $insertid = $this->Common_model->addEditTableByCond($insert_data,Q_PACK,$cond);
        if(!empty($id)){$text = 'updated';}else{$text = 'added';}
        $this->session->set_flashdata('accessormsg','Qualification Pack '.$text.' succesfully.');
        redirect(base_url('qpacklist'));
      }
      elseif($this->input->get('q'))  // yeh edit ka section hai
      {
        $bid =  $this->input->get('q');
        $qpackfound = $this->AssessorModel->checkqpack($bid);  // yeh function bana do ja ke model mein
        if($qpackfound)
        {
          $data['id'] = $bid;
          $data['qpackdetail'] = $this->Common_model->get_table_data(Q_PACK,'id = '.$bid);
          $this->load->view('view/addqpack',$data);
        }
        else
        {
            $batchnotfound = 'The Qualification Pack you are looking does not exist.';
            $this->session->set_flashdata('batcherror',ltrim($batchnotfound,','));
            redirect(base_url('qpacklist'));
        }
      }
      else
      {
        $data['id'] = '';
        $data['qpcodedetail'] = array();
        $this->load->view('view/addqpack',$data);
      }
    }
    else
    {
        redirect(base_url('home'));
    }
}

public function sectorListold()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');     
    if(!empty($userid) && ($usertype <=2 )){
      $sectorList = $this->AssessorModel->allsectorList($sessionuser,$userid);
      $data['sectorList'] = $sectorList;
      $this->load->view('view/sector-list',$data);
    }
    else
    {
        redirect(base_url('home'));
    }
}
public function sectorList()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');     
    if(!empty($userid) && ($usertype <=2 )){
       $conditions['returnType'] = 'count';
    $totalRec = $this->AssessorModel->allsectorList($conditions);
        
        //pagination config
        $config['base_url']    = base_url().'sectorlist/';
        $config['uri_segment'] = 2;
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 30;
        
        //styling
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="pgactive"><a href="javascript:void(0);">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';
        $config['next_tag_open'] = '<li class="pg-next">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="pg-prev">';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        
        //initialize pagination library
        $this->pagination->initialize($config);
        
        //define offset
        $page = $this->uri->segment(2);
      //  echo"<pre>"; print_r($page); die;
        $offset = !$page?0:$page;
        
        //get rows
        $conditions['returnType'] = '';
        $conditions['start'] = $offset;
        $conditions['limit'] = 30;
        $data['sectorList'] = $this->AssessorModel->allsectorList($conditions);
      $this->load->view('view/sector-list',$data);
    }
    else
    {
        redirect(base_url('home'));
    }
}
function addsector()
{
    $data=array();
    $sessionuser = $this->session->userdata(SESSION_PREFIX.'priviliges');
    $userid = $this->session->userdata(SESSION_PREFIX.'user_id');
    $usertype = $this->session->userdata(SESSION_PREFIX.'user_type');
    $data['userid'] = $userid;
    if(!empty($userid) && ($usertype <=2 )){
      //echo "<pre>";print_r($_POST);      die;
      if(!empty($this->input->post('submitsector')) && $this->input->post('submitsector') == 'save')
      {
        $id = $this->input->post('id');
        $insert_data = array(
          			"sector_name"=>$this->input->post('sector_name'),
          			"status"=>$this->input->post('status'),
                      );
        $cond = 'id = '.$id;
        $insertid = $this->Common_model->addEditTableByCond($insert_data,SECTOR,$cond);
        if(!empty($id)){$text = 'updated';}else{$text = 'added';}
        $this->session->set_flashdata('accessormsg','Sector Name '.$text.' succesfully.');
        redirect(base_url('sectorlist'));
      }
      elseif($this->input->get('q'))  // yeh edit ka section hai
      {
        $bid =  $this->input->get('q');
        $sectorfound = $this->AssessorModel->checksector($bid);  // yeh function bana do ja ke model mein
        if($sectorfound)
        {
          $data['id'] = $bid;
          $data['sectordetail'] = $this->Common_model->get_table_data(SECTOR,'id = '.$bid);
          $this->load->view('view/addsector',$data);
        }
        else
        {
            $batchnotfound = 'The Sector Name you are looking does not exist.';
            $this->session->set_flashdata('batcherror',ltrim($batchnotfound,','));
            redirect(base_url('sectorlist'));
        }
      }
      else
      {
        $data['id'] = '';
        $data['sectordetail'] = array();
        $this->load->view('view/addsector',$data);
      }
    }
    else
    {
        redirect(base_url('home'));
    }
}


  function getdata($id="",$type="")
  {
      if($this->input->post('type') == 'delete')
      {
        if($this->input->post('itemtype') == 'assessor')
        {
    		$resultdata = $this->AssessorModel->deleteassessor($this->input->post('id'));
    		if(!empty($resultdata)){
                $this->session->set_flashdata('accessormsg','Assessor Deleted Successfully !!');
                $result = array('code'=>1000,'msg'=>'Assessor Deleted Successfully !!');
    		}else{
                $this->session->set_flashdata('accessormsg','Assessor Not Deleted. Try again...');
                $result = array('code'=>1001,'msg'=>'Assessor Not Deleted. Try again...');
    		}
        }
          if($this->input->post('itemtype') == 'proctor')
        {
    		$resultdata = $this->AssessorModel->deleteproctor($this->input->post('id'));
    		if(!empty($resultdata)){
                $this->session->set_flashdata('accessormsg','Proctor Deleted Successfully !!');
                $result = array('code'=>1000,'msg'=>'Proctor Deleted Successfully !!');
    		}else{
                $this->session->set_flashdata('accessormsg','Proctor Not Deleted. Try again...');
                $result = array('code'=>1001,'msg'=>'Proctor Not Deleted. Try again...');
    		}
        }
        if($this->input->post('itemtype') == 'scheme')
        {
    		$resultdata = $this->AssessorModel->deletescheme($this->input->post('id'));
    		if(!empty($resultdata)){
                $this->session->set_flashdata('accessormsg','Scheme Type Deleted Successfully !!');
                $result = array('code'=>1000,'msg'=>'Assessor Deleted Successfully !!');
    		}else{
                $this->session->set_flashdata('accessormsg','Scheme Type Not Deleted. Try again...');
                $result = array('code'=>1001,'msg'=>'Assessor Not Deleted. Try again...');
    		}
        }
        if($this->input->post('itemtype') == 'schemename')
        {
    		$resultdata = $this->AssessorModel->deleteschemename($this->input->post('id'));
    		if(!empty($resultdata)){
                $this->session->set_flashdata('accessormsg','SchemeName Type Deleted Successfully !!');
                $result = array('code'=>1000,'msg'=>'Assessor Deleted Successfully !!');
    		}else{
                $this->session->set_flashdata('accessormsg','SchemeName Type Not Deleted. Try again...');
                $result = array('code'=>1001,'msg'=>'Assessor Not Deleted. Try again...');
    		}
        }
        if($this->input->post('itemtype') == 'subscheme')
        {
    		$resultdata = $this->AssessorModel->deletesubscheme($this->input->post('id'));
    		if(!empty($resultdata)){
                $this->session->set_flashdata('accessormsg','SubScheme Type Deleted Successfully !!');
                $result = array('code'=>1000,'msg'=>'Assessor Deleted Successfully !!');
    		}else{
                $this->session->set_flashdata('accessormsg','SubScheme Type Not Deleted. Try again...');
                $result = array('code'=>1001,'msg'=>'Assessor Not Deleted. Try again...');
    		}
        }
           if($this->input->post('itemtype') == 'appearedcand')
           {
    		$resultdata = $this->AssessorModel->deleteappearedcand($this->input->post('id'));
    		if(!empty($resultdata)){
                $this->session->set_flashdata('accessormsg','AppearedCand Type Deleted Successfully !!');
                $result = array('code'=>1000,'msg'=>'Assessor Deleted Successfully !!');
    		}else{
                $this->session->set_flashdata('accessormsg','AppearedCand Type Not Deleted. Try again...');
                $result = array('code'=>1001,'msg'=>'Assessor Not Deleted. Try again...');
    		}
        }
        
         if($this->input->post('itemtype') == 'passed')
           {
    		$resultdata = $this->AssessorModel->deletepassed($this->input->post('id'));
    		if(!empty($resultdata)){
                $this->session->set_flashdata('accessormsg','passed Type Deleted Successfully !!');
                $result = array('code'=>1000,'msg'=>'Assessor Deleted Successfully !!');
    		}else{
                $this->session->set_flashdata('accessormsg','passed Type Not Deleted. Try again...');
                $result = array('code'=>1001,'msg'=>'Assessor Not Deleted. Try again...');
    		}
        }
        if($this->input->post('itemtype') == 'failed')
           {
    		$resultdata = $this->AssessorModel->deletefailed($this->input->post('id'));
    		if(!empty($resultdata)){
                $this->session->set_flashdata('accessormsg','failed Type Deleted Successfully !!');
                $result = array('code'=>1000,'msg'=>'Assessor Deleted Successfully !!');
    		}else{
                $this->session->set_flashdata('accessormsg','failed Type Not Deleted. Try again...');
                $result = array('code'=>1001,'msg'=>'Assessor Not Deleted. Try again...');
    		}
        }
      if($this->input->post('itemtype') == 'qpcode')
      {
    		$resultdata = $this->AssessorModel->deleteqpcode($this->input->post('id'));
    		if(!empty($resultdata)){
                $this->session->set_flashdata('accessormsg','QP code Deleted Successfully !!');
                $result = array('code'=>1000,'msg'=>'Assessor Deleted Successfully !!');
    		}else{
                $this->session->set_flashdata('accessormsg','QP code Not Deleted. Try again...');
                $result = array('code'=>1001,'msg'=>'Assessor Not Deleted. Try again...');
    		}
       }
       if($this->input->post('itemtype') == 'qpack')
      {
    		$resultdata = $this->AssessorModel->deleteqpack($this->input->post('id'));
    		if(!empty($resultdata)){
                $this->session->set_flashdata('accessormsg','Qualification Pack Deleted Successfully !!');
                $result = array('code'=>1000,'msg'=>'Assessor Deleted Successfully !!');
    		}else{
                $this->session->set_flashdata('accessormsg','Qualification Pack Not Deleted. Try again...');
                $result = array('code'=>1001,'msg'=>'Assessor Not Deleted. Try again...');
    		}
       }
       if($this->input->post('itemtype') == 'sector')
      {
    		$resultdata = $this->AssessorModel->deletesector($this->input->post('id'));
    		if(!empty($resultdata)){
                $this->session->set_flashdata('accessormsg','Sector Deleted Successfully !!');
                $result = array('code'=>1000,'msg'=>'Assessor Deleted Successfully !!');
    		}else{
                $this->session->set_flashdata('accessormsg','Sector Not Deleted. Try again...');
                $result = array('code'=>1001,'msg'=>'Assessor Not Deleted. Try again...');
    		}
       }
       if($this->input->post('itemtype') == 'user')
      {
    		$resultdata = $this->AssessorModel->deleteuser($this->input->post('id'));
    		if(!empty($resultdata)){
                $this->session->set_flashdata('accessormsg','User Deleted Successfully !!');
                $result = array('code'=>1000,'msg'=>'User Deleted Successfully !!');
    		}else{
                $this->session->set_flashdata('accessormsg','User Not Deleted. Try again...');
                $result = array('code'=>1001,'msg'=>'User Not Deleted. Try again...');
    		}
       }
      }
      if($this->input->post('type') == 'usertype')
      {
          $con = 'status = 1 AND id > 2';
          $result = $this->Common_model->get_table_data(USERTYPE_TBL,$con);
          //print_r($result);
          //$result = array('code'=>1000,'msg'=>'User Deleted Successfully !!');
      }       
  /*    if($this->input->post('type') == 'add')
      {
  		$resultdata = $this->AssessorModel->deleteassessor($this->input->post('id'));
  		if(!empty($resultdata)){
              $this->session->set_flashdata('accessormsg','Assessor Deleted Successfully !!');
              $result = array('code'=>1000,'msg'=>'Assessor Deleted Successfully !!');
  		}else{
              $this->session->set_flashdata('accessormsg','Assessor Not Deleted. Try again...');
              $result = array('code'=>1001,'msg'=>'Assessor Not Deleted. Try again...');
  		}
      }*/
      // yeh code pahle se tah kya? haan ok. jaroort nahi iski
      print_r(json_encode($result, true));
      }
      
      public function getcityAjax()
{
    $stateid= $_POST['state_id'];
          $stRes = $this->Common_model->get_table_data(CITY,'Stateid = '.$stateid); 
          //print_r($stRes);
         echo $city = '<option value="">Select District</option>';

    foreach ($stRes as $c) {
     
        echo $city ='<option value="'.$c["CityID"].'">'.$c["City"].' </option>';

    }
    
}
}
