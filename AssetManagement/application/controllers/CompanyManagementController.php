<?php
defined('BASEPATH') or exit('No direct script access allowed');
class CompanyManagementController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->checkUserSessionExist();
    }
    public function index()
    {
        $this->data['title'] = 'Add Company';
        $this->load->view('web/includes/header', $this->data);
        $this->load->view('web/companymanagement/add_company');
        $this->load->view('web/includes/footer');
    }
    public function saveCompany()
    {

        if (!postAllowed()) {
            redirect('add_company');
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('company_name', 'Company Name', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->index();
            return;
        }

        $company_name = postDataFilterhtml($this->input->post('company_name'));

        $whereData = [
            'company_name' => strtolower($company_name)
        ];

        $response = $this->CompanyManagementModel->getCount('company_management', $whereData);

        if ($response == FAILED) {
            $insertData = [
                'company_name' => strtolower($company_name),
                'status' => 1,
                'created_by' => $this->getLoggedInUser()->user_id,
                'created_dt' => getCurrentTime(),
            ];
            $response = $this->CompanyManagementModel->insertData('company_management', $insertData);

            if ($response > FAILED) {
                $color = 'success';
                $message = "$company_name company created Successfully";
            } else {
                $color = 'danger';
                $message = "Database Problem";
            }
        } else {
            $color = 'warning';
            $message = "$company_name company already Created";
        }
        $this->redirectWithMessage($color, $message, 'add_company');
    }

    public function viewCompanies()
    {
        $this->data['title'] = 'View Companies';
        $this->data['page_data'] = $this->CompanyManagementModel->getByTableName('company_management');
        $this->load->view('web/includes/header', $this->data);
        $this->load->view('web/companymanagement/view_companies');
        $this->load->view('web/includes/footer');
    }

    public function viewCompanyProfile($id)
    {
        $id = (int)base64_decode($id);
        $response = $this->CompanyManagementModel->getCount('company_management', ['cid' => $id]);

        if ($response != SUCCESS) {
            $color = 'danger';
            $message = "Company does not exist";
            $this->redirectWithMessage($color, $message, 'view_companies');
        }

        $this->data['title'] = 'View Company';
        $this->data['page_data'] = $this->CompanyManagementModel->getUserProfileWithWhere($id);
        $this->load->view('web/includes/header', $this->data);
        $this->load->view('web/companymanagement/view_company_profile');
        $this->load->view('web/includes/footer');
    }

    public function changeStatus(string $id = '', string $status = '')
    {
        $id = (int)base64_decode($id);
        $status = (int)base64_decode($status);
        $response = $this->CompanyManagementModel->getCount('company_management', ['cid' => $id]);

        if ($response != SUCCESS) {
            $color = 'danger';
            $message = "Company does not exist";
        } else {
            $response = $this->CompanyManagementModel->updateData(
                'company_management',
                ['cid' => $id],
                ['status' =>  !$status]     // making active or inactive by adding not condtion
            );
            if ($response == SUCCESS) {
                $color = 'success';
                $message = "Company Status Changed Successfully";
            } else {
                $color = 'danger';
                $message = "Database Problem";
            }
        }
        $this->redirectWithMessage($color, $message, 'view_companies');
    }

    public function editCompanyProfile($id)
    {
        $id = (int)base64_decode($id);
        $response = $this->UserManagmentModel->getCount('company_management', ['cid' => $id]);

        if ($response != SUCCESS) {
            $color = 'danger';
            $message = "Company does not exist";
            $this->redirectWithMessage($color, $message, 'view_companies');
        }

        $this->data['title'] = 'Edit User';
        $this->data['page_data'] = $this->UserManagmentModel->getSingleRowWithWhere('*', 'company_management', ['cid' => $id]);
        $this->load->view('web/includes/header', $this->data);
        $this->load->view('web/companymanagement/edit_company_profile');
        $this->load->view('web/includes/footer');
    }

    public function saveUpdateCompany()
    {
        if (!postAllowed()) {
            redirect('view_companies');
        }
        $id = $this->input->post('company_id');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('company_name', 'Company Name', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->index();
            return redirect("edit_company_profile/" . base64_encode($id));
        }

        $updateData = [
            'company_name' => postDataFilterhtml($this->input->post('company_name')),
            'modified_by' => $this->getLoggedInUser()->user_id,
            'modified_dt' => getCurrentTime(),
        ];
        $response = $this->UserManagmentModel->updateData('company_management', ['cid' => $id], $updateData);

        if ($response > FAILED) {
            $color = 'success';
            $message = "Company updated Successfully";
        } else {
            $color = 'danger';
            $message = "Database Problem";
        }
        $this->redirectWithMessage($color, $message, 'view_companies');
    }
}
