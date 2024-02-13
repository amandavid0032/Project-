<?php
defined('BASEPATH') or exit('No direct script access allowed');
class AssetManagementController extends MY_Controller
{
    private $tageLimitId = 1;
    public function __construct()
    {
        parent::__construct();
        $this->checkUserSessionExist();
    }
    public function index()
    {
        $this->uploadExcel();
    }
    public function uploadExcel()
    {
        $this->data['title'] = 'Upload Excel';
        $this->data['page_data'] = $this->CompanyManagementModel->getByTableName('company_management');
        $this->load->view('web/includes/header', $this->data);
        $this->load->view('web/assetmanagement/upload_excel');
    }
    public function saveUploadExcel()
    {
        if (!fileAllowed()) {
            redirect('upload_excel');
        }

        $files = array_diff(scandir(UPLOAD_EXCEL_PATH), array('.', '..'));

        if (count($files)) {
            foreach ($files as $fileName) {
                deletefile($fileName, UPLOAD_EXCEL_PATH);
            }
        }

        $response = uploadSingleFile($_FILES['upload_excel'], UPLOAD_EXCEL_PATH);

        if ($response === FALSE) {
            $this->redirectWithMessage('danger', 'File Not Uploaded', 'upload_excel');
        }

        // reading Excel File
        try {
            require(TPPATH . 'PHPEXCEL/excel_reader.php');
            $excel = new PhpExcelReader;
            if ($excel->read(FETCH_EXCEL_PATH . $response) === false) {
                throw new Exception("Please provide valid excel format example '.xls' format OR please check data types of your columns");
            }

            $skip = 0;
            foreach ($excel->sheets[0]['cells'] as $data) {
                if ($skip == 0) {
                    ++$skip;
                    continue;
                }
                $whereInArray[] = $data[1];     // RFID OR ID Number (first column of excel)
            }

            // check already exist data in table
            $alreadyExistRfidOrID  = $this->AssetManagementModel->getDataWithWhereIn(
                'rfid_or_id',
                'assets',
                $whereInArray
            );

            $checkRefIdOrIdExistiNDataBase = array_column($alreadyExistRfidOrID, 'rfid_or_id');

            $insertData = [];
            $count = 0;
            foreach ($excel->sheets[0]['cells'] as $data) {
                if ($count == 0) {      // skipping first value of the excel
                    ++$count; // setting this variable to stop skipping next value and incerment also
                    continue;
                }

                $rfid_or_id = strtolower($data[1]);
                $checkRefIdOrIdExistiNCurrectFile = array_column($insertData, 'rfid_or_id');

                $locationName = isset($data[17]) ? strtolower(trim($data[17])) : 0;
                $whereCondition = ['location_name' => $locationName];
                $locationId = $this->AssetManagementModel->getSingleRowWithWhere('pid', ' location_management', $whereCondition);
                if (!empty($locationId)) {
                    $array = [
                        'rfid_or_id' => isset($rfid_or_id) ? $rfid_or_id : 0,
                        'qr_and_bar_code_number' => isset($data[2]) ? strtolower($data[2]) : 0,               //QR and barcode number
                        'asset' => isset($data[3]) ?  $data[3] : 0,
                        'subnumber' => isset($data[4]) ?  $data[4] : 0,
                        'fa' => isset($data[5]) ?  $data[5] : 0,
                        'class' => isset($data[6]) ?  $data[6] : 0,
                        'asset_class' => isset($data[7]) ?  $data[7] : 0,
                        'asset_status_1' => isset($data[8]) ?  $data[8] : 0,
                        'profit_center' => isset($data[9]) ?  $data[9] : 0,
                        'as_per_sap' => isset($data[10]) ? $data[10] : 0,
                        'outlet_type' => isset($data[11]) ? $data[11] : 0,
                        'store_name' => isset($data[12]) ? $data[12] : 0,
                        'opening_date' => isset($data[13]) ? $data[13] : 0,
                        'asset_status_2' => isset($data[14]) ? $data[14] : 0,
                        'asset_block' => isset($data[15]) ? $data[15] : 0,
                        'asset_brand' => isset($data[16]) ? $data[16] : 0,
                        'location' => isset($data[17]) ? $data[17] : 0,
                        'location_id' => $locationId->pid,
                        'asset_description' => isset($data[18]) ? $data[18] : 0,
                        'asset_name' => isset($data[19]) ? $data[19] : 0,
                        'type_of_Assets' => isset($data[20]) ? $data[20] : 0,
                        'capitalized_on' => isset($data[21]) ? $data[21] : 0,
                        'life' => isset($data[22]) ? $data[22] : 0,
                        'balance_life' => isset($data[23]) ? $data[23] : 0,
                        'days' => isset($data[24]) ? $data[24] : 0,
                        'opening' => isset($data[25]) ? $data[25] : 0,
                        'cwip_capitalized' => isset($data[26]) ? $data[26] : 0,
                        'add_asset' => isset($data[27]) ? $data[27] : 0,
                        'transfer' => isset($data[28]) ? $data[28] : 0,
                        'del' => isset($data[29]) ? $data[29] : 0,
                        'w_off' => isset($data[30]) ? $data[30] : 0,
                        'net_block' => isset($data[31]) ? $data[31] : 0,
                        'dep_fy_start' => isset($data[32]) ? $data[32] : 0,
                        'dep_transfer' => isset($data[33]) ? $data[33] : 0,
                        'dep_for_the_year' => isset($data[34]) ? $data[34] : 0,
                        'dep_retir' => isset($data[35]) ? $data[35] : 0,
                        'accumul_dep' => isset($data[36]) ? $data[36] : 0,
                        'opening_impairment' => isset($data[37]) ? $data[37] : 0,
                        'impairment_transfer' => isset($data[38]) ? $data[38] : 0,
                        'impairment_charges' => isset($data[39]) ? $data[39] : 0,
                        'impairment_charges2' => isset($data[40]) ? $data[40] : 0,
                        'imapirment_reversal' => isset($data[41]) ? $data[41] : 0,
                        'accumul_impairment' => isset($data[42]) ? $data[42] : 0,
                        'curr_bk_val' => isset($data[43]) ? $data[43] : 0,
                        'wdv_after_impairment' => isset($data[44]) ? $data[44] : 0,
                        'opening_wdv' => isset($data[45]) ? $data[45] : 0,
                        'opening_wdv_after_impairment' => isset($data[46]) ? $data[46] : 0,
                        'quantity' => isset($data[47]) ? $data[47] : 0,
                        'deactivation_on' => isset($data[48]) ? $data[48] : 0,
                        'life_used' => isset($data[49]) ? $data[49] : 0,
                        'dep' => isset($data[50]) ? $data[50] : 0,
                        'historical_wdv' => isset($data[51]) ? $data[51] : 0,
                        'rfid_read_status' => NOT_READ_STATUS,
                        'qr_read_status' => NOT_READ_STATUS,
                        'status' => INACTIVE_STATUS,
                        'created_by' => $this->getLoggedInUser()->user_id,
                        'created_dt' => getCurrentTime(),
                        'moved_status' => 0
                    ];
                    if (
                        !in_array($rfid_or_id, $checkRefIdOrIdExistiNCurrectFile) &&
                        !in_array($rfid_or_id, $checkRefIdOrIdExistiNDataBase)
                    ) {
                        $array['data_exist'] = NOT_EXIST;
                    } else {
                        $array['data_exist'] = ALREADY_EXIST;
                    }
                    $insertData[] = $array;
                }
            }

            if (empty($insertData)) {
                throw new Exception("Please check your excel data before uploading. There is some mistake in your excel");
            }

            $response = $this->AssetManagementModel->insertBatch('assets', $insertData);

            if ($response == SUCCESS) {
                $color = 'success';
                $message = 'Excel Sheet Uploaded Successfully';
                $redirect = 'current_excel';
            } else {
                $color = 'danger';
                $message = 'Excel Sheet Uploaded Successfully';
                $redirect = 'upload_excel';
            }
            $this->redirectWithMessage($color, $message, $redirect);
        } catch (Exception $e) {
            $this->redirectWithMessage('danger', $e->getMessage(), 'upload_excel');
        }
    }

