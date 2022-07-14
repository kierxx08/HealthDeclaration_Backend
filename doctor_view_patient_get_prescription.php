<?php
require "assets/conn.php";

if(isset($_GET['checkup_id'])){
    $checkup_id = $_GET['checkup_id'];
    $account_id =  $_GET['account_id'];
    
    $user_query = mysqli_query($conn,"SELECT * FROM `user_info` WHERE account_id='$account_id'");
    $user_fetch = mysqli_fetch_array($user_query);
    $user_status = $user_fetch['status'];

    //check if doctor is verified
    if($user_status=="verified"){

    echo '
    [  ';

    //$sqlCovid = mysqli_query($conn,"SELECT *
    //FROM family_info, family_member
    //WHERE family_info.fam_mem_id = family_member.fam_mem_id AND ( family_member.fname LIKE '%$search%' OR family_member.lname LIKE '%$search%' OR family_info.family_id = '$search') ORDER BY family_info.family_id");

    $query = mysqli_query($conn,"SELECT *
    FROM prescription_info WHERE checkup_id='$checkup_id' ORDER BY date");
    $row = mysqli_num_rows($query);
    $i = 1;
    while($fetch=mysqli_fetch_array($query)){
        $med_name = $fetch['medicine'];
        $amount = ucwords($fetch['taking_amount']);
        $time = str_replace(";"," | ",$fetch['taking_time']);
        $day_num = $fetch['taking_day'];
        if($day_num==1){
            $day_text = "Day";
        }else if($day_num>1){
            $day_text = "Days";
        }
        $pieces = explode(' ', $amount);
        $last_word = array_pop($pieces);
        if(strtolower($last_word)=="tablet"){
            $img = "https://www.michaelshouse.com/wp-content/uploads/2018/04/round-white-pill-410x350.jpg";
        } else if(strtolower($last_word)=="capsule"){
            $img = "https://lh3.googleusercontent.com/proxy/SNHws6N4CeUXfTiDwnCo8sTB14toMCHEV8y90YDLYjJUQ6POBLFpAfwflZfto4oj4ysAahzA69jTxwfzvX3t-H9usJs83TFJN3-OyHN9tWHRKrLORGhzvkAxzLZ3DTpN26bKVUXGi1Mm";
        }
        echo '
        {  
        "med_name":"'.$med_name.'",
        "amount":"'.$amount.'",
        "time":"'.$time.'",
        "day_num":"'.$day_num.'",
        "day_text":"'.$day_text.'",
        "img":"'.$img.'"
        }';

    if($i<$row){
        echo ',';
    }
    $i += 1;
    }
    echo ']';
    }else{
        echo "Your account has a Problem\nContact Support";
    }

}else{
	echo "Unauthorized Request";
}
?>