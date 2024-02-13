<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

    public $data;

    public function __construct()
    {
        parent::__construct();
        $this->data['company'] = 'WAVELINX | ASSET MANAGEMENT SYSTEM';
        $this->data['user_type'] = isset($this->getLoggedInUser()->user_type) ? $this->getLoggedInUser()->user_type : null;
        $this->data['check_excle'] = $this->AssetManagementModel->getCount('assets');
    }
    public function redirectWithMessage(
        string $color = '',
        string $message = '',
        string $redirect = ''
    ): void {
        $this->session->set_flashdata('color', $color);
        $this->session->set_flashdata('message', $message);
        redirect($redirect);
    }
    public function getLoggedInUser(): ?object
    {
        $userId = $this->session->userdata('id');
        return $this->ProfileModel->getSingleRowWithWhere(
            'user_id,first_name,last_name,phone_number,user_type,status,created_dt,last_login',
            'user_management',
            ['user_id' => $userId]
        );
    }

    protected function checkUserSessionExist()
    {
        if (!$this->session->userdata('id')) {
            redirect('login');
        }
    }

    protected function checkUserSessionExistForLogin()
    {
        if ($this->session->userdata('id')) {
            redirect('dashboard');
        }
    }
}
