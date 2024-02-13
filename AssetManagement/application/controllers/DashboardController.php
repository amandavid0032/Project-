<?php
defined('BASEPATH') or exit('No direct script access allowed');
class DashboardController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->checkUserSessionExist();
    }
    public function index()
    {
        $this->data['title'] = 'DashBoard';
        $this->data['location_count'] = $this->LocationManagmentModel->getcount('location_management');
        $this->data['user_count'] = $this->UserManagmentModel->getcount('user_management');
        $this->data['asset_count'] = $this->AssetManagementModel->getcount('assets');
        $this->load->view('web/includes/header', $this->data);
        $this->load->view('web/dashboard/dashboard');
        $this->load->view('web/includes/footer');
    }
}
