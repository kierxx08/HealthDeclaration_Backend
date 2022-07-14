<?php
require "assets/remotesql.php";

if(isset($_POST["start_timestamp"]) && isset($_POST["end_timestamp"]) && isset($_POST["class_id"]) && isset($_POST["account_id"]) && isset($_POST["device_id"])){
    $start_timestamp = $_POST["start_timestamp"];
    $start_date = date('Y-m-d H:i:s', $start_timestamp);
    $end_timestamp = $_POST["end_timestamp"];
    $end_date = date('Y-m-d H:i:s', $end_timestamp);
    $class_id = $_POST["class_id"];
    $account_id = $_POST["account_id"];
    $device_id = $_POST["device_id"];
    $date = addslashes(date("Y-m-d H:i:s"));
    $cur_timestamp = time();
    
    $myObj = new \stdClass();

    $device_id_query = mysqli_query($conn,"SELECT * FROM `login_logs` WHERE account_id='$account_id' AND device_id='$device_id'");
    
    if(mysqli_num_rows($device_id_query)>1){

        $device_id_query = mysqli_query($conn,"SELECT * FROM `login_info` WHERE account_id='$account_id' AND type='teacher'");
        if(mysqli_num_rows($device_id_query)==1){
            
            if(($cur_timestamp<$end_timestamp)&&(($end_timestamp-$start_timestamp)>300)){


                $found = 1;
                while ($found == 1) {
                    $gen_attendance_ID = gen_id(16);
                    $attendanceQuery = mysqli_query($conn,"SELECT * FROM `attendance_info` WHERE attendance_id='$gen_attendance_ID'");
                    if (mysqli_num_rows($attendanceQuery)==1) {
                        $found = 1;
                    }else{
                        $found = 0;
                    }

                }


                $sql_attendance = "INSERT INTO `attendance_info`(`attendance_id`, `class_id`, `start`, `end`, `edited`, `date`) 
                VALUES ('$gen_attendance_ID','$class_id','$start_timestamp','$end_timestamp','false','$date')";
                if(mysqli_query($conn,$sql_attendance)){

                    $myObj->error = false;

                }else{
                    $myObj->error = true;
                    $myObj->error_desc = "Error in inserting data on database";
                }

            }else{
                $myObj->error = true;
                $myObj->error_desc = "Invalid Due Date and Time";
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

function gen_id($length_of_string) 

{ 

  

    // String of all alphanumeric character 

    $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 

  

    // Shufle the $str_result and returns substring 

    // of specified length 

    return substr(str_shuffle($str_result),0, $length_of_string); 

} 
?>