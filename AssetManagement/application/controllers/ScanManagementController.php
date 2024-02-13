<?php
defined('BASEPATH') or exit('No direct script access allowed');
class ScanManagementController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->checkUserSessionExist();
    }
    public function index()
    {
    }
    public function readRfidTags()
    {
        $this->data['title'] = 'Read RFID Tags';

        $this->data['totalCount'] = $this->ScanManagementModel->getCount('assets', [
            'rfid_read_by' => $this->getLoggedInUser()->user_id,
            'rfid_read_status' => YES_READ_STATUS
        ]);
        $this->load->view('web/includes/header', $this->data);
        $this->load->view('web/scanmanagement/read_rfid_tags');
    }
    public function saveRfidReaderTag()
    {
        $totalCount = 0;
        $tag = $this->input->post('tag');
        $whereData = [
            'rfid_or_id ' => $tag
        ];

        $response = $this->ScanManagementModel->getDataByWhereByOrderBy(
            'rfid_read_status',
            'assets',
            $whereData,
            'tid',
            'ASC'
        );

        if (empty($response)) {
            $color = 'danger';
            $message = "RFID Tag does not exist";
            $status = false;
        } else {
            if ($response[0]->rfid_read_status == 1) {
                $color = 'warning';
                $message = "$tag RFID Tag Already Read";
                $status = false;
            } else {
                $responseStatus = $this->UserManagmentModel->updateData(
                    'assets',
                    $whereData,
                    [
                        'rfid_read_status' =>  YES_READ_STATUS,
                        'rfid_read_by' => $this->getLoggedInUser()->user_id,
                        'rfid_read_dt' => getCurrentTime(),
                    ]
                );
                if ($responseStatus == 1) {
                    $color = 'success';
                    $message = "$tag RFID Tag read successfully";
                    $status = true;
                    $totalCount = $this->ScanManagementModel->getCount(
                        'assets',
                        [
                            'rfid_read_by' => $this->getLoggedInUser()->user_id,
                            'rfid_read_status' => YES_READ_STATUS
                        ]
                    );
                } else {
                    $color = 'danger';
                    $message = "Database Problem";
                    $status = false;
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode(['status' => $status, 'color' => $color, 'message' => $message, 'totalCount' => $totalCount]);
    }
    public function scanQrTags()
    {
        $this->data['title'] = 'Scan QR Tags';

        $this->data['totalCount'] = $this->ScanManagementModel->getCount('assets', [
            'qr_read_by' => $this->getLoggedInUser()->user_id,
            'qr_read_status' => YES_READ_STATUS
        ]);
        $this->load->view('web/includes/header', $this->data);
        $this->load->view('web/scanmanagement/scan_qr_tags');
    }
    public function saveQrReaderTag()
    {
        $totalCount = 0;
        $tag = $this->input->post('tag');
        $whereData = [
            'qr_and_bar_code_number' => $tag
        ];

        $response = $this->ScanManagementModel->getDataByWhereByOrderBy(
            'qr_read_status, generated_qr, qr_and_bar_code_number ',
            'assets',
            $whereData,
            'tid',
            'ASC'
        );
        
        if (empty($response)) {
            $color = 'danger';
            $message = "QR Tag does not exist";
            $status = false;
        } else {
            if ($response[0]->qr_read_status == 1) {
                $color = 'warning';
                $message = "$tag QR Tag Already Read";
                $status = false;
            } else if ($response[0]->generated_qr !== $response[0]->qr_and_bar_code_number) {
                $color = 'warning';
                $message = "$tag QR Tag does not matched with RFID Tag";
                $status = false;
            } else {
                $responseStatus = $this->UserManagmentModel->updateData(
                    'assets',
                    $whereData,
                    [
                        'qr_read_status' =>  YES_READ_STATUS,
                        'qr_read_by' => $this->getLoggedInUser()->user_id,
                        'qr_read_dt' => getCurrentTime(),
                    ]
                );
                if ($responseStatus == 1) {
                    $color = 'success';
                    $message = "$tag QR Tag read successfully";
                    $status = true;
                    $totalCount = $this->ScanManagementModel->getCount(
                        'assets',
                        [
                            'qr_read_by' => $this->getLoggedInUser()->user_id,
                            'qr_read_status' => YES_READ_STATUS
                        ]
                    );
                } else {
                    $color = 'danger';
                    $message = "Database Problem";
                    $status = false;
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode(['status' => $status, 'color' => $color, 'message' => $message, 'totalCount' => $totalCount]);
    }
}