    public function currentExcel()
    {
        $this->data['title'] = 'Current Excel';
        $this->data['project_data'] = $this->AssetManagementModel->getCurrentCompnayProjectName();
        $this->data['total_count'] = $this->AssetManagementModel->getDataWithWhereIn(
            'COUNT(tid) as total_count',
            'assets',
            [1, 0]
        );
        $this->data['original_count'] = $this->AssetManagementModel->getCount('assets', ['data_exist' => 0]);
        $this->data['duplicate_count'] = $this->AssetManagementModel->getCount('assets', ['data_exist' => 1]);
        $this->data['page_data'] = $this->AssetManagementModel->getAllAsset();
        $this->load->view('web/includes/header', $this->data);
        $this->load->view('web/assetmanagement/current_excel');
        $this->load->view('web/includes/footer');
    }
    public function removeDuplicate()
    {
        $response = $this->AssetManagementModel->deleteData('assets', ['data_exist' => 1]);

        if ($response == SUCCESS) {
            $color = 'success';
            $message = 'Duplicate Data Deleted Successfully';
            $redirect = 'current_excel';
        } else {
            $color = 'danger';
            $message = 'Database Problem';
            $redirect = 'currentExcel';
        }
        $this->redirectWithMessage($color, $message, $redirect);
    }
    public function removeAll()
    {
        $response = $this->AssetManagementModel->truncateTable('assets');
        if ($response == SUCCESS) {
            $color = 'success';
            $message = 'All Data Deleted Successfully';
            $redirect = 'current_excel';
        } else {
            $color = 'danger';
            $message = 'Database Problem';
            $redirect = 'currentExcel';
        }
        $this->redirectWithMessage($color, $message, $redirect);
    }

