<?php
require "assets/remotesql.php";

if(isset($_POST["decision"]) && isset($_POST["CScon_id"]) && isset($_POST["account_id"]) && isset($_POST["device_id"])){
    $decision = $_POST["decision"];
    $CScon_id = $_POST["CScon_id"];
    $account_id = $_POST["account_id"];
    $device_id = $_POST["device_id"];

    $account_device_query = mysqli_query($conn,"SELECT * FROM `login_logs` WHERE account_id='$account_id' AND device_id='$device_id'");
    if(mysqli_num_rows($account_device_query)>0){
        $account_query = mysqli_query($conn,"SELECT * FROM `login_info` WHERE account_id='$account_id'");
        $account_fetch=mysqli_fetch_array($account_query);

        $myObj = new \stdClass();
        if(mysqli_num_rows($account_query)==1){

            $update_sql = "update class_student_con set status='$decision' where CScon_id='$CScon_id'";
            if(mysqli_query($conn,$update_sql)){
                $myObj->error = false;
               
            }else{
                $myObj->error = true;
                $myObj->error_desc = "Error in updating data on database";
            }
            
        }else{
            $myObj->error = true;
            $myObj->error_desc = "Account is not valid";
        }
    }else{
        $myObj->error = true;
        $myObj->error_desc = "Account and Device not matched";
    }
    
    echo json_encode($myObj);
    
}else{
    echo "Unauthorized Request";
}

?>