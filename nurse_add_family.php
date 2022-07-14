<?php
require "assets/conn.php";

if(isset($_POST["fname"]) && isset($_POST["lname"]) && isset($_POST["bday"]) && isset($_POST["pnumber"]) &&
    isset($_POST["brgy"]) && isset($_POST["add_add"]) && isset($_POST["pitf"]) && isset($_POST["sex"]) &&
    isset($_POST["nurse_id"])){
    
    $fname = ucwords($_POST["fname"]);
    $lname = ucwords($_POST["lname"]);
    $bday = $_POST["bday"];
    $pnumber = $_POST["pnumber"];
    $brgy = $_POST["brgy"];
    $add_add = $_POST["add_add"];
    $pitf = $_POST["pitf"];
    $sex = $_POST["sex"];
    $nurse_id = $_POST["nurse_id"];
    $date = time();

    $nurse_query = mysqli_query($conn,"SELECT * FROM `user_info` WHERE account_id='$nurse_id'");
    $nurse_fetch = mysqli_fetch_array($nurse_query);
    $nurse_status = $nurse_fetch['status'];

    //check if nurse is verified
    if($nurse_status=="verified"){
        $family_query = mysqli_query($conn,"SELECT * FROM `family_member` WHERE fname='$fname' AND lname='$lname' AND bday='$bday'");
        $number_query = mysqli_query($conn,"SELECT * FROM `family_info` WHERE pnumber='$pnumber'");
        
        //check family is already register
        if(mysqli_num_rows($family_query)<1){
            if(mysqli_num_rows($number_query)<1){

                //get last family info
                $last_family_query = mysqli_query($conn,"SELECT * FROM `family_info` ORDER BY CAST(family_id AS DECIMAL(20,2)) DESC LIMIT 1");
                $last_family_fetch = mysqli_fetch_array($last_family_query);
                $last_family_id = $last_family_fetch['family_id']+1;

                //generate family member id
                $found = 1;
                while ($found == 1) {
                    //$genID = "U-";
                    $genID = date("y");
                    $genID .= date("m");
                    $genID .= date("d");
                    $genID .= str_pad(rand(0, 9999), 4, 0, STR_PAD_LEFT);
                    $usernameQuery = mysqli_query($conn,"SELECT * FROM `family_member` WHERE fam_mem_id='$genID'");
                    if (mysqli_num_rows($usernameQuery)==1) {
                        $found = 1;
                    }else{
                        $found = 0;
                    }
                }

                //generate account id
                $found = 1;
                while ($found == 1) {
                    $gen_acc_ID = str_pad(rand(0, 99999999), 8, 0, STR_PAD_LEFT);
                    $usernameQuery = mysqli_query($conn,"SELECT * FROM `user_info` WHERE account_id='$gen_acc_ID'");
                    if (mysqli_num_rows($usernameQuery)==1) {
                        $found = 1;
                    }else{
                        $found = 0;
                    }
                }
                
                $sql_register_fam_info = "INSERT INTO `family_info` (`family_id`, `fam_mem_id`, `pnumber`, `fam_brgy`, `fam_add_add`)
                                VALUES ('$last_family_id','$genID','$pnumber','$brgy','$add_add')";

                $sql_register_fam_mem_info = "INSERT INTO `family_member` (`fam_mem_id`, `fam_id`, `fname`, `lname`, `bday`, `pitf`, `sex`, `date`)
                                VALUES ('$genID','$last_family_id','$fname','$lname','$bday','$pitf','$sex','$date')";

                $username = strtolower($lname);
                $password = date_format(date_create($bday),"d-m-Y");

                $sql_register_acc_info = "INSERT INTO `user_info`(`account_id`, `username`, `password`, `position_id`, `special_num`, `status`)
                                VALUES ('$gen_acc_ID','$username','$password','$last_family_id','101719','verified')";

                if(mysqli_query($conn,$sql_register_fam_info) && mysqli_query($conn,$sql_register_fam_mem_info) && mysqli_query($conn,$sql_register_acc_info)){
                    echo "Success;$last_family_id;$genID";
                }else{
                    echo "Failed to Register";
                }
            
            }else{
                echo "Phone Number Already Used";
            }
            
        }else{
            echo "We Found Duplicate Account\nContact Support";
        }
    }else{
        echo "Your account have a Problem\nContact Support";
    }

}else{
	echo "Unauthorized Request";
}

?>