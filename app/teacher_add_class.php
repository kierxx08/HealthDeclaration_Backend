<?php

require "assets/remotesql.php";

if(isset($_POST["classname"]) && isset($_POST["section"]) && isset($_POST["subject"]) && isset($_POST["room"]) && isset($_POST["account_id"]) && isset($_POST["device_id"])){
    $account_id = $_POST["account_id"];
    $device_id = $_POST["device_id"];
    
    $classname = $_POST["classname"];
    $section = $_POST["section"];
    $subject = $_POST["subject"];
    $room = $_POST["room"];
    $date = addslashes(date("Y-m-d H:i:s"));
    
    $myObj = new \stdClass();
    $device_id_query = mysqli_query($conn,"SELECT * FROM `login_logs` WHERE account_id='$account_id' AND device_id='$device_id'");
    if(mysqli_num_rows($device_id_query)>1){
        $device_id_query = mysqli_query($conn,"SELECT * FROM `login_info` WHERE account_id='$account_id' AND type='teacher'");
        if(mysqli_num_rows($device_id_query)==1){

            if (!ctype_space($classname)&&$classname!=NULL) {
                $found = 1;
                while ($found == 1) {
                    $gen_cli_ID = gen_id(rand(5,7));
                    $classQuery = mysqli_query($conn,"SELECT * FROM `class_info` WHERE class_id='$gen_cli_ID'");
                    if (mysqli_num_rows($classQuery)==1) {
                        $found = 1;
                    }else{
                        $found = 0;
                    }
                }

                $sql_class = "INSERT INTO `class_info` (`class_id`, `account_id`, `class_name`, `section`, `subject`, `room`, `status`, `date`) 
                            VALUES ('$gen_cli_ID','$account_id','$classname','$section','$subject','$room','active','$date')";
                if(mysqli_query($conn,$sql_class)){
                    $myObj->error = false;

                }else{
                    $myObj->error = true;
                    $myObj->error_desc = "Error in inserting data on database";
                }

            }else{
                $myObj->error = true;
                $myObj->error_desc = "Classname is not valid";
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