    public function setLimit()
    {
        $this->data['title'] = 'Tag Limit';
        $this->data['page_data'] = $this->AssetManagementModel->getSingleRowWithWhere('total_limit', 'tag_limit', ['id' => $this->tageLimitId]);
        $this->load->view('web/includes/header', $this->data);
        $this->load->view('web/assetmanagement/set_limit');
        $this->load->view('web/includes/footer');
    }
    public function saveLimit()
    {
        if (!postAllowed()) {
            redirect('add_user');
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('tags_limit', 'Tag Limit', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->index();
            return;
        }

        $updateData = [
            'total_limit' => postDataFilterhtml($this->input->post('tags_limit')),
            'modified_by' => $this->getLoggedInUser()->user_id,
            'modified_at' => getCurrentTime(),
        ];
        $response = $this->UserManagmentModel->updateData('tag_limit', ['id' => $this->tageLimitId], $updateData);

        if ($response > FAILED) {
            $color = 'success';
            $message = "New Limit Added Successfully";
        } else {
            $color = 'danger';
            $message = "Database Problem";
        }
        $this->redirectWithMessage($color, $message, 'set_limit');
    }
    public function addAsset()
    {
        $this->data['title'] = 'Add Asset';
        $this->data['page_data'] = $this->AssetManagementModel->getDataByWhereByOrderBy('pid,location_name', 'location_management', ['status' => 1]);
        $this->load->view('web/includes/header', $this->data);
        $this->load->view('web/assetmanagement/add_asset');
        $this->load->view('web/includes/footer');
    }
    public function saveAsset()
    {
        if (!postAllowed()) {
            redirect('add_Asset');
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('qr_and_bar_code_number', 'qr and bar code number', 'required');
        $this->form_validation->set_rules('rfid_or_id', 'rfid or id', 'required');
        $this->form_validation->set_rules('asset', 'asset', 'required');
        $this->form_validation->set_rules('subnumber', 'sub number', 'required');
        $this->form_validation->set_rules('fa', 'fa', 'required');
        $this->form_validation->set_rules('class', 'class', 'required');
        $this->form_validation->set_rules('asset_class', 'asset class', 'required');
        $this->form_validation->set_rules('asset_status_1', 'asset status 1', 'required');
        $this->form_validation->set_rules('profit_center', 'profit center', 'required');
        $this->form_validation->set_rules('as_per_sap', 'as per sap', 'required');
        $this->form_validation->set_rules('outlet_type', 'outlet_type', 'required');
        $this->form_validation->set_rules('store_name', 'store name', 'required');
        $this->form_validation->set_rules('opening_date', 'opening date', 'required');
        $this->form_validation->set_rules('asset_status_2', 'asset status 2', 'required');
        $this->form_validation->set_rules('asset_block', 'asset block', 'required');
        $this->form_validation->set_rules('asset_brand', 'asset brand', 'required');
        $this->form_validation->set_rules('location_id', 'location name','required');
        $this->form_validation->set_rules('asset_description', 'asset_description', 'required');
        $this->form_validation->set_rules('asset_name', 'asset name', 'required');
        $this->form_validation->set_rules('type_of_Assets', 'type of Assets', 'required');
        $this->form_validation->set_rules('life', 'life', 'required');
        $this->form_validation->set_rules('balance_life', 'balance_life', 'required');
        $this->form_validation->set_rules('days', 'days', 'required');
        $this->form_validation->set_rules('opening', 'balance life', 'required');
        $this->form_validation->set_rules('cwip_capitalized', 'cwip capitalized', 'required');
        $this->form_validation->set_rules('add_asset', 'add asset', 'required');
        $this->form_validation->set_rules('transfer', 'transfer', 'required');
        $this->form_validation->set_rules('del', 'del', 'required');
        $this->form_validation->set_rules('w_off', 'w_off', 'required');
        $this->form_validation->set_rules('net_block', 'net block', 'required');
        $this->form_validation->set_rules('dep_fy_start', 'dep_fy_start', 'required');
        $this->form_validation->set_rules('dep_transfer', 'dep transfer', 'required');
        $this->form_validation->set_rules('dep_retir', 'dep retir', 'required');
        $this->form_validation->set_rules('accumul_dep', 'accumul dep', 'required');
        $this->form_validation->set_rules('opening_impairment', 'opening impairment', 'required');
        $this->form_validation->set_rules('impairment_transfer', 'impairment transfer', 'required');
        $this->form_validation->set_rules('impairment_charges', 'impairment charges', 'required');
        $this->form_validation->set_rules('impairment_charges2', 'impairment charges2', 'required');
        $this->form_validation->set_rules('imapirment_reversal', 'imapirment_reversal', 'required');
        $this->form_validation->set_rules('accumul_impairment', 'accumul impairment', 'required');
        $this->form_validation->set_rules('Curr_bk_val', 'Curr bk val', 'required');
        $this->form_validation->set_rules('wdv_after_impairment', 'wdv after impairment', 'required');
        $this->form_validation->set_rules('opening_wdv', 'opening wdv', 'required');
        $this->form_validation->set_rules('opening_wdv_after_impairment', 'opening wdv after impairment', 'required');
        $this->form_validation->set_rules('quantity', 'quantity', 'required');
        $this->form_validation->set_rules('life_used', 'life_used', 'required');
        $this->form_validation->set_rules('dep', 'dep', 'required');
        $this->form_validation->set_rules('historical_wdv', 'requried');
        if ($this->form_validation->run() == FALSE) {
            $this->addAsset();
            return;
        }
    $locationDetails = postDataFilterhtml($this->input->post('location_id'));
    $locationData = $this->AssetManagementModel->getSingleRowWithWhere('pid, location_name','location_management',['pid' => $locationDetails]);
        $qr_and_bar_code_number = postDataFilterhtml($this->input->post('qr_and_bar_code_number'));
        $rfid_or_id = postDataFilterhtml($this->input->post('rfid_or_id'));
        $asset = postDataFilterhtml($this->input->post('asset'));
        $subnumber = postDataFilterhtml($this->input->post('subnumber'));
        $fa = postDataFilterhtml($this->input->post('fa'));
        $class = postDataFilterhtml($this->input->post('class'));
        $asset_class = postDataFilterhtml($this->input->post('asset_class'));
        $asset_status_1 = postDataFilterhtml($this->input->post('asset_status_1'));
        $profit_center = postDataFilterhtml($this->input->post('profit_center'));
        $as_per_sap = postDataFilterhtml($this->input->post('as_per_sap'));
        $outlet_type = postDataFilterhtml($this->input->post('outlet_type'));
        $store_name = postDataFilterhtml($this->input->post('store_name'));
        $opening_date = postDataFilterhtml($this->input->post('opening_date'));
        $asset_status_2 = postDataFilterhtml($this->input->post('asset_status_2'));
        $asset_block = postDataFilterhtml($this->input->post('asset_block'));
        $asset_brand = postDataFilterhtml($this->input->post('asset_brand'));
        $location = $locationData->location_name;
        $location_id = $locationData->pid;
        $asset_description = postDataFilterhtml($this->input->post('asset_description'));
        $asset_name = postDataFilterhtml($this->input->post('asset_name'));
        $type_of_Assets = postDataFilterhtml($this->input->post('type_of_Assets'));
        $capitalized_on = postDataFilterhtml($this->input->post('capitalized_on'));
        $life = postDataFilterhtml($this->input->post('life'));
        $balance_life = postDataFilterhtml($this->input->post('balance_life'));
        $days = postDataFilterhtml($this->input->post('days'));
        $opening = postDataFilterhtml($this->input->post('opening'));
        $cwip_capitalized = postDataFilterhtml($this->input->post('cwip_capitalized'));
        $add_asset = postDataFilterhtml($this->input->post('add_asset'));
        $transfer = postDataFilterhtml($this->input->post('transfer'));
        $del = postDataFilterhtml($this->input->post('del'));
        $w_off = postDataFilterhtml($this->input->post('w_off'));
        $net_block = postDataFilterhtml($this->input->post('net_block'));
        $dep_fy_start = postDataFilterhtml($this->input->post('dep_fy_start'));
        $dep_transfer = postDataFilterhtml($this->input->post('dep_transfer'));
        $dep_for_the_year = postDataFilterhtml($this->input->post('dep_for_the_year'));
        $dep_retir = postDataFilterhtml($this->input->post('dep_retir'));
        $accumul_dep = postDataFilterhtml($this->input->post('accumul_dep'));
        $opening_impairment = postDataFilterhtml($this->input->post('opening_impairment'));
        $impairment_transfer = postDataFilterhtml($this->input->post('impairment_transfer'));
        $impairment_charges = postDataFilterhtml($this->input->post('impairment_charges'));
        $impairment_charges2 = postDataFilterhtml($this->input->post('impairment_charges2'));
        $imapirment_reversal = postDataFilterhtml($this->input->post('imapirment_reversal'));
        $accumul_impairment = postDataFilterhtml($this->input->post('accumul_impairment'));
        $Curr_bk_val = postDataFilterhtml($this->input->post('Curr_bk_val'));
        $wdv_after_impairment = postDataFilterhtml($this->input->post('wdv_after_impairment'));
        $opening_wdv = postDataFilterhtml($this->input->post('opening_wdv'));
        $opening_wdv_after_impairment = postDataFilterhtml($this->input->post('opening_wdv_after_impairment'));
        $quantity = postDataFilterhtml($this->input->post('quantity'));
        $deactivation_on = postDataFilterhtml($this->input->post('deactivation_on'));
        $life_used = postDataFilterhtml($this->input->post('life_used'));
        $dep = postDataFilterhtml($this->input->post('dep'));
        $historical_wdv = postDataFilterhtml($this->input->post('historical_wdv'));
        $locationId = postDataFilterhtml($this->input->post('location_id'));
        $locationData = $this->AssetManagementModel->getSingleRowWithWhere('pid, location_name','location_management',['pid' => $locationId]);
        $whereData = [
            'qr_and_bar_code_number' => strtolower($qr_and_bar_code_number)
        ];
        $response = $this->AssetManagementModel->getCount('assets', $whereData);
        if ($response == FAILED) {
            $insertData = [
                'qr_and_bar_code_number' => $qr_and_bar_code_number,
                'rfid_or_id' => $rfid_or_id,
                'asset' => $asset,
                'subnumber' => $subnumber,
                'fa' => $fa,
                'class' => $class,
                'asset_class' => $asset_class,
                'asset_status_1' => $asset_status_1,
                'profit_center' => $profit_center,
                'as_per_sap' => $as_per_sap,
                'outlet_type' => $outlet_type,
                'as_per_sap' => $as_per_sap,
                'store_name' => $store_name,
                'opening_date' => $opening_date,
                'asset_status_2' =>  $asset_status_2,
                'asset_block' => $asset_block,
                'asset_brand' => $asset_brand,
                'location' => $location,
                'location_id' => $location_id,
                'asset_description ' => $asset_description,
                'asset_name' => $asset_name,
                'type_of_Assets' => $type_of_Assets,
                'capitalized_on' => $capitalized_on,
                'life' => $life,
                'balance_life' => $balance_life,
                'days' => $days,
                'opening' => $opening,
                'cwip_capitalized' => $cwip_capitalized,
                'add_asset' => $add_asset,
                'transfer' => $transfer,
                'del' => $del,
                'w_off' => $w_off,
                'net_block' => $net_block,
                'dep_fy_start' => $dep_fy_start,
                'dep_transfer' => $dep_transfer,
                'dep_for_the_year' => $dep_for_the_year,
                'dep_retir' => $dep_retir,
                'accumul_dep' => $accumul_dep,
                'opening_impairment' => $opening_impairment,
                'impairment_transfer' => $impairment_transfer,
                'impairment_charges' => $impairment_charges,
                'impairment_charges2' => $impairment_charges2,
                'imapirment_reversal' => $imapirment_reversal,
                'accumul_impairment' => $accumul_impairment,
                'Curr_bk_val' => $Curr_bk_val,
                'wdv_after_impairment' => $wdv_after_impairment,
                'opening_wdv' => $opening_wdv,
                'opening_wdv_after_impairment' => $opening_wdv_after_impairment,
                'quantity' => $quantity,
                'deactivation_on' => $deactivation_on,
                'life_used' => $life_used,
                'dep' => $dep,
                'historical_wdv' => $historical_wdv,
                'rfid_read_status' => NOT_READ_STATUS,
                'qr_read_status' => NOT_READ_STATUS,
                'status' => INACTIVE_STATUS,
                'created_by' => $this->getLoggedInUser()->user_id,
                'created_dt' => getCurrentTime(),
            ];
            $response = $this->AssetManagementModel->insertData('assets', $insertData);
            if ($response > FAILED) {
                $color = 'success';
                $message = "$asset Asset created Successfully";
            } else {
                $color = 'danger';
                $message = "Database Problem";
            }
        } else {
            $color = 'warning';
            $message = "$asset Asset already Created";
        }
        $this->redirectWithMessage($color, $message, 'add_asset');
    }

    public function viewAssetProfile($id)
    {
        $id = (int)base64_decode($id);
        $response = $this->AssetManagementModel->getCount('assets', ['tid' => $id]);

        if ($response != SUCCESS) {
            $color = 'danger';
            $message = "asset does not exist";
            $this->redirectWithMessage($color, $message, 'view_asset');
        }

        $this->data['title'] = 'View asset';
        $this->data['page_data'] = $this->AssetManagementModel->getAssetProfileWithWhere($id);
        $this->load->view('web/includes/header', $this->data);
        $this->load->view('web/assetmanagement/view_asset_profile');
        $this->load->view('web/includes/footer');
    }
    public function deleteAssetProfile($id)
    {
        $id = (int)base64_decode($id);
        $response = $this->AssetManagementModel->deleteData('assets', ['tid' => $id]);
        if ($response > FAILED) {
            $color = 'success';
            $message = "Asset Delete Successfully";
        } else {
            $color = 'danger';
            $message = "Database Problem";
        }
        $this->redirectWithMessage($color, $message, 'current_excel');
    }
    public function editAssetProfile($id)
    {
        $id = (int)base64_decode($id);
        $response = $this->AssetManagementModel->getCount('assets', ['tid' => $id]);

        if ($response != SUCCESS) {
            $color = 'danger';
            $message = "asset does not exist";
            $this->redirectWithMessage($color, $message, 'view_asset');
        }

        $this->data['title'] = 'Update asset';
        $this->data['location_data'] = $this->LocationManagmentModel->getDataByWhereByOrderBy('*', 'location_management',  ['status' => 1], 'location_name', 'ASC');
        $this->data['page_data'] = $this->AssetManagementModel->getAssetProfileWithWhere($id);
        $this->load->view('web/includes/header', $this->data);
        $this->load->view('web/assetmanagement/edit_asset_profile');
        $this->load->view('web/includes/footer');
    }
    public function updateAssetProfile()
    {
        if (!postAllowed()) {
            redirect('view_asset');
        }
        $id = $this->input->post('asset_id');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('qr_and_bar_code_number', 'qr and bar code number', 'required');
        $this->form_validation->set_rules('rfid_or_id', 'rfid or id', 'required');
        $this->form_validation->set_rules('asset', 'asset', 'required');
        $this->form_validation->set_rules('subnumber', 'sub number', 'required');
        $this->form_validation->set_rules('fa', 'fa', 'required');
        $this->form_validation->set_rules('class', 'class', 'required');
        $this->form_validation->set_rules('asset_class', 'asset class', 'required');
        $this->form_validation->set_rules('asset_status_1', 'asset status 1', 'required');
        $this->form_validation->set_rules('profit_center', 'profit center', 'required');
        $this->form_validation->set_rules('as_per_sap', 'as per sap', 'required');
        $this->form_validation->set_rules('outlet_type', 'outlet_type', 'required');
        $this->form_validation->set_rules('store_name', 'store name', 'required');
        $this->form_validation->set_rules('opening_date', 'opening date', 'required');
        $this->form_validation->set_rules('asset_status_2', 'asset status 2', 'required');
        $this->form_validation->set_rules('asset_block', 'asset block', 'required');
        $this->form_validation->set_rules('asset_brand', 'asset brand', 'required');
        $this->form_validation->set_rules('location_id', 'location name', 'required');
        $this->form_validation->set_rules('asset_description', 'asset_description', 'required');
        $this->form_validation->set_rules('asset_name', 'asset name', 'required');
        $this->form_validation->set_rules('type_of_Assets', 'type of Assets', 'required');
        $this->form_validation->set_rules('capitalized_on', 'capitalized on', 'required');
        $this->form_validation->set_rules('life', 'life', 'required');
        $this->form_validation->set_rules('balance_life', 'balance_life', 'required');
        $this->form_validation->set_rules('days', 'days', 'required');
        $this->form_validation->set_rules('opening', 'balance life', 'required');
        $this->form_validation->set_rules('cwip_capitalized', 'cwip capitalized', 'required');
        $this->form_validation->set_rules('add_asset', 'add asset', 'required');
        $this->form_validation->set_rules('transfer', 'transfer', 'required');
        $this->form_validation->set_rules('del', 'del', 'required');
        $this->form_validation->set_rules('w_off', 'w_off', 'required');
        $this->form_validation->set_rules('net_block', 'net block', 'required');
        $this->form_validation->set_rules('dep_fy_start', 'dep_fy_start', 'required');
        $this->form_validation->set_rules('dep_transfer', 'dep transfer', 'required');
        $this->form_validation->set_rules('dep_for_the_year', 'dep for the year', 'required');
        $this->form_validation->set_rules('dep_retir', 'dep retir', 'required');
        $this->form_validation->set_rules('accumul_dep', 'accumul dep', 'required');
        $this->form_validation->set_rules('opening_impairment', 'opening impairment', 'required');
        $this->form_validation->set_rules('impairment_transfer', 'impairment transfer', 'required');
        $this->form_validation->set_rules('impairment_charges', 'impairment charges', 'required');
        $this->form_validation->set_rules('impairment_charges2', 'impairment charges2', 'required');
        $this->form_validation->set_rules('imapirment_reversal', 'imapirment_reversal', 'required');
        $this->form_validation->set_rules('accumul_impairment', 'accumul impairment', 'required');
        $this->form_validation->set_rules('Curr_bk_val', 'Curr bk val', 'required');
        $this->form_validation->set_rules('wdv_after_impairment', 'wdv after impairment', 'required');
        $this->form_validation->set_rules('opening_wdv', 'opening wdv', 'required');
        $this->form_validation->set_rules('opening_wdv_after_impairment', 'opening wdv after impairment', 'required');
        $this->form_validation->set_rules('quantity', 'quantity', 'required');
        $this->form_validation->set_rules('deactivation_on', 'deactivation on', 'required');
        $this->form_validation->set_rules('life_used', 'life_used', 'required');
        $this->form_validation->set_rules('dep', 'dep', 'required');
        $this->form_validation->set_rules('historical_wdv', 'historical_wdv', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->index();
            $data['errors'] = validation_errors();
            return redirect("edit_asset_profile/" . base64_encode($id));
        }
        $locationId = postDataFilterhtml($this->input->post('location_id'));
        $locationData = $this->AssetManagementModel->getSingleRowWithWhere('pid, location_name','location_management',['pid' => $locationId]);
        $updateData = [
            'qr_and_bar_code_number' => postDataFilterhtml($this->input->post('qr_and_bar_code_number')),
            'rfid_or_id' => postDataFilterhtml($this->input->post('rfid_or_id')),
            'asset' => postDataFilterhtml($this->input->post('asset')),
            'subnumber' => postDataFilterhtml($this->input->post('subnumber')),
            'fa' => postDataFilterhtml($this->input->post('fa')),
            'class' => postDataFilterhtml($this->input->post('class')),
            'asset_class' => postDataFilterhtml($this->input->post('asset_class')),
            'asset_status_1' => postDataFilterhtml($this->input->post('asset_status_1')),
            'profit_center' => postDataFilterhtml($this->input->post('profit_center')),
            'as_per_sap' => postDataFilterhtml($this->input->post('as_per_sap')),
            'outlet_type' =>  postDataFilterhtml($this->input->post('outlet_type')),
            'store_name' => postDataFilterhtml($this->input->post('store_name')),
            'opening_date' => postDataFilterhtml($this->input->post('opening_date')),
            'asset_status_2' => postDataFilterhtml($this->input->post('asset_status_2')),
            'asset_block' => postDataFilterhtml($this->input->post('asset_block')),
            'asset_brand' => postDataFilterhtml($this->input->post('asset_brand')),
            'location' => $locationData->location_name,
            'location_id' => $locationData->pid,
            'asset_description' => postDataFilterhtml($this->input->post('asset_description')),
            'asset_name' => postDataFilterhtml($this->input->post('asset_name')),
            'type_of_Assets' => postDataFilterhtml($this->input->post('type_of_Assets')),
            'capitalized_on' => postDataFilterhtml($this->input->post('capitalized_on')),
            'life' => postDataFilterhtml($this->input->post('life')),
            'balance_life' => postDataFilterhtml($this->input->post('balance_life')),
            'days' => postDataFilterhtml($this->input->post('days')),
            'opening' => postDataFilterhtml($this->input->post('opening')),
            'cwip_capitalized' => postDataFilterhtml($this->input->post('cwip_capitalized')),
            'add_asset' => postDataFilterhtml($this->input->post('add_asset')),
            'transfer' => postDataFilterhtml($this->input->post('transfer')),
            'del' => postDataFilterhtml($this->input->post('del')),
            'w_off' => postDataFilterhtml($this->input->post('w_off')),
            'net_block' => postDataFilterhtml($this->input->post('net_block')),
            'dep_fy_start' => postDataFilterhtml($this->input->post('dep_fy_start')),
            'dep_transfer' => postDataFilterhtml($this->input->post('dep_transfer')),
            'dep_for_the_year' => postDataFilterhtml($this->input->post('dep_for_the_year')),
            'dep_retir' => postDataFilterhtml($this->input->post('dep_retir')),
            'accumul_dep' => postDataFilterhtml($this->input->post('accumul_dep')),
            'opening_impairment' => postDataFilterhtml($this->input->post('opening_impairment')),
            'impairment_transfer' => postDataFilterhtml($this->input->post('impairment_transfer')),
            'impairment_charges' => postDataFilterhtml($this->input->post('impairment_charges')),
            'impairment_charges2' => postDataFilterhtml($this->input->post('impairment_charges2')),
            'imapirment_reversal' => postDataFilterhtml($this->input->post('imapirment_reversal')),
            'accumul_impairment' => postDataFilterhtml($this->input->post('accumul_impairment')),
            'Curr_bk_val' => postDataFilterhtml($this->input->post('Curr_bk_val')),
            'wdv_after_impairment' => postDataFilterhtml($this->input->post('wdv_after_impairment')),
            'opening_wdv' => postDataFilterhtml($this->input->post('opening_wdv')),
            'opening_wdv_after_impairment' => postDataFilterhtml($this->input->post('opening_wdv_after_impairment')),
            'quantity' => postDataFilterhtml($this->input->post('quantity')),
            'deactivation_on' => postDataFilterhtml($this->input->post('deactivation_on')),
            'life_used' => postDataFilterhtml($this->input->post('life_used')),
            'dep' => postDataFilterhtml($this->input->post('dep')),
            'historical_wdv' => postDataFilterhtml($this->input->post('historical_wdv')),
            'rfid_read_status' => NOT_READ_STATUS,
            'qr_read_status' => NOT_READ_STATUS,
            'status' => INACTIVE_STATUS,
            'created_by' => $this->getLoggedInUser()->user_id,
            'created_dt' => getCurrentTime(),
        ];
        $response = $this->UserManagmentModel->updateData('assets', ['tid' => $id], $updateData);
        if ($response > FAILED) {
            $color = 'success';
            $message = "Asset updated Successfully";
        } else {
            $color = 'danger';
            $message = "Database Problem";
        }
        $this->redirectWithMessage($color, $message, 'current_excel');
    }
}
