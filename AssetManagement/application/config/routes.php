<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'LoginController';
$route['404_override'] = 'ErrorPage';
$route['translate_uri_dashes'] = FALSE;


// Login Controller
$route['login']['get'] = 'LoginController/index';
$route['check']['post'] = 'LoginController/check';

//DashboardController
$route['dashboard']['get'] = 'DashboardController/index';

//ProfileController
$route['my_profile']['get'] = 'ProfileController/index';
$route['update_profile']['get'] = 'ProfileController/updateProfile';
$route['saveprofile']['post'] = 'ProfileController/saveProfile';
$route['change_password']['get'] = 'ProfileController/changePassword';
$route['save_password']['post'] = 'ProfileController/savePassword';
$route['logout']['get'] = 'ProfileController/logout';


//UserManagementController
$route['add_user']['get'] = 'UserManagmentController/index';
$route['save_user']['post'] = 'UserManagmentController/saveUser';
$route['view_users']['get'] = 'UserManagmentController/viewUsers';
$route['view_user_profile/(:any)']['get'] = 'UserManagmentController/viewUserProfile/$1';
$route['reset_password/(:any)']['get'] = 'UserManagmentController/resetPassword/$1';
$route['change_status/(:any)/(:any)']['get'] = 'UserManagmentController/changeStatus/$1/$2';
$route['edit_user_profile/(:any)']['get'] = 'UserManagmentController/editUserProfile/$1';
$route['save_update_user']['post'] = 'UserManagmentController/saveUpdateUser';
$route['login_activities/(:any)']['get'] = 'UserManagmentController/loginActivities/$1';

// Company Management
$route['add_company']['get'] = 'CompanyManagementController/index';
$route['save_company']['post'] = 'CompanyManagementController/saveCompany';
$route['view_companies']['get'] = 'CompanyManagementController/viewCompanies';
$route['change_status_company/(:any)/(:any)']['get'] = 'CompanyManagementController/changeStatus/$1/$2';
$route['view_company_profile/(:any)']['get'] = 'CompanyManagementController/viewCompanyProfile/$1';
$route['edit_company_profile/(:any)']['get'] = 'CompanyManagementController/editCompanyProfile/$1';
$route['save_update_company']['post'] = 'CompanyManagementController/saveUpdateCompany';


// Project Management
$route['add_location']['get'] = 'LocationManagmentController/index';
$route['save_location']['post'] = 'LocationManagmentController/saveLocation';
$route['view_locations']['get'] = 'LocationManagmentController/viewLocations';
$route['change_status_location/(:any)/(:any)']['get'] = 'LocationManagmentController/changeStatus/$1/$2';
$route['view_location_profile/(:any)']['get'] = 'LocationManagmentController/viewLocationProfile/$1';
$route['edit_location_profile/(:any)']['get'] = 'LocationManagmentController/editLocationProfile/$1';
$route['save_update_location']['post'] = 'LocationManagmentController/saveUpdateLocation';

//Device Management
$route['add_device']['get'] = 'DeviceManagementController/index';
$route['save_device']['post'] = 'DeviceManagementController/saveDevice';
$route['view_devices']['get'] = 'DeviceManagementController/viewDevices';
$route['view_device_profile/(:any)']['get'] = 'DeviceManagementController/viewDeviceProfile/$1';
$route['change_status_device/(:any)/(:any)']['get'] = 'DeviceManagementController/changeStatus/$1/$2';
$route['edit_device_profile/(:any)']['get'] = 'DeviceManagementController/editDeviceProfile/$1';
$route['save_update_device']['post'] = 'DeviceManagementController/saveUpdateDevice';

// Asset Management
$route['add_asset']['get']='AssetManagementController/addAsset';
$route['save_asset']['post']='AssetManagementController/saveAsset';
$route['view_asset_profile/(:any)']['get']='AssetManagementController/viewAssetProfile/$1';
$route['edit_asset_profile/(:any)']['get']='AssetManagementController/editAssetProfile/$1';
$route['update_asset_profile']['post']='AssetManagementController/updateAssetProfile';
$route['delete_asset_profile/(:any)']['get']='AssetManagementController/deleteAssetProfile/$1';
$route['upload_excel']['get'] = 'AssetManagementController/index';
$route['save_upload_excel']['post'] = 'AssetManagementController/saveUploadExcel';
$route['current_excel']['get'] = 'AssetManagementController/currentExcel';
$route['remove_duplicate']['get'] = 'AssetManagementController/removeDuplicate';
$route['remove_all']['get'] = 'AssetManagementController/removeAll';
$route['set_limit']['get'] = 'AssetManagementController/setLimit';
$route['save_limit']['post'] = 'AssetManagementController/saveLimit';

// Scan Management
$route['read_rfid_tags']['get'] = 'ScanManagementController/readRfidTags';
$route['save_rfid_reader_tag']['post'] = 'ScanManagementController/saveRfidReaderTag';
$route['scan_qr_tags']['get'] = 'ScanManagementController/scanQrTags';
$route['save_qr_reader_tag']['post'] = 'ScanManagementController/saveQrReaderTag';

// Api Controller
$route['app_login']['post'] = 'ApiController/login';
$route['get_user_data']['get'] = 'ApiController/getUserData';

// audit Management
$route['add_audit']['get'] = 'AuditManagementController/index';
$route['save_audit']['post'] = 'AuditManagementController/saveAudit';
$route['view_audit']['get'] = 'AuditManagementController/viewAudit';
$route['view_audit_profile/(:any)']['get'] = 'AuditManagementController/viewAuditProfile/$1';
$route['edit_audit_profile/(:any)']['get'] = 'AuditManagementController/editAuditProfile/$1';
$route['save_update_audit']['post'] = 'AuditManagementController/saveUpdateAudit';
$route['change_status_audit/(:any)/(:any)']['get'] = 'AuditManagementController/changeStatus/$1/$2';

// Reports
$route['scanned_tags']['get'] = 'ReportsController/scannedTags';
$route['unscanned_tags']['get'] = 'ReportsController/unscannedTags';
$route['scanned_tags']['post'] = 'ReportsController/scannedTags';
$route['unscanned_tags']['post'] = 'ReportsController/unscannedTags';
$route['log_file']['get'] = 'ReportsController/logFile';
$route['moved_tags']['get'] = 'ReportsController/movedTags';
$route['remove_logs']['get'] = 'ReportsController/removeLogs';
