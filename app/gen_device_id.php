<?php
require "assets/remotesql.php";
include "assets/gen_key.php";

if(isset($_POST["unique_id"]) && isset($_POST["device_brand"]) && isset($_POST["device_model"]) && isset($_POST["device_app_version"])){
    $unique_id = $_POST["unique_id"];
    $phone_brand=$_POST["device_brand"];
    $phone_model=$_POST["device_model"];
    $phone_app_version=$_POST["device_app_version"];
    $phone_name=$_POST["device_name"];
    //$date = time();
    $date = addslashes(date("Y-m-d H:i:s"));
    
    

    $sql_register = "INSERT INTO `device_info` (`device_id`, `unique_id`, `brand`, `model`, `name`, `app_version`, `last_update`, `detected_date`) VALUES ('$key','$unique_id','$phone_brand','$phone_model','$phone_name','$phone_app_version','$date','$date')";

    $myObj = new \stdClass();
    
    if($conn){
        if(mysqli_query($conn,$sql_register)){
            
            $myObj->error = false;
            $myObj->device_id = $key;
            
            $unique_id_query = mysqli_query($conn,"SELECT * FROM `device_info` WHERE unique_id='$unique_id'");
            $ui_fetch = mysqli_fetch_array($unique_id_query);
            
            if(mysqli_num_rows($unique_id_query)>1){
                $myObj->error_desc = "Avoid clearing data";
            }
            
        }else{
            $myObj->error = true;
            $myObj->error_desc = "Error in inserting data on database";
        }
    }else{
            $myObj->error = true;
            $myObj->error_desc = "Error in connection in the database";
    }
    
    $myJSON = json_encode($myObj);
    echo $myJSON;
    
}else{
	    echo "Unauthorized Request";
}
?>