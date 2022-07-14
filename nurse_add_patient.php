<?php
require "assets/conn.php";

if(isset($_POST["member_id"]) &&isset($_POST["doctor_id"]) && isset($_POST["height"]) && isset($_POST["weight"])&& isset($_POST["temperature"]) &&
    isset($_POST["systolic"]) && isset($_POST["diastolic"]) && isset($_POST["illness"]) && isset($_POST["nurse_id"])){
    
    $member_id = $_POST["member_id"];
    $doctor_id = $_POST["doctor_id"];
    $height = $_POST["height"];
    $weight = $_POST["weight"];
    $temperature = $_POST["temperature"];
    $systolic = $_POST["systolic"];
    $diastolic = $_POST["diastolic"];
    $illness = $_POST["illness"];
    $nurse_id = $_POST["nurse_id"];
    $date = time();
    
    //check
    $nurse_query = mysqli_query($conn,"SELECT * FROM `user_info` WHERE account_id='$nurse_id'");
    $nurse_fetch = mysqli_fetch_array($nurse_query);
    $nurse_status = $nurse_fetch['status'];

    //check if nurse is verified
    if($nurse_status=="verified"){
        //check member
        $member_query = mysqli_query($conn,"SELECT * FROM `family_member` WHERE fam_mem_id='$member_id'");

        //check member if exist
        if(mysqli_num_rows($member_query)==1){

            //generate family member id
            $found = 1;
	        while ($found == 1) {
		        //$genID = "U-";
		        $genID = date("y");
		        $genID .= date("m");
		        $genID .= date("d");
		        $genID .= date("-");
		        $genID .= str_pad(rand(0, 9999), 4, 0, STR_PAD_LEFT);
		        $genIDQuery = mysqli_query($conn,"SELECT * FROM `checkup_info` WHERE checkup_id='$genID'");
		        if (mysqli_num_rows($genIDQuery)==1) {
			        $found = 1;
		        }else{
			        $found = 0;
		        }
            }

            $sql_register_checkup_info = "INSERT INTO `checkup_info`(`checkup_id`, `fam_mem_id`, `weight`, `height`, `temperature`, `blood_pressure`, `illness_info`, `doctor_id`, `date`) 
            VALUES ('$genID','$member_id','$weight','$height','$temperature','$systolic/$diastolic','$illness','$doctor_id','$date')";


		    if(mysqli_query($conn,$sql_register_checkup_info)){
				echo "Success";
		    }else{
			    echo "Failed to Register";
		    }
            
        }else{
            echo "No Member Found\nContact Support";
        }
    }else{
        echo "Unauthorized Account";
    }

}else{
	echo "Unauthorized Request";
}

?>