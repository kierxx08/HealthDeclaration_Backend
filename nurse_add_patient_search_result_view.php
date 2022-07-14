<?php
require "assets/conn.php";

if(isset($_POST["data"]) && isset($_POST["search"]) && isset($_POST["data2"]) && isset($_POST["nurse_id"])){
    $data=$_POST["data"];
    $search = $_POST['search'];
    $nurse_id=$_POST["nurse_id"];
    $data2=$_POST["data2"];

    $nurse_query = mysqli_query($conn,"SELECT * FROM `user_info` WHERE special_num='101722' AND account_id='$nurse_id'");
    $nurse_fetch = mysqli_fetch_array($nurse_query);
    $nurse_status = $nurse_fetch['status'];

    //check if nurse is verified
    if($nurse_status=="verified"){
        $sql_data = mysqli_query($conn,"SELECT *
FROM family_info, family_member
WHERE family_info.fam_mem_id = family_member.fam_mem_id AND ( ('$search' LIKE Concat(Concat('%',family_member.fname),'%') AND '$search' LIKE  Concat(Concat('%',family_member.lname),'%')) OR (family_member.fname LIKE '$search' OR family_member.lname LIKE '$search') OR family_info.family_id = '$search') ORDER BY family_info.family_id LIMIT $data,1");
        $row_data=mysqli_fetch_array($sql_data);
        $num_data=mysqli_num_rows($sql_data);
        $fam_id = $row_data['fam_id'];

        $sql_data2 = mysqli_query($conn,"SELECT * FROM `family_member` WHERE fam_id = '$fam_id' LIMIT $data2,1");
        $row_data2=mysqli_fetch_array($sql_data2);
        $num_data2=mysqli_num_rows($sql_data2);
        $from = new DateTime($row_data2['bday']);
        $to   = new DateTime('today');

        if(0<$num_data2){
            $mem_id = $row_data2['fam_mem_id'];
            $mem_fullname = $row_data2['fname']." ".$row_data2['lname'];
            $mem_add = $row_data['fam_add_add']." ".$row_data['fam_brgy'];
            $age = $from->diff($to)->y;

            echo "Success;$mem_id;$mem_fullname;$age;$mem_add";
        }else{
	        echo "No Data Found";
        }
    }else{
        echo "Account Problem";
    }
}else if( isset($_POST["mem_id"]) && isset($_POST["nurse_id"])){
    $mem_id=$_POST["mem_id"];
    $nurse_id=$_POST["nurse_id"];

    $nurse_query = mysqli_query($conn,"SELECT * FROM `user_info` WHERE special_num='101722' AND account_id='$nurse_id'");
    $nurse_fetch = mysqli_fetch_array($nurse_query);
    $nurse_status = $nurse_fetch['status'];

    //check if nurse is verified
    if($nurse_status=="verified"){

        $sql_data2 = mysqli_query($conn,"SELECT * FROM family_info, family_member WHERE family_info.family_id=family_member.fam_id AND family_member.fam_mem_id = '$mem_id'");
        $row_data2=mysqli_fetch_array($sql_data2);
        $num_data2=mysqli_num_rows($sql_data2);
        $from = new DateTime($row_data2['bday']);
        $to   = new DateTime('today');

        if(0<$num_data2){
            $mem_id = $row_data2['fam_mem_id'];
            $mem_fullname = $row_data2['fname']." ".$row_data2['lname'];
            $mem_add = $row_data['fam_add_add']." ".$row_data['fam_brgy'];
            $age = $from->diff($to)->y;

            echo "Success;$mem_fullname;$age;$mem_add";
        }else{
	        echo "No Data Found";
        }
    }else{
        echo "Account Problem";
    }
    
}else{
	echo "Unauthorized Request";
}

?>