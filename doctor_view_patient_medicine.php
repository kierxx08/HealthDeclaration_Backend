<?php
require "assets/conn.php";
if(isset($_POST["illness"]) && isset($_POST["medicine"]) && isset($_POST["amount"]) && isset($_POST["time"]) && isset($_POST["day"]) && isset($_POST["checkup_id"]) && isset($_POST["account_id"])){
    
    $illness = ucwords($_POST["illness"]);
    $medicine = ucwords($_POST["medicine"]);
    $amount = ucwords($_POST["amount"]);
    $time = $_POST["time"];
    $day = $_POST["day"];
    $checkup_id = $_POST["checkup_id"];
    $account_id = $_POST["account_id"];
    $date = time();

    $doctor_query = mysqli_query($conn,"SELECT * FROM `user_info` WHERE account_id='$account_id'");
    $doctor_fetch = mysqli_fetch_array($doctor_query);
    $doctor_status = $doctor_fetch['status'];
    $doctor_id = $doctor_fetch['position_id'];
    
    //check if doctor is verified
    if($doctor_status=="verified"){
        
        //generate family member id
        $found = 1;
        while ($found == 1) {
            //$genID = "U-";
            $genID = date("y");
            $genID .= date("m");
            $genID .= date("d");
            $genID .= "-";
            $genID .= str_pad(rand(0, 9999), 4, 0, STR_PAD_LEFT);
            $usernameQuery = mysqli_query($conn,"SELECT * FROM `prescription_info` WHERE pres_id='$genID'");
            if (mysqli_num_rows($usernameQuery)==1) {
                $found = 1;
            }else{
                $found = 0;
            }
        }
                
        $sql_add_med = "INSERT INTO `prescription_info`(`pres_id`, `checkup_id`, `illness`, `medicine`, `taking_amount`, `taking_time`, `taking_day`, `date`)
        VALUES ('$gen_acc_ID','$checkup_id','$illness','$medicine','$amount','$time','$day','$day')";


        if(mysqli_query($conn,$sql_add_med)){
            echo "Success";
        }else{
            echo "Failed to Add Medicine";
        }


    }else{
        echo "Your account have a Problem\nContact Support";
    }
}else{
	echo "Unauthorized Request";
}


?>