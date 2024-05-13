<?php
header('content-Type: application/json');
header('Access-control-Allow-origin: *');
$data=json_decode(file_get_contents("php://input"),true);
$student_data=$data['sid'];
include "database.php";
$sql = "SELECT * FROM studentrecord  WHERE id={$student_data}";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    $output = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($output);
} else {
    echo json_encode(array('message' => 'No Record found', 'status' => false));
}
