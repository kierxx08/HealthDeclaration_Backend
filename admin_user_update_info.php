<?php
require "assets/conn.php";

if(isset($_POST['account_id']) && isset($_POST['user_account_id']) && isset($_POST['username']) && isset($_POST['fname'])
&& isset($_POST['lname']) && isset($_POST['bday']) && isset($_POST['pnumber']) && isset($_POST['sex']) && isset($_POST['brgy'])
&& isset($_POST['add_add']) && isset($_POST['status'])){
    $account_id = $_POST['account_id'];
    $user_account_id = $_POST['user_account_id'];
    $username = $_POST['username'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $bday = $_POST['bday'];
    $pnumber = $_POST['pnumber'];
    $sex = $_POST['sex'];
    $brgy = $_POST['brgy'];
    $add_add = $_POST['add_add'];
    $status = $_POST['status'];
    
    $user_query = mysqli_query($conn,"SELECT * FROM `user_info` WHERE special_num='101720' AND account_id='$account_id'");
    $user_fetch = mysqli_fetch_array($user_query);
    $user_status = $user_fetch['status'];


    //check if nurse is verified
    if($user_status=="verified"){
        $user_info_query = mysqli_query($conn,"SELECT * FROM `user_info` WHERE account_id='$user_account_id'");
        $user_info_fetch = mysqli_fetch_array($user_info_query);
        $user_info_special_num = $user_info_fetch['special_num'];
        $user_info_position_id = $user_info_fetch['position_id'];
        $user_username = $user_info_fetch['username'];

        $username_error = "false";
        if($user_username != $username){
            $username_query = mysqli_query($conn,"SELECT * FROM `user_info` WHERE username='$username'");
            if(mysqli_num_rows($username_query)>=1){
                $username_error = "true";
            }else{
                $username_error = "false";
            }
        }
        
        //family
        if($user_info_special_num == "101719"){
            $family_query = mysqli_query($conn,"SELECT * FROM `family_member` WHERE fname='$fname' AND lname='$lname' AND bday='$bday'");
            $number_query = mysqli_query($conn,"SELECT * FROM `family_info` WHERE pnumber='$pnumber'");
        //doctor
        }else if($user_info_special_num == "101721"){
            $family_query = mysqli_query($conn,"SELECT * FROM `doctor_info` WHERE fname='$fname' AND lname='$lname' AND bday='$bday'");
            $number_query = mysqli_query($conn,"SELECT * FROM `doctor_info` WHERE pnumber='$pnumber'");
            
        //nurse
        }else if($user_info_special_num == "101722"){
            $family_query = mysqli_query($conn,"SELECT * FROM `nurse_info` WHERE fname='$fname' AND lname='$lname' AND bday='$bday'");
            $number_query = mysqli_query($conn,"SELECT * FROM `nurse_info` WHERE pnumber='$pnumber'");
            
        
        }

        
        
        //check family is already register
        if($username_error == "false"){
            if(mysqli_num_rows($family_query)<=1){
                if(mysqli_num_rows($number_query)<=1){

                    if($user_info_special_num == "101719"){
                        $update_user_info_query = "update user_info set username='$username' AND status='$status' where account_id='$user_account_id'";
                        $update_family_info_query = "update family_info set pnumber='$pnumber' AND fam_brgy='$brgy' AND fam_add_add='$add_add' where family_id='$user_info_position_id'";
                        $fam_mem_query = mysqli_query($conn,"SELECT * FROM `family_info` WHERE family_id='$user_info_position_id'");
                        $fam_mem_fetch = mysqli_fetch_array($fam_mem_query);
                        $member_id = $fam_mem_fetch['fam_mem_id'];
                        $update_family_member_query = "update family_member set fname='$fname' AND lname='$lname' AND bday='$bday' AND sex='$sex' where fam_mem_id='$member_id'";

                        if(mysqli_query($conn,$update_user_info_query) && mysqli_query($conn,$update_family_info_query) && mysqli_query($conn,$update_family_member_query)){
                            echo "Success";
                        }else{
                            echo "Failed to Update";
                        }
                    }else if($user_info_special_num == "101721"){
                        
                        $update_user_info_query = "update user_info set username='$username' AND status='$status' where account_id='$user_account_id'";
                        $update_doctor_info_query = "update doctor_info set fname='$fname' AND lname='$lname' AND bday='$bday' AND pnumber='$pnumber' AND sex='$sex' where doctor_id='$user_info_position_id'";
                        
                        if(mysqli_query($conn,$update_user_info_query) && mysqli_query($conn,$update_doctor_info_query)){
                            echo "Success";
                        }else{
                            echo "Failed to Update";
                        }
                    }else if($user_info_special_num == "101722"){
                        
                        $update_user_info_query = "update user_info set username='$username' AND status='$status' where account_id='$user_account_id'";
                        $update_nurse_info_query = "update nurse_info set fname='$fname' AND lname='$lname' AND bday='$bday' AND pnumber='$pnumber' AND sex='$sex' where nurse_id='$user_info_position_id'";

                        if(mysqli_query($conn,$update_user_info_query) && mysqli_query($conn,$update_nurse_info_query)){
                            echo "Success";
                        }else{
                            echo "Failed to Update";
                        }
                    }else{
                        echo "Fatal Error";
                    }

                }else{
                    echo "Phone Number Already Used";
                }
                
            }else{
                echo "Duplicate Account";
            }
        }else{
            echo "Username is Alreay Used";
        }
    }else{
        echo "Your account have a Problem\nContact Support";
    }
    
}else{
    echo "Unauthorized Request";
}