<?php
defined('BASEPATH') or exit('No direct script access allowed');
class LocationManagmentController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->checkUserSessionExist();
    }
    public function index()
    {
        $this->addLocation();
    }
    public function addLocation()
    {
        $this->data['title'] = 'Add Location';
        $this->data['page_data'] = $this->CompanyManagementModel->getDataByWhereByOrderBy('*', 'company_management', ['status' => 1], 'company_name', 'ASC');
        $this->load->view('web/includes/header', $this->data);
        $this->load->view('web/locationmanagement/add_location');
        $this->load->view('web/includes/footer');
    }
    public function saveLocation()
    {
        if (!postAllowed()) {
            redirect('add_location');
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('location_name', 'Location Name', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->index();
            return;
        }

        $location_name = postDataFilterhtml($this->input->post('location_name'));

        $whereData = [
            'location_name' => strtolower($location_name)
        ];

        $response = $this->CompanyManagementModel->getCount('location_management', $whereData);

        if ($response == FAILED) {
            $insertData = [
                'company_id' => postDataFilterhtml($this->input->post('company_id')),
                'location_name' => strtolower($location_name),
                'status' => 1,
                'created_by' => $this->getLoggedInUser()->user_id,
                'created_dt' => getCurrentTime(),
            ];
            $response = $this->LocationManagmentModel->insertData('location_management', $insertData);

            if ($response > FAILED) {
                $color = 'success';
                $message = "$location_name location added Successfully";
            } else {
                $color = 'danger';
                $message = "Database Problem";
            }
        } else {
            $color = 'warning';
            $message = "$location_name location already Created";
        }
        $this->redirectWithMessage($color, $message, 'add_location');
    }

    public function viewLocations()
    {
        $this->data['title'] = 'View Locations';
        $this->data['page_data'] = $this->LocationManagmentModel->getAllLoations();
        $this->load->view('web/includes/header', $this->data);
        $this->load->view('web/locationmanagement/view_locations');
        $this->load->view('web/includes/footer');
    }

    public function viewLocationProfile($id)
    {
        $id = (int)base64_decode($id);
        $response = $this->LocationManagmentModel->getCount('location_management', ['pid' => $id]);

        if ($response != SUCCESS) {
            $color = 'danger';
            $message = "Location does not exist";
            $this->redirectWithMessage($color, $message, 'view_locations');
        }

        $this->data['title'] = 'View Locations';
        $this->data['page_data'] = $this->LocationManagmentModel->getLocationWithWhere($id);
        $this->load->view('web/includes/header', $this->data);
        $this->load->view('web/locationmanagement/view_location_profile');
        $this->load->view('web/includes/footer');
    }

    public function changeStatus(string $id = '', string $status = '')
    {
        $id = (int)base64_decode($id);
        $status = (int)base64_decode($status);
        $response = $this->LocationManagmentModel->getCount('location_management', ['pid' => $id]);

        if ($response != SUCCESS) {
            $color = 'danger';
            $message = "Location does not exist";
        } else {
            $response = $this->LocationManagmentModel->updateData(
                'location_management',
                ['pid' => $id],
                ['status' =>  !$status]     // making active or inactive by adding not condtion
            );
            if ($response == SUCCESS) {
                $color = 'success';
                $message = "Location Status Changed Successfully";
            } else {
                $color = 'danger';
                $message = "Database Problem";
            }
        }
        $this->redirectWithMessage($color, $message, 'view_locations');
    }

    public function editLocationProfile($id)
    {
        $id = (int)base64_decode($id);
        $response = $this->LocationManagmentModel->getCount('location_management', ['pid' => $id]);

        if ($response != SUCCESS) {
            $color = 'danger';
            $message = "Location does not exist";
            $this->redirectWithMessage($color, $message, 'view_locations');
        }

        $this->data['title'] = 'Edit Location';
        $this->data['page_data'] = $this->CompanyManagementModel->getDataByWhereByOrderBy('*', 'company_management', ['status' => 1], 'company_name', 'ASC');
        $this->data['page_data_database'] = $this->LocationManagmentModel->getLocationWithWhere($id);
        $this->load->view('web/includes/header', $this->data);
        $this->load->view('web/locationmanagement/edit_location_profile');
        $this->load->view('web/includes/footer');
    }

    public function saveUpdateLocation()
    {
        if (!postAllowed()) {
            redirect('view_projectss');
        }
        $id = $this->input->post('project_id');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('location_name', 'Location Name', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->index();
            return redirect("edit_project_profile/" . base64_encode($id));
        }

        $updateData = [
            'company_id' => postDataFilterhtml($this->input->post('company_id')),
            'location_name' => strtolower(postDataFilterhtml($this->input->post('location_name'))),
            'modified_by' => $this->getLoggedInUser()->user_id,
            'modified_dt' => getCurrentTime(),
        ];
        $response = $this->UserManagmentModel->updateData('location_management', ['pid' => $id], $updateData);

        if ($response > FAILED) {
            $color = 'success';
            $message = "Location updated Successfully";
        } else {
            $color = 'danger';
            $message = "Database Problem";
        }
        $this->redirectWithMessage($color, $message, 'view_locations');
    }

    public function getProjects()
    {
        if (!postAllowed()) {
            return false;
        }

        $companyId = $this->input->post('company_id');
        $data = $this->LocationManagmentModel->getDataByWhereByOrderBy(
            'pid,project_name',
            'project_management',
            ['company_id' => $companyId],
            'project_name',
            'ASC'
        );
        header('Content-Type: application/json');
        if (!empty($data)) {
            echo json_encode($data);
        } else {
            echo json_encode([]);
        }
    }
}
