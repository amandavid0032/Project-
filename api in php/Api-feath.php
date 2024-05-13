<?php
header('content-Type: application/json');
header('Access-control-Allow-origin: *');

include "database.php";
$sql="SELECT * FROM studentrecord ";
$result=mysqli_query($conn,$sql);
if(mysqli_num_rows($result)>0){
    $output=mysqli_fetch_all($result,MYSQLI_ASSOC);
    echo json_encode($output);
}else{
    echo json_encode(array('message' =>'No Record found','status'=>false));
}



?>