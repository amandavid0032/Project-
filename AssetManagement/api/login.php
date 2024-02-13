<?php
require './common-function.php';
function reactBackend(DB $db)
{
    $data = $db->getJsonInput();

    if (empty($data)) {
        $db->errorResponse(400, 'Please send all paramters');
    }

    if (!isset($data['user_id'])) {
        $db->errorResponse(400, 'Please provide user_id');
    }

    if (!isset($data['password'])) {
        $db->errorResponse(400, 'Please provide password');
    }

    $user_id = $data['user_id'];
    $password = md5($data['password']);

    // Select Query
    $selectQuery = "SELECT first_name,user_id,location_id FROM user_management where phone_number = '$user_id' and password = '$password'";
    $selectQueryMessage = "Invalid Email Id/ Password";
    $db->login($selectQuery, $selectQueryMessage);
}


reactBackend($db);
