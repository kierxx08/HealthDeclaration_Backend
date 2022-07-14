<?php
require "assets/conn.php";

if(isset($_POST['account_id'])&&isset($_POST['data'])&&isset($_POST['from'])){
    $account_id = $_POST['account_id'];
    $data = $_POST['data'];
    $from = $_POST['from'];

    $doctor_query = mysqli_query($conn,"SELECT * FROM `user_info` WHERE account_id='$account_id'");
    $doctor_fetch = mysqli_fetch_array($doctor_query);
    $doctor_status = $doctor_fetch['status'];
    $doctor_id = $doctor_fetch['position_id'];

        //check if doctor is verified
        if($doctor_status=="verified"){
            
            if($from=="davpr"){

                //$sqlCovid = mysqli_query($conn,"SELECT *
                //FROM family_info, family_member
                //WHERE family_info.fam_mem_id = family_member.fam_mem_id AND ( family_member.fname LIKE '%$search%' OR family_member.lname LIKE '%$search%' OR family_info.family_id = '$search') ORDER BY family_info.family_id");

                $sqlCovid = mysqli_query($conn,"SELECT *
                FROM family_info, family_member, checkup_info 
                WHERE family_info.fam_mem_id = family_member.fam_mem_id AND family_member.fam_mem_id = checkup_info.fam_mem_id AND 
                checkup_info.status = 'queue' AND checkup_info.doctor_id='$doctor_id' ORDER BY convert(checkup_info.date, decimal) ASC LIMIT $data,1");
                $num_case = mysqli_num_rows($sqlCovid);

                if($num_case<=1){
                    $case_fetch=mysqli_fetch_array($sqlCovid);
                    $mem_id = $case_fetch['fam_mem_id'];
                    $name = $case_fetch['fname']." ".$case_fetch['lname'];
                    $from = new DateTime($case_fetch['bday']);
                    $to   = new DateTime('today');
                    $age = $from->diff($to)->y;
                    $checkup_id = $case_fetch['checkup_id'];
                    $height = $case_fetch['height'];
                    $weight = $case_fetch['weight'];
                    $temp = $case_fetch['temperature'];
                    $bp = $case_fetch['blood_pressure'];
                    $illness = $case_fetch['illness_info'];

                    echo "Success;$mem_id;$name;$age;$checkup_id;$height;$weight;$temp;$bp;$illness";
                }else{
                    echo "No Data";
                }
            
            }else{
                $sqlCovid = mysqli_query($conn,"SELECT *
                FROM family_info, family_member, checkup_info 
                WHERE family_info.fam_mem_id = family_member.fam_mem_id AND family_member.fam_mem_id = checkup_info.fam_mem_id AND 
                checkup_info.status = 'finish' AND (family_member.fname LIKE '%$search%' OR family_member.lname LIKE '%$search%') AND checkup_info.doctor_id='$doctor_id' ORDER BY convert(checkup_info.date, decimal) DESC LIMIT $data,1");
                $num_case = mysqli_num_rows($sqlCovid);
                
                if($num_case<=1){
                    $case_fetch=mysqli_fetch_array($sqlCovid);
                    $mem_id = $case_fetch['fam_mem_id'];
                    $name = $case_fetch['fname']." ".$case_fetch['lname'];
                    $from = new DateTime($case_fetch['bday']);
                    $to   = new DateTime('today');
                    $age = $from->diff($to)->y;
                    $checkup_id = $case_fetch['checkup_id'];
                    $height = $case_fetch['height'];
                    $weight = $case_fetch['weight'];
                    $temp = $case_fetch['temperature'];
                    $bp = $case_fetch['blood_pressure'];
                    $illness = $case_fetch['illness_info'];

                    echo "Success;$mem_id;$name;$age;$checkup_id;$height;$weight;$temp;$bp;$illness";
                }else{
                    echo "No Data";
                }
            }

        }else{
            echo "Your account has a Problem\nContact Support";
        }

}else{
	echo "Unauthorized Request";
}
?>