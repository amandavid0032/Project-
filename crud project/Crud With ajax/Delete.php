<?php
$user_Id=$_POST["id"];
$conn = mysqli_connect("localhost", "root", "", "record") or die("Connection failed:" . mysqli_connect_error());
$sql = "DELETE  FROM studentrecord WHERE id={$user_Id}";
if(mysqli_query($conn,$sql)){
    echo 1;
}else{
    echo 0;
}
?>