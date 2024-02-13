<?php
define('TPPATH', APPPATH.'third_party/');                               // access to Third party folder
define('UPLOAD_EXCEL_PATH', APPPATH . '../upload/excel_file/');         // provide access to physical path/actual path
define('FETCH_EXCEL_PATH', APPPATH . '../upload/excel_file/');          // provide access to physical path/actual path
define('EXCEL_PATH', base_url('upload/excel_sample/sample.xls'));       // provide only http address 

define('ACTIVE_STATUS', 1);
define('INACTIVE_STATUS', 0);

define('ALREADY_EXIST', 1);
define('NOT_EXIST', 0);
define('YES_READ_STATUS', 1);
define('NOT_READ_STATUS', 0);

//Condition or Response 
define('SUCCESS', 1);
define('FAILED', 0);

//Response Status
define('ZERO_COUNT', 0);
define('NOT_ACTIVE_ANYMORE', 2);

//Http Erros
define('NOT_FOUND', 404);
define('UNAUTHORIZED', 401);
define('REQUEST_NOT_VALID', 400);
define('REQUEST_SUCCESS', 200);

//JWT 
define('JWT_KEY', 'assetManagementApiJwtKey');



