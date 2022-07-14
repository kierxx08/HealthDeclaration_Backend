<?php
require "assets/conn.php";

if(isset($_GET['account_id']) && isset($_GET['search']) && isset($_GET['type'])){
    $account_id = $_GET['account_id'];
    $search = $_GET['search'];
    $type = $_GET['type'];
    
    $user_query = mysqli_query($conn,"SELECT * FROM `user_info` WHERE special_num='101720' AND account_id='$account_id'");
    $user_fetch = mysqli_fetch_array($user_query);
    $user_status = $user_fetch['status'];

    //check if nurse is verified
    if($user_status=="verified"){

        if($search == null){
            $user_sql = mysqli_query($conn,"SELECT * FROM user_info WHERE special_num!='101720'");
            $user_row = mysqli_num_rows($user_sql);
            $i = 1;
    
        echo '
            [  ';
            while($case_fetch=mysqli_fetch_array($user_sql)){
                $position_id = $case_fetch['position_id'];
                $special_num = $case_fetch['special_num'];

                if($special_num=="101719"){
                    $user_sql2 = mysqli_query($conn,"SELECT * FROM family_info, family_member WHERE family_info.family_id = '$position_id' AND family_info.fam_mem_id = family_member.fam_mem_id");
                    $position_txt = "Family";
                }else if($special_num=="101721"){
                    $user_sql2 = mysqli_query($conn,"SELECT * FROM doctor_info WHERE doctor_id = '$position_id'");
                    $position_txt = "Doctor";
                }else{
                    $user_sql2 = mysqli_query($conn,"SELECT * FROM nurse_info WHERE nurse_id = '$position_id'");
                    $position_txt = "Nurse";
                }

                $info_fetch = mysqli_fetch_array($user_sql2);
        
                $case_id = $info_fetch['fname']." ".$info_fetch['lname'];
        
                $case_brgy_name = $position_txt;

                $user_id = $case_fetch['account_id'];
                $sex = $info_fetch['sex'];
                $target_dir = "account/profile/user/";
                $target_file = $target_dir . $user_id . ".jpg";
                $pic_link = "https://hda-server.000webhostapp.com/app/".$target_file;

                
                if (file_exists($target_file)) {
                    $img = $pic_link;
                }else if($special_num=="101721" && $sex == "Female") {
                    $img = "https://hda-server.000webhostapp.com/app/images/profile_doctor_female.jpg";
                }else if($special_num=="101721" && $sex == "Male") {
                    $img = "https://hda-server.000webhostapp.com/app/images/profile_doctor_male.jpg";
                }else if($special_num=="101722" && $sex == "Female") {
                    $img = "https://hda-server.000webhostapp.com/app/images/profile_nurse_female.jpg";
                }else if($special_num=="101722" && $sex == "Male") {
                    $img = "https://hda-server.000webhostapp.com/app/images/profile_nurse_male.jpg";
                }else{
                    $img = "https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcTayT6TO8auGABi8G6HesSJYLLs9zKzUxmAKg&usqp=CAU";
                }
        
                echo '
                {  
                "case_id":"'.$case_id.'",
                "url":"'.$user_id.'",
                "case_brgy":"'.$case_brgy_name.'",
                "case_image":"'.$img.'"
                }';
        
            if($i<$user_row){
                echo ',';
            }
            $i += 1;
        }
        echo ']';
            
    }else{
    
    echo '
        [  ';
    
        //$sqlCovid = mysqli_query($conn,"SELECT *
        //FROM family_info, family_member
        //WHERE family_info.fam_mem_id = family_member.fam_mem_id AND ( family_member.fname LIKE '%$search%' OR family_member.lname LIKE '%$search%' OR family_info.family_id = '$search') ORDER BY family_info.family_id");
        if($type == "Family"){
            $user_sql = mysqli_query($conn,"SELECT * FROM user_info, family_info, family_member WHERE user_info.special_num = '101719' AND user_info.position_id = family_info.family_id AND family_member.fam_mem_id = family_info.fam_mem_id AND (user_info.username LIKE '$search' OR family_member.fname LIKE '$search' OR family_member.lname LIKE '$search' OR family_info.family_id='$search') ORDER BY family_member.fname");
            $user_row = mysqli_num_rows($user_sql);
            //$user_fetch = mysqli_fetch_array($user_sql);
        }else if($type == "Doctor"){
            $user_sql = mysqli_query($conn,"SELECT * FROM user_info, doctor_info WHERE user_info.special_num = '101721' AND user_info.position_id = doctor_info.doctor_id AND (user_info.username LIKE '$search' OR doctor_info.fname LIKE '$search' OR doctor_info.lname LIKE '$search' OR doctor_info.doctor_id='$search') ORDER BY doctor_info.fname");
            $user_row = mysqli_num_rows($user_sql);
            //$user_fetch = mysqli_fetch_array($user_sql);
        }else{
            $user_sql = mysqli_query($conn,"SELECT * FROM user_info, nurse_info WHERE user_info.special_num = '101722' AND user_info.position_id = nurse_info.nurse_id AND (user_info.username LIKE '$search' OR nurse_info.fname LIKE '$search' OR nurse_info.lname LIKE '$search' OR nurse_info.nurse_id='$search') ORDER BY nurse_info.fname");
            $user_row = mysqli_num_rows($user_sql);
            //$user_fetch = mysqli_fetch_array($user_sql);
        }
        
        $i = 1;
        while($case_fetch=mysqli_fetch_array($user_sql)){
            $case_id = $case_fetch['fname']." ".$case_fetch['lname'];
    
            $case_brgy_name = "";
            $case_detected = "2";

            $user_id = $case_fetch['account_id'];
             $target_dir = "account/profile/user/";
            $target_file = $target_dir . $user_id . ".jpg";
            $pic_link = "https://hda-server.000webhostapp.com/app/".$target_file;
            
            $special_num = $case_fetch['special_num'];
            $sex = $case_fetch['sex'];
            
            if (file_exists($target_file)) {
                $img = $pic_link;
            }else if($special_num=="101721" && $sex == "Female") {
                $img = "https://hda-server.000webhostapp.com/app/images/profile_doctor_female.jpg";
            }else if($special_num=="101721" && $sex == "Male") {
                $img = "https://hda-server.000webhostapp.com/app/images/profile_doctor_male.jpg";
            }else if($special_num=="101722" && $sex == "Female") {
                $img = "https://hda-server.000webhostapp.com/app/images/profile_nurse_female.jpg";
            }else if($special_num=="101722" && $sex == "Male") {
                $img = "https://hda-server.000webhostapp.com/app/images/profile_nurse_male.jpg";
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
    
        if($i<$user_row){
            echo ',';
        }
        $i += 1;
        }
        echo ']';
    }

    }else{
        echo "Your account have a Problem\nContact Support";
    }

}else{
	echo "Unauthorized Request";
}