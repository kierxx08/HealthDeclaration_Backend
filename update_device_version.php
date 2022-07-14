<?php
require "conn.php";
include "assets/gen_key.php";

if(isset($_POST["device_key"]) && isset($_POST["device_app_version"])){
    $phone_key=$_POST["device_key"];
    $phone_app_version=$_POST["device_app_version"];

    $sqlDevice = "SELECT * FROM `device_info` WHERE device_key='$phone_key'";
	$deviceQuery = mysqli_query($conn,$sqlDevice);

    if(mysqli_num_rows($deviceQuery)==1){
        mysqli_query($conn,"update device_info set device_app_version='$phone_app_version' where device_key='$phone_key'");
        echo "Success";
    }else if(mysqli_num_rows($deviceQuery)==0){
	    echo "Device Not Found\nContact Support";
    }else{
	    echo "Device Key Duplicate\nContact Support";
    }
    
}else{
	    echo "Error";
}
?>