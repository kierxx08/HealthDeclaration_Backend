<?php
require "assets/conn.php";

//echo 'Under Maintenance';
if(isset($_POST['to'])){
    //kier.asis8@gmail.com//09501729430
    $apicode = "TR-HEALT729430_UXRZ3";
    $passwd = "h47bc1iy(w";

    if($_POST['to'] == "find"){
        $pnumber_old = $_POST['pnumber'];
        $pnumber = substr($pnumber_old, 1);
        $find_admn_query = mysqli_query($conn,"SELECT * FROM user_info, admin_info WHERE user_info.special_num = '101720' AND user_info.position_id = admin_info.admin_id AND admin_info.pnumber='$pnumber'");
        $find_fam_query = mysqli_query($conn,"SELECT * FROM user_info, family_info, family_member WHERE user_info.special_num = '101719' AND user_info.position_id = family_info.family_id AND family_member.fam_mem_id = family_info.fam_mem_id AND family_info.pnumber='$pnumber'");
        $find_doc_query = mysqli_query($conn,"SELECT * FROM user_info, doctor_info WHERE user_info.special_num = '101721' AND user_info.position_id = doctor_info.doctor_id AND doctor_info.pnumber='$pnumber'");
        $find_nur_query = mysqli_query($conn,"SELECT * FROM user_info, nurse_info WHERE user_info.special_num = '101722' AND user_info.position_id = nurse_info.nurse_id AND nurse_info.pnumber='$pnumber'");
        $admn_row = mysqli_num_rows($find_admn_query);
        $fam_row = mysqli_num_rows($find_fam_query);
        $doc_row = mysqli_num_rows($find_doc_query);
        $nur_row = mysqli_num_rows($find_nur_query);
        
        $total = $fam_row + $doc_row +$nur_row;
        if($admn_row>$total){
            echo "Admin";
        }else if($total>1){
            echo "Multiple;$apicode;$passwd";
        }else if($total == 1){
            if($fam_row == 1){
                $data_ftch = mysqli_fetch_array($find_fam_query);
            }else if($doc_row == 1){
                $data_ftch = mysqli_fetch_array($find_doc_query);
            }else if($nur_row == 1){
                $data_ftch = mysqli_fetch_array($find_nur_query);
            }
            $account_id = $data_ftch['account_id'];
            $full_name = $data_ftch['fname']." ".$data_ftch['lname'];

            echo "One;$account_id;$full_name;$apicode;$passwd";
        }else{
            echo "None";
        }
        
    }else if($_POST['to'] == "code"){
        $type = $_POST['type'];
        $account_id = $_POST['account_id'];
        $pnumber = substr($_POST['pnumber'], 1);
        $username = "none";
        if($account_id != "none"){
            $count = 1;
        }
        
        if($account_id=="none"){
            if($type=="Family"){
                $user_query = mysqli_query($conn,"SELECT * FROM user_info, family_info, family_member WHERE user_info.special_num = '101719' AND user_info.position_id = family_info.family_id AND family_member.fam_mem_id = family_info.fam_mem_id AND family_info.pnumber='$pnumber'");
                $data_ftch = mysqli_fetch_array($user_query);
                $account_id = $data_ftch['account_id'];
                $count = mysqli_num_rows($user_query);
                $username = $data_ftch['username'];

            }else if($type=="Doctor"){
                $find_doc_query = mysqli_query($conn,"SELECT * FROM user_info, doctor_info WHERE user_info.special_num = '101721' AND user_info.position_id = doctor_info.doctor_id AND doctor_info.pnumber='$pnumber'");
                $data_ftch = mysqli_fetch_array($find_doc_query);
                $account_id = $data_ftch['account_id'];
                $count = mysqli_num_rows($find_doc_query);
                $username = $data_ftch['username'];

            }else if($type=="Nurse"){
                $find_nur_query = mysqli_query($conn,"SELECT * FROM user_info, nurse_info WHERE user_info.special_num = '101722' AND user_info.position_id = nurse_info.nurse_id AND nurse_info.pnumber='$pnumber'");
                $data_ftch = mysqli_fetch_array($find_nur_query);
                $account_id = $data_ftch['account_id'];
                $count = mysqli_num_rows($find_nur_query);
                $username = $data_ftch['username'];

            }


        }else{
            $user_query = mysqli_query($conn,"SELECT * FROM `user_info` WHERE account_id='$account_id'");
            $user_fetch = mysqli_fetch_array($user_query);
            $username = $user_fetch['username'];
        }

        $forgot_query = mysqli_query($conn,"SELECT * FROM fp_verify WHERE account_id='$account_id' AND status='pending'");
        $forgot_count = mysqli_num_rows($forgot_query);


        $date = time();
        $code = mt_rand(100000, 999999);

        if($count==0){
            echo "Select Valid User Type";
            
        }else if($forgot_count>3){
            echo "Forgot Reached";

        }else if($count>1){

            echo "Double Error";
            
        }else if($account_id!="none" && $username!="none"){
            $code_query = "INSERT INTO `fp_verify` (`account_id`, `code`, `status`, `date`)
                                VALUES ('$account_id','$code','pending','$date')";
            
            if(mysqli_query($conn,$code_query)){
                echo "Success;$code;$username";
            }else{
                echo "Failed to Register";
            }



        }else{
            echo "Unknown Error Occur";
        }




    }else if($_POST['to'] == "verify"){
        $code = $_POST['code'];
        $account_id = $_POST['account_id'];

        $find_nur_query = mysqli_query($conn,"SELECT * FROM fp_verify WHERE account_id='$account_id' AND code='$code' AND status='pending'");
        $data_ftch = mysqli_fetch_array($find_nur_query);
        $count = mysqli_num_rows($find_nur_query);

        if($count==1){
            $update_user_code_query = "update fp_verify set status='verified' where account_id='$account_id'";
            if(mysqli_query($conn,$update_user_code_query)){
                echo "Success";
            }else{
                echo "Failed to Update";
            }
        }else{
            echo "Wrong OTP";
        }


    }else if($_POST['to'] == "update"){
        $new_password = $_POST['new_password'];
        $account_id = $_POST['account_id'];

            $update_username_query = "update user_info set password='$new_password' where account_id='$account_id'";
            if(mysqli_query($conn,$update_username_query)){
                echo "Success";
            }else{
                echo "Failed to Update";
            }


    }else{
        echo "Missing Something";
    }
}else{
    echo "Unauthorized Request";
}

?>