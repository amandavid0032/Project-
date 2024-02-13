<?php
defined('BASEPATH') or exit('No direct script access allowed');
class AuditManagementController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->checkUserSessionExist();
    }
    public function index()
    {
        $this->data['title'] = 'Add audit';
        $this->data['page_data'] = $this->AuditManagementModel->getDataByWhereByOrderBy('pid,location_name', 'location_management', ['status' => 1]);
        $this->load->view('web/includes/header', $this->data);
        $this->load->view('web/auditmanagement/add_audit');
        $this->load->view('web/includes/footer');
    }
    public function saveAudit()
    {
        if (!postAllowed()) {
            redirect('add_audit');
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('audit_name', 'audit Name', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->index();
            return;
        }
        $audit_name = postDataFilterhtml($this->input->post('audit_name'));
        $whereData = [
            'audit_name' => strtolower(postDataFilterhtml($audit_name))
        ];

        $response = $this->LocationManagmentModel->getCount('audit_management', $whereData);
        if ($response == FAILED) {
            $insertData = [
                'audit_name' => $audit_name,
                'location_id' => $this->input->post('location_id'),
                'end_date' => $this->input->post('end_date'),
                'status' => 1,
                'created_by' => $this->getLoggedInUser()->user_id,
                'created_dt' => getCurrentTime(),
            ];

            $emailMessage = ucwords($audit_name) . " audit created successfully and It will start from " . $insertData['created_dt'] . " and end on  " . yearMonthDayDate($insertData['end_date']);

            $response = $this->LocationManagmentModel->insertData('audit_management', $insertData);
            if ($response != FAILED) {

                $this->emaillibrary->sendSingleMail(
                    'Audit Inpection Start',
                    'Sarbdeep Singh',
                    'demo@wavelinx.in',
                    'deepinder999@gmail.com',
                    // 'sarbdeep@wavelinx.in',
                    $emailMessage,
                    '',
                    'html'
                );

                $color = 'success';
                $message = ucwords($audit_name) . " audit added Successfully";
            } else {
                $color = 'danger';
                $message = "Database Problem";
            }
        } else {
            $color = 'warning';
            $message = ucwords($audit_name) . " audit already Created";
        }
        $this->redirectWithMessage($color, $message, 'add_audit');
    }
    public function viewAudit()
    {
        $this->data['title'] = 'View audit';
        $this->data['page_data'] = $this->AuditManagementModel->getAllAudit();
        $this->load->view('web/includes/header', $this->data);
        $this->load->view('web/auditmanagement/view_audit');
        $this->load->view('web/includes/footer');
    }
    public function viewAuditProfile($id)
    {
        $id = (int)base64_decode($id);
        $response = $this->AuditManagementModel->getCount('audit_management', ['aid' => $id]);

        if ($response != SUCCESS) {
            $color = 'danger';
            $message = "audit does not exist";
            $this->redirectWithMessage($color, $message, 'view_audit');
        }

        $this->data['title'] = 'View audit';
        $this->data['page_data'] = $this->AuditManagementModel->getAuditProfileWithWhere($id);
        $this->load->view('web/includes/header', $this->data);
        $this->load->view('web/auditmanagement/view_audit_profile');
        $this->load->view('web/includes/footer');
    }
    public function editAuditProfile($id)
    {
        $id = (int)base64_decode($id);
        $response = $this->AuditManagementModel->getCount('audit_management', ['aid' => $id]);

        if ($response != SUCCESS) {
            $color = 'danger';
            $message = "aduit does not exist";
            $this->redirectWithMessage($color, $message, 'view_companies');
        }

        $this->data['title'] = 'Edit audit ';
        $this->data['page_data'] = $this->LocationManagmentModel->getDataByWhereByOrderBy('*', 'location_management',  ['status' => 1], 'location_name', 'ASC');
        $this->data['page_data_database'] = $this->AuditManagementModel->getAuditProfileWithWhere($id);
        $this->load->view('web/includes/header', $this->data);
        $this->load->view('web/auditmanagement/edit_audit_profile');
        $this->load->view('web/includes/footer');
    }
    public function saveUpdateAudit()
    {
        if (!postAllowed()) {
            redirect('view_audit');
        }
        $id = $this->input->post('project_id');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('audit_name', 'audit Name', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->index();
            return redirect("edit_audit_profile/" . base64_encode($id));
        }
        $updateData = [
            'audit_name' => strtolower(postDataFilterhtml($this->input->post('audit_name'))),
            'location_id' => postDataFilterhtml($this->input->post('location_id')),
            'end_date' => postDataFilterhtml($this->input->post('end_date')),
            'modified_by' => $this->getLoggedInUser()->user_id,
            'modified_dt' => getCurrentTime(),
        ];
        $response = $this->UserManagmentModel->updateData('audit_management', ['aid' => $id], $updateData);
        if ($response > FAILED) {
            $color = 'success';
            $message = "Audit updated Successfully";
        } else {
            $color = 'danger';
            $message = "Database Problem";
        }
        $this->redirectWithMessage($color, $message, 'view_audit');
    }

    public function changeStatus(string $id = '', string $status = '')
    {
        $id = (int)base64_decode($id);
        $status = (int)base64_decode($status);
        $response = $this->AuditManagementModel->getCount('audit_management', ['aid' => $id]);

        if ($response != SUCCESS) {
            $color = 'danger';
            $message = "Audit does not exist";
        } else {
            $response = $this->AuditManagementModel->updateData(
                'audit_management',
                ['aid' => $id],
                ['status' =>  !$status]     // making active or inactive by adding not condtion
            );
            if ($response == SUCCESS) {
                $color = 'success';
                $message = "Audit status changed Successfully";
            } else {
                $color = 'danger';
                $message = "Database Problem";
            }
        }
        $this->redirectWithMessage($color, $message, 'view_audit');
    }
}
