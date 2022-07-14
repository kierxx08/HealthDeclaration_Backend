<?php
require "assets/conn.php";

if(isset($_POST["data"]) && isset($_POST["search"]) && isset($_POST["nurse_id"])){
    $data=$_POST["data"];
    $search = $_POST['search'];
    $nurse_id=$_POST["nurse_id"];

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

        if(0<$num_data){
            $fam_id = $row_data['fam_id'];

            echo "Success;$fam_id";
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