<?php
require "assets/remotesql.php";

if(isset($_POST["type"]) && isset($_POST["srcode"]) && isset($_POST["fname"]) && isset($_POST["lname"]) && 
    isset($_POST["email"]) && isset($_POST["phone"]) && isset($_POST["sex"]) && isset($_POST["password"]) &&
    isset($_POST["device_id"])){
        
        $device_id = $_POST["device_id"];
        $type = $_POST["type"];
        $srcode = ucfirst($_POST["srcode"]);
        $fname = ucfirst($_POST["fname"]);
        $lname = ucfirst($_POST["lname"]);
        $email = $_POST["email"];
        $phone = $_POST["phone"];
        $sex = $_POST["sex"];
        $password = $_POST["password"];
        $error = 0;
        //$date = time();
        $date = addslashes(date("Y-m-d H:i:s"));
        
        $myObj = new \stdClass();

        $device_id_query = mysqli_query($conn,"SELECT * FROM `device_info` WHERE device_id='$device_id'");
        if(mysqli_num_rows($device_id_query)==1){
            if($type == "student"){
                $table_name = "student_info";
                $srcode_query = mysqli_query($conn,"SELECT * FROM `student_info` WHERE srcode='$srcode'");
                if(mysqli_num_rows($srcode_query)>0){
                    $error += 1;
                    $myObj->srcode = false;
                }
            }else{
                $table_name = "teacher_info";
            }

            $email_query = mysqli_query($conn,"SELECT * FROM `$table_name` WHERE email='$email'");
            if(mysqli_num_rows($email_query)>0){
                $error += 1;
                $myObj->email = false;
            }

            $phone_query = mysqli_query($conn,"SELECT * FROM `$table_name` WHERE phone='$phone'");
            if(mysqli_num_rows($phone_query)>0){
                $error += 1;
                $myObj->phone = false;
            }

            if($error==0){

                //generate account id
                $found = 1;
                while ($found == 1) {
                    $gen_acc_ID = date("y");
                    $gen_acc_ID .= date("m");
                    $gen_acc_ID .= date("d");
                    $gen_acc_ID .= str_pad(rand(0, 99999), 5, 0, STR_PAD_LEFT);
                    $usernameQuery = mysqli_query($conn,"SELECT * FROM `login_info` WHERE account_id='$gen_acc_ID'");
                    if (mysqli_num_rows($usernameQuery)==1) {
                        $found = 1;
                    }else{
                        $found = 0;
                    }
                }

                if($type == "student"){
                    $username = $srcode;
                    $sql_register = "INSERT INTO `student_info` (`account_id`, `srcode`, `fname`, `lname`, `email`, `phone`, `sex`) 
                        VALUES ('$gen_acc_ID','$srcode','$fname','$lname','$email','$phone','$sex')";
                    $sql_login = "INSERT INTO `login_info` (`account_id`, `username`, `type`, `password`, `status`, `date_register`) 
                        VALUES ('$gen_acc_ID','$srcode','$type','$password','verify','$date')";
                }else{
                    $username = str_replace(' ', '', strtolower($lname));
                    $found = 1;
                    $user_count = 0;
                    while ($found == 1) {
                        $usernameQuery = mysqli_query($conn,"SELECT * FROM `login_info` WHERE username='$username'");
                        if (mysqli_num_rows($usernameQuery)>=1) {
                            $found = 1;
                            $user_count += 1;
                            $ext= str_pad($user_count, 2, 0, STR_PAD_LEFT);
                            $username = strtolower($lname)."".$ext;
                        }else{
                            $found = 0;
                        }
                    }
                    $sql_register = "INSERT INTO `teacher_info` (`account_id`, `fname`, `lname`, `email`, `phone`, `sex`) 
                        VALUES ('$gen_acc_ID','$fname','$lname','$email','$phone','$sex')";
                    $sql_login = "INSERT INTO `login_info` (`account_id`, `username`, `type`, `password`, `status`, `date_register`) 
                        VALUES ('$gen_acc_ID','$username','$type','$password','verify','$date')";
                }

                if(mysqli_query($conn,$sql_register) && mysqli_query($conn,$sql_login)){

                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                      CURLOPT_URL => 'https://atm-bsumalvar.000webhostapp.com/app/gen_profile_pic.php',
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => '',
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 0,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => 'POST',
                      CURLOPT_POSTFIELDS => array('srcode' => $srcode,'account_id' => $gen_acc_ID),
                    ));
                    
                    $response = curl_exec($curl);
                    
                    curl_close($curl);


                    $myObj->error = false;
                    $myObj->username = $username;
                }else {
                    $myObj->error = true;
                    $myObj->error_desc = "Error in inserting data on database";
                }
                
            }else {
                $myObj->error = true;
                $myObj->error_desc = "other_error";
            }

        }else if(mysqli_num_rows($device_id_query)>1){
            $myObj->error = true;
            $myObj->error_desc = "Multiple Device ID Detected";
        }else if(mysqli_num_rows($device_id_query)<1){
            $myObj->error = true;
            $myObj->error_desc = "Device is not Registered: $device_id";
        }
        
        $myJSON = json_encode($myObj);
        echo $myJSON;

}else{
    echo "Unauthorized Request";
}


?>


