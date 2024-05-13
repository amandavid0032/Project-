<?php
header('content-Type: application/json');
header('Acess-control-Allow-origin: *');
header('Access-control-Allow-Methods:POST');
header('Access-control-Allow-Header:Acess-control-Allow-Header,content-Type,
Access-control-Allow-Method,Authorization,X-Requested-with');
$data = json_decode(file_get_contents("php://input"), true);
$f_name = $data['name'];
$l_name    = $data['last'];
$age = $data['age'];
$emailId    = $data['email'];
$phone = $data['phone'];
$gender = $data['gender'];
include "database.php";
$sql = "INSERT INTO studentrecord (f_name,l_name,age,emailId,phone,gender) VALUES('{$f_name}',{'$l_name'},{'$age'},{'$emailId'},{'$phone'},{'$gender'})";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    echo json_encode(array('message' => 'Student record add ', 'status' => True));

} else {
    echo json_encode(array('message' => 'No Record found', 'status' => false));
}
