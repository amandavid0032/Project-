<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['sid'];
$f_name = $data['name'];
$l_name = $data['last'];
$age = $data['age'];
$emailId = $data['email'];
$phone = $data['phone'];
$gender = $data['gender'];

include "database.php"; 
$sql = "UPDATE studentrecord SET f_name=?, l_name=?, age=?, emailId=?, phone=?, gender=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssisssi", $f_name, $l_name, $age, $emailId, $phone, $gender, $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(array('message' => 'Student record updated', 'status' => true));
    } else {
        echo json_encode(array('message' => 'No record updated', 'status' => false));
    }
} else {
    echo json_encode(array('message' => 'Error: ' . $conn->error, 'status' => false));
}

$stmt->close();
$conn->close();
