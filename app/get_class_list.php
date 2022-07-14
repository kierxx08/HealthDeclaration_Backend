<?php
require "assets/remotesql.php";

if(isset($_POST["account_id"]) && isset($_POST["device_id"])){
    $account_id = $_POST["account_id"];
    $device_id = $_POST["device_id"];

    $account_device_query = mysqli_query($conn,"SELECT * FROM `login_logs` WHERE account_id='$account_id' AND device_id='$device_id'");
    if(mysqli_num_rows($account_device_query)>0){
        $account_query = mysqli_query($conn,"SELECT * FROM `login_info` WHERE account_id='$account_id'");
        $account_fetch=mysqli_fetch_array($account_query);
        $account_type = $account_fetch['type'];

        if(mysqli_num_rows($account_query)==1){

            if($account_type=='teacher'){
                $sql = mysqli_query($conn,"SELECT * FROM `class_info` WHERE account_id='$account_id' AND status='active'");
                $img_url = null;
            }else{
                //$cs_con_sql = mysqli_query($conn,"SELECT * FROM `class_student_con` WHERE account_id='$account_id' AND status='accepted'");
                //$cs_con_fetch=mysqli_fetch_array($cs_con_sql);
                //$class_id = $cs_con_fetch['class_id'];
                //$sql = mysqli_query($conn,"SELECT * FROM `class_info` WHERE class_id='$class_id' AND status='active'");
                $sql = mysqli_query($conn,"SELECT * FROM `class_student_con` INNER JOIN `class_info` WHERE class_student_con.account_id='$account_id' AND class_student_con.status='accepted' AND class_info.class_id = class_student_con.class_id ");
                
                
                $img_url = "https://atm-bsumalvar.000webhostapp.com/app/user_profile/21031696593.png";
            }

            $rows = mysqli_num_rows($sql);
            if($rows>0){
            $i = 1;

        echo '
        {  "error":"false",
            "class":[';
            while($fetch=mysqli_fetch_array($sql)){
                echo '
                    {
                    "class_id":"'.$fetch['class_id'].'",
                    "class_name":"'.$fetch['class_name'].'",
                    "section":"'.$fetch['section'].'",
                    "subject":"'.$fetch['subject'].'",
                    "room":"'.$fetch['room'].'",
                    "img_url":"'.$img_url.'"
                }
                ';

            if($i<$rows){
                echo ',';
            }
                $i += 1;
            }
            
        echo ']}';
        }else{
            $myObj = new \stdClass();
            $myObj->error = true;
            $myObj->error_desc = "No Data";
            echo json_encode($myObj);
        }
    }else{
        echo "Account is not valid";
    }
}else{
    echo "Account and Device not matched";
}
}else{
    echo "Unauthorized Request";
}

?>