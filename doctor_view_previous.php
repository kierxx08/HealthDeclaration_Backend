<?php
require "assets/conn.php";

if(isset($_GET["search"]) && isset($_GET["account_id"])){
    $account_id = $_GET["account_id"];
    $search = $_GET["search"];

    $doctor_query = mysqli_query($conn,"SELECT * FROM `user_info` WHERE account_id='$account_id'");
    $doctor_fetch = mysqli_fetch_array($doctor_query);
    $doctor_status = $doctor_fetch['status'];
    $doctor_id = $doctor_fetch['position_id'];
    
    //check if doctor is verified
    if($doctor_status=="verified"){
        
        echo '
        [  ';
        $sqlCovid = mysqli_query($conn,"SELECT *
        FROM family_info, family_member, checkup_info 
        WHERE family_info.family_id = family_member.fam_id AND family_member.fam_mem_id = checkup_info.fam_mem_id AND 
        checkup_info.status = 'finish' AND (family_member.fname LIKE '%$search%' OR family_member.lname LIKE '%$search%') AND checkup_info.doctor_id='$doctor_id' ORDER BY convert(checkup_info.date, decimal) DESC LIMIT 30");
        $num_case = mysqli_num_rows($sqlCovid);
        $i = 1;
        while($case_fetch=mysqli_fetch_array($sqlCovid)){
            $case_id = $case_fetch['fname']." ".$case_fetch['lname'];

            $case_brgy_name = $case_fetch['fam_add_add']." ".$case_fetch['fam_brgy'];
            $case_detected = date("M d, Y",$case_fetch['date']);
            
            $user_id = $case_fetch['fam_mem_id'];
            $target_dir = "account/profile/member/";
            $target_file = $target_dir . $user_id . ".jpg";
            $pic_link = "https://hda-server.000webhostapp.com/app/".$target_file;

            if (file_exists($target_file)) {
                $img = $pic_link;
            }else{
                $img = "https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcTayT6TO8auGABi8G6HesSJYLLs9zKzUxmAKg&usqp=CAU";
            }
            

            echo '
            {  
            "case_id":"'.$case_id.'",
            "url":"'.$case_detected.'",
            "case_brgy":"'.$case_brgy_name.'",
            "case_image":"'.$img.'"
            }';

        if($i<$num_case){
            echo ',';
        }
        $i += 1;
        }
        echo ']';

    }else{
        echo "Your account have a Problem\nContact Support";
    }
}else{
	echo "Unauthorized Request";
}

?>