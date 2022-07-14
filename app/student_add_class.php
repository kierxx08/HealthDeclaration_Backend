<?php

require "assets/remotesql.php";

if(isset($_POST["classcode"]) && isset($_POST["account_id"]) && isset($_POST["device_id"])){
    $account_id = $_POST["account_id"];
    $device_id = $_POST["device_id"];
    
    $classcode = $_POST["classcode"];
    $date = addslashes(date("Y-m-d H:i:s"));
    
    $myObj = new \stdClass();
    $device_id_query = mysqli_query($conn,"SELECT * FROM `login_logs` WHERE account_id='$account_id' AND device_id='$device_id'");
    if(mysqli_num_rows($device_id_query)>0){
        $device_id_query = mysqli_query($conn,"SELECT * FROM `login_info` WHERE account_id='$account_id' AND type='student'");
        if(mysqli_num_rows($device_id_query)==1){

            if (!ctype_space($classcode)&&$classcode!=NULL) {
                $cscon_query = mysqli_query($conn,"SELECT * FROM `class_student_con` WHERE BINARY class_id='$classcode' AND account_id='$account_id' AND status='requesting'");
                if(mysqli_num_rows($cscon_query)<1){
                $cscon_query = mysqli_query($conn,"SELECT * FROM `class_student_con` WHERE BINARY class_id='$classcode' AND account_id='$account_id' AND status='accepted'");
                    if(mysqli_num_rows($cscon_query)<1){
                        $class_query = mysqli_query($conn,"SELECT * FROM `class_info` WHERE BINARY class_id='$classcode'");
                        $class_fetch = mysqli_fetch_array($class_query);
                        if(mysqli_num_rows($class_query)==1){
                            if($class_fetch['status']=="active"){
                            
    
                                $found = 1;
                                while ($found == 1) {
                                    $gen_ID = str_pad(rand(0, 999999999), 9, 0, STR_PAD_LEFT);
                                    $classQuery = mysqli_query($conn,"SELECT * FROM `class_student_con` WHERE CScon_id='$gen_ID'");
                                    if (mysqli_num_rows($classQuery)==1) {
                                        $found = 1;
                                    }else{
                                        $found = 0;
                                    }
                                }
    
                                $sql_class = "INSERT INTO `class_student_con` (`CScon_id`, `account_id`, `class_id`, `status`, `date`) 
                                            VALUES ('$gen_ID','$account_id','$classcode','requesting','$date')";
                                if(mysqli_query($conn,$sql_class)){
                                    $myObj->error = false;
    
                                }else{
                                    $myObj->error = true;
                                    $myObj->error_desc = "Error in inserting data on database";
                                }
                            }else {
                                $myObj->error = true;
                                $myObj->error_desc = "Class status is ".$class_fetch['status'];
                            }
                        }else {
                            $myObj->error = true;
                            $myObj->error_desc = "Class Code not Found";
                        }
                        
                    }else {
                        $myObj->error = true;
                        $myObj->error_desc = "You already joined in this Class";
                    }
                }else {
                    $myObj->error = true;
                    $myObj->error_desc = "You already send a Join Request";
                }
            }else{
                $myObj->error = true;
                $myObj->error_desc = "Class Code is not valid";
            }
        }else{
            $myObj->error = true;
            $myObj->error_desc = "Account is not valid";
        }

    }else{
        $myObj->error = true;
        $myObj->error_desc = "Account and Device not matched";
    }
        
    $myJSON = json_encode($myObj);
    echo $myJSON;
    
}else{
	    echo "Unauthorized Request";
}


?>