<?php
defined('BASEPATH') or exit('No direct script access allowed');
class LoginController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->checkUserSessionExistForLogin();
    }
    public function index()
    {
        $this->data['title'] = 'Login';
        $this->load->view('web/login/login', $this->data);
    }
    public function check()
    {
        if (!postAllowed()) {
            redirect('login');
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('phone_number', 'Phone Number', 'required|min_length[10]|numeric');
        $this->form_validation->set_rules('password', 'Password', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->index();
            return false;
        }

        $databaseData = [
            'phone_number' => postDataFilterhtml($this->input->post('phone_number')),
            'password' => md5(postDataFilterhtml($this->input->post('password'))),
        ];
        
        $response =  $this->LoginModel->checkLogin($databaseData);

        if($response['status'] == SUCCESS){
            $color = 'success';
            $message = $response['message'];
            $redirect = 'dashboard';
        } else if ($response['status'] == NOT_ACTIVE_ANYMORE){
            $redirect = 'login';
            $message = $response['message']; 
            $color = 'warning';
        } else if ($response['status'] == FAILED){
            $redirect = 'login';
            $message = $response['message']; 
            $color = 'danger';
        }
        $this->redirectWithMessage($color,$message,$redirect);
    }
}
