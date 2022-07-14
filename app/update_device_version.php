<?php
require "assets/remotesql.php";
include "assets/gen_key.php";

if(isset($_POST["device_id"]) && isset($_POST["device_app_version"])){
    $phone_key=$_POST["device_id"];
    $phone_app_version=$_POST["device_app_version"];
    //$date = time();
    $date = addslashes(date("Y-m-d H:i:s"));

    $sqlDevice = "SELECT * FROM `device_info` WHERE device_id='$phone_key'";
	$deviceQuery = mysqli_query($conn,$sqlDevice);

    $myObj = new \stdClass();
    
    if(mysqli_num_rows($deviceQuery)==1){
        
        $update_sql = "update device_info set app_version='$phone_app_version', last_update='$date' where device_id='$phone_key'";
        if(mysqli_query($conn,$update_sql)){
            
            $myObj->error = false;
            
        }else{
            $myObj->error = true;
            $myObj->error_desc = "Error in updating data on database";
        }
        
    }else if(mysqli_num_rows($deviceQuery)==0){
        $myObj->error = true;
        $myObj->error_desc = "Device Not Found";
    }else{
        $myObj->error = true;
        $myObj->error_desc = "Duplicate device detected.";
    }
    
    $myJSON = json_encode($myObj);
    echo $myJSON;
    
}else{
	    echo "Unauthorized Request";
}
?>