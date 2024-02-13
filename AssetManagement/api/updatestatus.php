<?php
require './common-function.php';
$method = $_SERVER['REQUEST_METHOD'];

if ($method !== "POST") {
    $db->errorResponse(400, "Method should be post");
}

function reactBackend(DB $db)
{
    $data = $db->getJsonInput();
    if (empty($data)) {
        $db->errorResponse(400, 'Please send all paramters');
    }

    if (empty($data['user_id'])) {
        $db->errorResponse(400, 'user id paramter is empty');
    }

    if (empty($data['data'])) {
        $db->errorResponse(400, 'data paramter is empty');
    }
    $insertQuery = '';
    $responseStatus = [];
    $userId = $data['user_id'];
    foreach ($data['data'] as $value) {
        $status = 0;

        $tagsExist = $db->select("SELECT location_id FROM assets WHERE rfid_or_id ='{$value['rfid_or_id']}'");
        if ($tagsExist) {

            $movedStatus = 0;
            $locationId = isset($tagsExist[0]['location_id']) ? $tagsExist[0]['location_id'] : null;

            // if location get changed of asset
            if ($locationId != $value['location_id']) {
                $movedStatus = 1;
            }

            $updateStr = "UPDATE assets set status = 1, moved_status = $movedStatus where `location_id` = $locationId and rfid_or_id ='{$value['rfid_or_id']}'";
            $updateCheck = $db->update($updateStr);
            if ($updateCheck) {
                $status = 1;
                $value['message'] = 'updated successfully';
            } else {
                $value['message'] = 'not updated';
            }
        } else {
            $value['message'] = 'not found';
        }

        $insertQuery  .= "INSERT INTO  log_table VALUEs(null,$userId,{$value['location_id']},'{$value['rfid_or_id']}', $status);";

        $value['status'] = $status;
        array_push($responseStatus, $value);
    }

    $db->insertBatchQuery($insertQuery);

    $db->successResponse(200, $responseStatus);
}


reactBackend($db);

// example
// {
//     "user_id": 1,
//     "data": [
//         {
//             "location_id": 1,
//             "rfid_or_id": "EF0111111111111111111111"
//         },
//         {
//             "location_id": 1,
//             "rfid_or_id": "EF0112222222222222222222"
//         },
//         {
//             "location_id": 1,
//             "rfid_or_id": "EF0111222222222222222222"
//         },
//         {
//             "location_id": 1,
//             "rfid_or_id": "EF0111122222222222222222"
//         },
//         {
//             "location_id": 1,
//             "rfid_or_id": "EF0144444444444444444444"
//         }
//     ]
// } 
