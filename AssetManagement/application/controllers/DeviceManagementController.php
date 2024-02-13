<?php
defined('BASEPATH') or exit('No direct script access allowed');
class DeviceManagementController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->checkUserSessionExist();
    }
    public function index()
    {
        $this->data['title'] = 'Add Device';
        $this->load->view('web/includes/header', $this->data);
        $this->load->view('web/devicemanagement/add_device');
        $this->load->view('web/includes/footer');
    }
    public function saveDevice()
    {
        if (!postAllowed()) {
            redirect('add_device');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('device_name', 'Device Name', 'required');
        $this->form_validation->set_rules('device_id', 'Device Id', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->index();
            return;
        }

        $device_id = postDataFilterhtml($this->input->post('device_id'));

        $whereData = [
            'device_id' => $device_id
        ];

        $response = $this->DeviceManagementModel->getCount('device_management', $whereData);

        if ($response == ZERO_COUNT) {
            $insertData = [
                'device_name' => postDataFilterhtml($this->input->post('device_name')),
                'device_id' => $device_id,
                'status' => ACTIVE_STATUS,
                'created_by' => $this->getLoggedInUser()->user_id,
                'created_dt' => getCurrentTime(),
            ];
            $response = $this->DeviceManagementModel->insertData('device_management', $insertData);

            if ($response != FAILED) {
                $color = 'success';
                $message = "$device_id device added Successfully";
            } else {
                $color = 'danger';
                $message = "Database Problem";
            }
        } else {
            $color = 'warning';
            $message = "$device_id device already added";
        }
        $this->redirectWithMessage($color, $message, 'add_device');
    }

    public function viewDevices()
    {
        $this->data['title'] = 'View Devices';
        $this->data['page_data'] = $this->DeviceManagementModel->getByTableName('device_management');
        $this->load->view('web/includes/header', $this->data);
        $this->load->view('web/devicemanagement/view_devices');
        $this->load->view('web/includes/footer');
    }

    public function viewDeviceProfile($id)
    {
        $id = (int)base64_decode($id);
        $response = $this->DeviceManagementModel->getCount('device_management', ['id' => $id]);

        if ($response != SUCCESS) {
            $color = 'danger';
            $message = "Device does not exist";
            $this->redirectWithMessage($color, $message, 'view_devices');
        }

        $this->data['title'] = 'View Device';
        $this->data['page_data'] = $this->DeviceManagementModel->getSingleRowWithWhere('*', 'device_management', ['id' => $id]);
        $this->load->view('web/includes/header', $this->data);
        $this->load->view('web/devicemanagement/view_device_profile');
        $this->load->view('web/includes/footer');
    }

    public function changeStatus(string $id = '', string $status = '')
    {
        $id = (int)base64_decode($id);
        $status = (int)base64_decode($status);
        $response = $this->DeviceManagementModel->getCount('device_management', ['id' => $id]);

        if ($response != SUCCESS) {
            $color = 'danger';
            $message = "Device does not exist";
        } else {
            $response = $this->DeviceManagementModel->updateData(
                'device_management',
                ['id' => $id],
                ['status' =>  !$status]     // making active or inactive by adding not condtion
            );
            if ($response == SUCCESS) {
                $color = 'success';
                $message = "Device Status Changed Successfully";
            } else {
                $color = 'danger';
                $message = "Database Problem";
            }
        }
        $this->redirectWithMessage($color, $message, 'view_devices');
    }

    public function editDeviceProfile($id)
    {
        $id = (int)base64_decode($id);
        $response = $this->UserManagmentModel->getCount('device_management', ['id' => $id]);

        if ($response != SUCCESS) {
            $color = 'danger';
            $message = "device does not exist";
            $this->redirectWithMessage($color, $message, 'view_devices');
        }

        $this->data['title'] = 'Edit Device';
        $this->data['page_data'] = $this->UserManagmentModel->getSingleRowWithWhere('*', 'device_management', ['id' => $id]);
        $this->load->view('web/includes/header', $this->data);
        $this->load->view('web/devicemanagement/edit_device_profile');
        $this->load->view('web/includes/footer');
    }

    public function saveUpdateDevice()
    {
        if (!postAllowed()) {
            redirect('view_devices');
        }
        $id = $this->input->post('id');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('device_name', 'Company Name', 'required');
        $this->form_validation->set_rules('device_id', 'Company Name', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->index();
            return redirect("edit_device_profile/" . base64_encode($id));
        }

        $updateData = [
            'device_name' => postDataFilterhtml($this->input->post('device_id')),
            'device_id' => postDataFilterhtml($this->input->post('device_name')),
            'modified_by' => $this->getLoggedInUser()->user_id,
            'modified_dt' => getCurrentTime(),
        ];
        $response = $this->UserManagmentModel->updateData('device_management', ['id' => $id], $updateData);

        if ($response > FAILED) {
            $color = 'success';
            $message = "Device updated Successfully";
        } else {
            $color = 'danger';
            $message = "Database Problem";
        }
        $this->redirectWithMessage($color, $message, 'view_devices');
    }
}
