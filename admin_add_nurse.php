<?php
require "assets/conn.php";

if(isset($_POST['account_id'])&&isset($_POST['fname'])&&isset($_POST['lname'])&&isset($_POST['bday'])&&isset($_POST['pnumber'])&&isset($_POST['sex'])){

    $account_id = $_POST['account_id'];
    $fname = ucwords($_POST['fname']);
    $lname = ucwords($_POST['lname']);
    $bday = $_POST['bday'];
    $pnumber = $_POST['pnumber'];
    $sex = $_POST['sex'];
    $date = time();

    $user_query = mysqli_query($conn,"SELECT * FROM `user_info` WHERE special_num='101720' AND account_id='$account_id'");
    $user_fetch = mysqli_fetch_array($user_query);
    $user_status = $user_fetch['status'];

    //check if nurse is verified
    if($user_status=="verified"){
        $check_user_query = mysqli_query($conn,"SELECT * FROM `nurse_info` WHERE fname='$fname' AND lname='$lname' AND bday='$bday'");
        $check_number_query = mysqli_query($conn,"SELECT * FROM `nurse_info` WHERE pnumber='$pnumber'");
        
        //check family is already register
        if(mysqli_num_rows($check_user_query)<1){
            if(mysqli_num_rows($check_number_query)<1){

                //get last nurse info
                $last_nurse_query = mysqli_query($conn,"SELECT * FROM `nurse_info` ORDER BY CAST(nurse_id AS DECIMAL(20,2)) DESC LIMIT 1");
                $last_nurse_fetch = mysqli_fetch_array($last_nurse_query);
                $last_nurse_id = $last_nurse_fetch['nurse_id']+1;

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
                
                $username = strtolower($lname);
                $password = date_format(date_create($bday),"d-m-Y");

                $sql_register_acc_info = "INSERT INTO `user_info`(`account_id`, `username`, `password`, `position_id`, `special_num`, `status`)
                                VALUES ('$gen_acc_ID','$username','$password','$last_nurse_id','101722','verified')";

                $sql_register_nur_info = "INSERT INTO `nurse_info`(`nurse_id`, `fname`, `lname`, `bday`, `pnumber`, `sex`, `date`)
                                VALUES ('$last_nurse_id','$fname','$lname','$bday','$pnumber','$sex','$date')";


                if(mysqli_query($conn,$sql_register_acc_info) && mysqli_query($conn,$sql_register_nur_info)){
                    echo "Success";
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