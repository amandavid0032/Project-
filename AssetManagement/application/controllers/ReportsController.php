<?php
defined('BASEPATH') or exit('No direct script access allowed');
class ReportsController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->checkUserSessionExist();
    }

    public function scannedTags()
    {
        $this->data['title'] = 'Scanned Reports';
        $this->data['audit_data'] = $this->ReportsModel->getByTableName('audit_management');
        if (isset($_POST['audit_id'])) {
            $auditId = (int)$this->input->post('audit_id');
            $this->data['page_data'] = $this->ReportsModel->getScannedUnscannedTags($auditId, true);
        } else {
            $this->data['page_data'] = [];
        }
        $this->load->view('web/includes/header', $this->data);
        $this->load->view('web/reports/scanned_tags');
        $this->load->view('web/includes/footer');
    }
    public function unscannedTags()
    {
        $this->data['title'] = 'Unscanned Reports';

        if (isset($_POST['audit_id'])) {
            $auditId = (int)$this->input->post('audit_id');
            $this->data['page_data'] = $this->ReportsModel->getScannedUnscannedTags($auditId, false);
        } else {
            $this->data['page_data'] = [];
        }
        $this->data['audit_data'] = $this->ReportsModel->getByTableName('audit_management');
        $this->load->view('web/includes/header', $this->data);
        $this->load->view('web/reports/unscanned_tags');
        $this->load->view('web/includes/footer');
    }
    public function logFile()
    {
        $this->data['title'] = 'Log Report';
        $this->data['page_data'] = $this->ReportsModel->getByTableName('log_table');
        $this->load->view('web/includes/header', $this->data);
        $this->load->view('web/reports/log_report');
        $this->load->view('web/includes/footer');
    }
    public function movedTags()
    {
        $this->data['title'] = 'Moved Asset Report';
        $this->data['page_data'] = $this->ReportsModel->getDataByWhereByOrderBy('*', 'assets', ['moved_status' => 1], 'created_dt', 'ASC');
        $this->load->view('web/includes/header', $this->data);
        $this->load->view('web/reports/moved_tags');
        $this->load->view('web/includes/footer');
    }
    public function removeLogs()
    {
        $response = $this->ReportsModel->truncateTable('log_table');
        if ($response == SUCCESS) {
            $color = 'success';
            $message = 'All log data deleted successfully';
            $redirect = 'log_file';
        } else {
            $color = 'danger';
            $message = 'Database Problem';
            $redirect = 'log_file';
        }
        $this->redirectWithMessage($color, $message, $redirect);
    }
}
