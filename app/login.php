<?php
require "assets/remotesql.php";

if(isset($_POST["username"]) && isset($_POST["psw"]) && isset($_POST["device_id"])){
    $username= $_POST["username"];
    $password= $_POST["psw"];
    $device_id= $_POST["device_id"];
    
    $device_id_query = mysqli_query($conn,"SELECT * FROM `device_info` WHERE device_id='$device_id'");
    $myObj = new \stdClass();
        if(mysqli_num_rows($device_id_query)==1){
    
            $sql_login = "SELECT * FROM `login_info` WHERE BINARY username='$username' AND BINARY password='$password'";
        	$login_Query = mysqli_query($conn,$sql_login);
        
            
            
        	if (mysqli_num_rows($login_Query)>0) {
        	    
                    $myObj->error = false;
            	    $login_fetch=mysqli_fetch_array($login_Query);
            	    $account_id = $login_fetch['account_id'];
            	    $type = $login_fetch['type'];
            	    $myObj->account_id = $login_fetch['account_id'];
            	    $myObj->type = $login_fetch['type'];
            	    
            	    if($type == "student"){
            	        $Query_user = mysqli_query($conn, "SELECT * FROM `student_info` WHERE account_id='$account_id'");
            	        $user_fetch=mysqli_fetch_array($Query_user);
            	        $myObj->srcode = $user_fetch['srcode'];
            	    }else{
            	        $Query_user = mysqli_query($conn, "SELECT * FROM `teacher_info` WHERE account_id='$account_id'");
            	        $user_fetch=mysqli_fetch_array($Query_user);
            	    }
            	    
            	    $account_id = $login_fetch['account_id'];
        	        $date = addslashes(date("Y-m-d H:i:s"));
        	    
        	        $sql_login_logs = "INSERT INTO `login_logs` (`id`, `account_id`, `device_id`, `date`) VALUES (0,'$account_id','$device_id','$date')";
                    if(mysqli_query($conn,$sql_login_logs)){
                    
                	   $myObj->fname = $user_fetch['fname'];
                	   $myObj->lname = $user_fetch['lname'];
                	   $myObj->email = $user_fetch['email'];
                	   $myObj->phone = $user_fetch['phone'];
                	   $myObj->sex = $user_fetch['sex'];
                	   
                	   $curl = curl_init();
                
                        curl_setopt_array($curl, array(
                          CURLOPT_URL => 'https://atm-bsumalvar.000webhostapp.com/app/get_profile_pic_base64.php',
                          CURLOPT_RETURNTRANSFER => true,
                          CURLOPT_ENCODING => '',
                          CURLOPT_MAXREDIRS => 10,
                          CURLOPT_TIMEOUT => 0,
                          CURLOPT_FOLLOWLOCATION => true,
                          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                          CURLOPT_CUSTOMREQUEST => 'POST',
                          CURLOPT_POSTFIELDS => array('account_id' => $account_id),
                        ));
                        
                        $response = curl_exec($curl);
                        
                        curl_close($curl);
                        $myObj->photo_profile = $response;
                    }else{
                        $myObj->error = true;
                        $myObj->error_desc = "Error in inserting data on the database";
                        
                    }
        	  
        	    
        	}else{
                $myObj->error = true;
                $myObj->error_desc = "Your username and/or password is incorrect";
        	}
        }else{
                $myObj->error = true;
                $myObj->error_desc = "Device is not Registered: $device_id";
        }

    
    $myJSON = json_encode($myObj);
    echo $myJSON;
}else{
    echo "Error";
}


?>