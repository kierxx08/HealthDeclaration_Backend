<?php
require "assets/conn.php";

if(isset($_POST['account_id'])&&isset($_POST['data'])&&isset($_POST['from'])){
    $data = $_POST['data'];
    $from = $_POST['from'];
    $account_id = $_POST['account_id'];

    $user_query = mysqli_query($conn,"SELECT * FROM `user_info` WHERE account_id='$account_id'");
    $user_fetch = mysqli_fetch_array($user_query);
    $user_status = $user_fetch['status'];

    //check if doctor is verified
    if($user_status=="verified"){
        $mem_info_query = mysqli_query($conn,"SELECT * FROM family_member
        WHERE fam_id = '$family_id' ORDER BY FIELD(pitf, 'Mother', 'Father') DESC LIMIT $data,1");
        $member_info_count = mysqli_num_rows['$mem_info_query'];
        $mem_info_fetch = mysqli_fetch_array($mem_info_query);
        $member_id = mem_info_fetch['fam_mem_id'];
        $fullname = mem_info_fetch['fname']+" "+mem_info_fetch['lname'];
        $bday = mem_info_fetch['bday'];
        $sex = mem_info_fetch['sex'];

        $mem_checkup_info_query = mysqli_query($conn,"SELECT * FROM checkup_info
        WHERE fam_mem_id = '$member_id' ORDER BY convert(checkup_info.date, decimal) DESC");
        $mem_checkup_info_fetch = mysqli_fetch_array($mem_checkup_info_query);
        $consult_count = mysqli_num_rows['mem_checkup_info_fetch'];
        $last_consult = date("m-d-Y",$mem_checkup_info_fetch['date']);

        if(member_info_count>0){
            echo "Success;$member_id;$fullname;$bday;$sex;$consult_count;$last_consult;none";
        }else{
            echo "Not Found";
        }
            

    }else{
            echo "Your account has a Problem\nContact Support";
    }
}else{
	echo "Unauthorized Request";
}


?>