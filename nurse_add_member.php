<?php
require "assets/conn.php";

if(isset($_POST["fname"]) && isset($_POST["lname"]) && isset($_POST["bday"])&& isset($_POST["pitf"]) &&
    isset($_POST["sex"]) && isset($_POST["fam_id"]) && isset($_POST["nurse_id"])){
    
    $fname = ucwords($_POST["fname"]);
    $lname = ucwords($_POST["lname"]);
    $bday = $_POST["bday"];
    $pitf = $_POST["pitf"];
    $sex = $_POST["sex"];
    $fam_id = $_POST["fam_id"];
    $nurse_id = $_POST["nurse_id"];
    $date = time();

    $nurse_query = mysqli_query($conn,"SELECT * FROM `user_info` WHERE account_id='$nurse_id'");
    $nurse_fetch = mysqli_fetch_array($nurse_query);
    $nurse_status = $nurse_fetch['status'];

    //check if nurse is verified
    if($nurse_status=="verified"){
        $family_query = mysqli_query($conn,"SELECT * FROM `family_member` WHERE fname='$fname' AND lname='$lname' AND bday='$bday'");
        $family_pitf_query = mysqli_query($conn,"SELECT * FROM `family_member` WHERE fam_id='$fam_id' AND pitf='$pitf' AND (pitf='Father' OR pitf='Mother')");
        
        //check member if already register
        if(mysqli_num_rows($family_query)<1){
            if(mysqli_num_rows($family_pitf_query)<1){

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

                $sql_register_fam_mem_info = "INSERT INTO `family_member` (`fam_mem_id`, `fam_id`, `fname`, `lname`, `bday`, `pitf`, `sex`, `date`)
                VALUES ('$genID','$fam_id','$fname','$lname','$bday','$pitf','$sex','$date')";


                if(mysqli_query($conn,$sql_register_fam_mem_info)){
                    echo "Success;$genID";
                }else{
                    echo "Failed to Register";
                }
            
            }else{
                echo $pitf." of the Family Already Exist";
            }
            
        }else{
            echo "We Found Duplicate\nContact Support";
        }
    }else{
        echo "Unauthorized Account";
    }

}else{
	echo "Unauthorized Request";
}

?>