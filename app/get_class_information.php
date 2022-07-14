<?php
require "assets/remotesql.php";

if(isset($_POST["get"]) && isset($_POST["class_id"]) && isset($_POST["account_id"]) && isset($_POST["device_id"])){
    if($_POST["get"]=="class_info"){
        $class_id = $_POST["class_id"];
        $account_id = $_POST["account_id"];
        $device_id = $_POST["device_id"];

        $myObj = new \stdClass();
        $account_device_query = mysqli_query($conn,"SELECT * FROM `login_logs` WHERE account_id='$account_id' AND device_id='$device_id'");
        if(mysqli_num_rows($account_device_query)>0){
            $account_query = mysqli_query($conn,"SELECT * FROM `login_info` WHERE account_id='$account_id'");
            $account_fetch=mysqli_fetch_array($account_query);
            
            if(mysqli_num_rows($account_query)==1){
                
                $class_st_query = mysqli_query($conn,"SELECT * FROM `class_student_con` WHERE class_id='$class_id' AND status='accepted'");
                $total_st = mysqli_num_rows($class_st_query);

                $class_query = mysqli_query($conn,"SELECT * FROM `class_info` WHERE class_id='$class_id'");
                $class_fetch=mysqli_fetch_array($class_query);
                
                $myObj->error = false;
                $myObj->class_code = $class_fetch['class_id'];
                $myObj->class_name = $class_fetch['class_name'];
                $myObj->total = $total_st;
                $myObj->class_created = date("F d, Y", strtotime($class_fetch['date']));
                
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
    
}else{
    echo "Unauthorized Request";
}

?>