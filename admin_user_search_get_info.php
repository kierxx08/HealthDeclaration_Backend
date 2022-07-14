<?php
require "assets/conn.php";

if(isset($_POST['account_id']) && isset($_POST['search']) && isset($_POST['data']) && isset($_POST['type'])){
    $account_id = $_POST['account_id'];
    $search = $_POST['search'];
    $type = $_POST['type'];
    $data = $_POST['data'];
    
    $user_query = mysqli_query($conn,"SELECT * FROM `user_info` WHERE special_num='101720' AND account_id='$account_id'");
    $user_fetch = mysqli_fetch_array($user_query);
    $user_status = $user_fetch['status'];

    //check if nurse is verified
    if($user_status=="verified"){
        
        if(($search == null) || $search == "none"){
            $user_sql = mysqli_query($conn,"SELECT * FROM user_info WHERE special_num!='101720' LIMIT $data,1");
            $user_row = mysqli_num_rows($user_sql);
            $user_ftch = mysqli_fetch_array($user_sql);

            $position_id = $user_ftch['position_id'];
            $special_num = $user_ftch['special_num'];

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
            $username = $user_ftch['username'];
            $fname = $info_fetch['fname'];
            $lname = $info_fetch['lname'];
            $bday = $info_fetch['bday'];
            $new_bday = date_format(date_create($bday),"M. d, Y");
            $pnumber = $info_fetch['pnumber'];
            $sex = $info_fetch['sex'];
            $status = $user_ftch['status'];
            
            $brgy = "none";
            $add_add = "none";

            $user_id = $user_ftch['account_id'];
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
            
            if($special_num=="101719"){
                $brgy = $info_fetch['fam_brgy'];
                $add_add = $info_fetch['fam_add_add'];
            }

            echo "Success;$user_id;$username;$fname;$lname;$bday;$new_bday;$pnumber;$sex;$status;$img;$brgy;$add_add";

        }else if($type == "Doctor"){
            $user_sql = mysqli_query($conn,"SELECT * FROM user_info, doctor_info WHERE user_info.special_num = '101721' AND user_info.position_id = doctor_info.doctor_id AND (user_info.username LIKE '$search' OR doctor_info.fname LIKE '$search' OR doctor_info.lname LIKE '$search' OR doctor_info.doctor_id='$search') ORDER BY doctor_info.fname LIMIT $data,1");
            $user_ftch = mysqli_fetch_array($user_sql);
            $user_row = mysqli_num_rows($user_sql);
            $username = $user_ftch['username'];
            $fname = $user_ftch['fname'];
            $lname = $user_ftch['lname'];
            $bday = $user_ftch['bday'];
            $new_bday = date_format(date_create($bday),"M. d, Y");
            $pnumber = $user_ftch['pnumber'];
            $sex = $user_ftch['sex'];
            $status = $user_ftch['status'];
            
            $user_id = $user_ftch['account_id'];
            $target_dir = "account/profile/user/";
            $target_file = $target_dir . $user_id . ".jpg";
            $pic_link = "https://hda-server.000webhostapp.com/app/".$target_file;

            if (file_exists($target_file)) {
                $img = $pic_link;
            }else if($sex == "Female") {
                $img = "https://hda-server.000webhostapp.com/app/images/profile_doctor_female.jpg";
            }else{
                $img = "https://hda-server.000webhostapp.com/app/images/profile_doctor_male.jpg";
            }

            echo "Success;$user_id;$username;$fname;$lname;$bday;$new_bday;$pnumber;$sex;$status;$img;none;none";
        }else if($type == "Nurse"){
            $user_sql = mysqli_query($conn,"SELECT * FROM user_info, nurse_info WHERE user_info.special_num = '101722' AND user_info.position_id = nurse_info.nurse_id AND (user_info.username LIKE '$search' OR nurse_info.fname LIKE '$search' OR nurse_info.lname LIKE '$search' OR nurse_info.nurse_id='$search') ORDER BY nurse_info.fname LIMIT $data,1");
            $user_ftch = mysqli_fetch_array($user_sql);
            $user_row = mysqli_num_rows($user_sql);
            $username = $user_ftch['username'];
            $fname = $user_ftch['fname'];
            $lname = $user_ftch['lname'];
            $bday = $user_ftch['bday'];
            $new_bday = date_format(date_create($bday),"M. d, Y");
            $pnumber = $user_ftch['pnumber'];
            $sex = $user_ftch['sex'];
            $status = $user_ftch['status'];

            $user_id = $user_ftch['account_id'];
            $target_dir = "account/profile/user/";
            $target_file = $target_dir . $user_id . ".jpg";
            $pic_link = "https://hda-server.000webhostapp.com/app/".$target_file;

            if (file_exists($target_file)) {
                $img = $pic_link;
            }else if($sex == "Female") {
                $img = "https://hda-server.000webhostapp.com/app/images/profile_nurse_female.jpg";
            }else{
                $img = "https://hda-server.000webhostapp.com/app/images/profile_nurse_male.jpg";
            }

            echo "Success;$user_id;$username;$fname;$lname;$bday;$new_bday;$pnumber;$sex;$status;$img;none;none";
        }else if($type == "Family"){
            $user_sql = mysqli_query($conn,"SELECT * FROM user_info, family_info, family_member WHERE user_info.special_num = '101719' AND user_info.position_id = family_info.family_id AND family_member.fam_mem_id = family_info.fam_mem_id AND (user_info.username LIKE '$search' OR family_member.fname LIKE '$search' OR family_member.lname LIKE '$search' OR family_info.family_id='$search') ORDER BY family_member.fname LIMIT $data,1");
            $user_ftch = mysqli_fetch_array($user_sql);
            $user_row = mysqli_num_rows($user_sql);
            $username = $user_ftch['username'];
            $fname = $user_ftch['fname'];
            $lname = $user_ftch['lname'];
            $bday = $user_ftch['bday'];
            $new_bday = date_format(date_create($bday),"M. d, Y");
            $pnumber = $user_ftch['pnumber'];
            $sex = $user_ftch['sex'];
            $status = $user_ftch['status'];

            $user_id = $user_ftch['account_id'];
            $target_dir = "account/profile/user/";
            $target_file = $target_dir . $user_id . ".jpg";
            $pic_link = "https://hda-server.000webhostapp.com/app/".$target_file;

            if (file_exists($target_file)) {
                $img = $pic_link;
            }else{
                $img = "https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcTayT6TO8auGABi8G6HesSJYLLs9zKzUxmAKg&usqp=CAU";
            }
            
            $brgy = $user_ftch['fam_brgy'];
            $add_add = $user_ftch['fam_add_add'];

            echo "Success;$user_id;$username;$fname;$lname;$bday;$new_bday;$pnumber;$sex;$status;$img;$brgy;$add_add";
        }

}else{
    echo "Your account have a Problem\nContact Support";
}

}else{
echo "Unauthorized Request";
}