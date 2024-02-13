<?php
defined('BASEPATH') or exit('No direct script access allowed');
class ProfileController extends MY_Controller
{
    private $tableName = 'user_management';

    public function __construct()
    {
        parent::__construct();
        $this->checkUserSessionExist();
    }
    public function index()
    {
        $this->data['title'] = 'My Profile';
        $this->data['page_data'] = $this->getLoggedInUser();
        $this->load->view('web/includes/header', $this->data);
        $this->load->view('web/profile/my_profile');
        $this->load->view('web/includes/footer');
    }
    public function updateProfile()
    {
        $this->data['title'] = 'Update Profile';
        $this->data['page_data'] = $this->getLoggedInUser();
        $this->load->view('web/includes/header', $this->data);
        $this->load->view('web/profile/update_profile');
        $this->load->view('web/includes/footer');
    }
    public function saveProfile()
    {
        if (!postAllowed()) {
            redirect('my_profile');
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('first_name', 'First Name', 'required|min_length[2]');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|min_length[2]');
        if ($this->form_validation->run() == FALSE) {
            $this->index();
            return false;
        }

        $databaseData = [
            'first_name' => postDataFilterhtml($this->input->post('first_name')),
            'last_name' => postDataFilterhtml($this->input->post('last_name')),
        ];

        $whereData = [
            'user_id' =>  $this->getLoggedInUser()->user_id
        ];

        $response = $this->ProfileModel->updateData($this->tableName, $whereData, $databaseData);

        if ($response == SUCCESS) {
            $color = 'success';
            $message = 'Profile Updated Successfully';
        } else if ($response == FAILED) {
            $color = 'danger';
            $message = 'Database Problem';
        }
        $this->redirectWithMessage($color, $message, 'update_profile');
    }

    public function changePassword()
    {
        $this->data['title'] = 'Change Password';
        $this->load->view('web/includes/header', $this->data);
        $this->load->view('web/profile/change_password');
        $this->load->view('web/includes/footer');
    }
    public function savePassword()
    {
        if (!postAllowed()) {
            redirect('change_password');
        }

        $userId = $this->getLoggedInUser()->user_id;

        $whereData = [
            'user_id' => $userId,
            'password' => md5(postDataFilterhtml($this->input->post('old_password')))
        ];

        $response = $this->ProfileModel->getCount($this->tableName, $whereData);
        if ($response == SUCCESS) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('new_password', 'Password', 'required');
            $this->form_validation->set_rules('con_password', 'Confirm Password', 'required|matches[new_password]');
            if ($this->form_validation->run() == FALSE) {
                $this->changePassword();
                return false;
            }

            $whereData = [
                'user_id' => $userId,
            ];

            $updateData = [
                'password' => md5(postDataFilterhtml($this->input->post('new_password')))
            ];

            $response = $this->ProfileModel->updateData($this->tableName, $whereData, $updateData);
            if ($response == SUCCESS) {
                $color = 'success';
                $message = 'Password Updated Successfully';
            } else if ($response == FAILED) {
                $color = 'danger';
                $message = 'Database Problem';
            }
        } else {
            $color = 'danger';
            $message = 'Old Password do not matched';
        }

        $this->redirectWithMessage($color, $message, 'change_password');
    }
    public function logout()
    {
        $color = 'success';
        $message = 'Logout Succesfully';
        $this->session->sess_destroy();
        $this->redirectWithMessage($color, $message, 'login');
    }
}
