<?php
require "assets/remotesql.php";

if(isset($_POST["account_id"]) && isset($_POST["device_id"]) && isset($_POST["get"])){
    $get = $_POST["get"];
    $class_id = $_POST["class_id"];
    $account_id = $_POST["account_id"];
    $device_id = $_POST["device_id"];

    $account_device_query = mysqli_query($conn,"SELECT * FROM `login_logs` WHERE account_id='$account_id' AND device_id='$device_id'");
    if(mysqli_num_rows($account_device_query)>0){
        $account_query = mysqli_query($conn,"SELECT * FROM `login_info` WHERE account_id='$account_id'");
        $account_fetch=mysqli_fetch_array($account_query);
        $account_type = $account_fetch['type'];

        if(mysqli_num_rows($account_query)==1){
            if(strpos($get, 'requesting') !== false){
                $status = "requesting";
                $order_by = "class_student_con.date DESC";
            }else if(strpos($get, 'accepted') !== false){
                $status = "accepted";
                $order_by = "student_info.fname  ASC";
            }else{
                $status = "none";
            }
            if(strpos($get, 'total') !== false){
                $sql = mysqli_query($conn,"SELECT student_info.fname, student_info.lname, class_student_con.date, student_info.account_id 
                FROM class_student_con, class_info, student_info 
                WHERE class_info.class_id = class_student_con.class_id AND class_student_con.account_id = student_info.account_id AND class_info.account_id = '$account_id' AND class_info.class_id = '$class_id' AND class_student_con.status = '$status'");
                $rows = mysqli_num_rows($sql);
                echo $rows;
            }else{
                $start = $_POST["start"];
                if($account_type=='teacher'){

                    $sql = mysqli_query($conn,"SELECT student_info.fname, student_info.lname, class_student_con.date, class_student_con.CScon_id, student_info.account_id 
                    FROM class_student_con, class_info, student_info 
                    WHERE class_info.class_id = class_student_con.class_id AND class_student_con.account_id = student_info.account_id AND class_info.account_id = '$account_id' AND class_info.class_id = '$class_id' AND class_student_con.status = '$status' ORDER BY $order_by LIMIT $start, 20");

                    $rows = mysqli_num_rows($sql);

                            
                    if($rows>0){
                        $i = 1;

                        echo '
                        {  "error":"false",
                            "students":[';
                            while($fetch=mysqli_fetch_array($sql)){
                                echo '
                                    {
                                    "CScon_id":"'.$fetch['CScon_id'].'",
                                    "account_id":"'.$fetch['account_id'].'",
                                    "name":"'.$fetch['fname'].' '.$fetch['lname'].'",
                                    "date":"'.date_func($fetch['date']).'",
                                    "img_url":"https://atm-bsumalvar.000webhostapp.com/app/user_profile/'.$fetch['account_id'].'.png"
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
                    $myObj = new \stdClass();
                    $myObj->error = true;
                    $myObj->error_desc = "Account is not valid";
                    echo json_encode($myObj);
                }
            }
        }else{
            $myObj = new \stdClass();
            $myObj->error = true;
            $myObj->error_desc = "Account is not valid";
            echo json_encode($myObj);
        }
    }else{
        $myObj = new \stdClass();
        $myObj->error = true;
        $myObj->error_desc = "Account and Device not matched";
        echo json_encode($myObj);
    }
}else{
    echo "Unauthorized Request";
}

function date_func($date) { 
  
	$date_now = date("Y-m-d H:i:s");

	$date_now_timestamp = strtotime("$date_now");
	//$date_sent_timestamp = strtotime("$date_sent");
	$date_timestamp = strtotime("$date");
	$between = $date_now_timestamp-$date_timestamp;

	$minutes = floor($between/60);
	$hours = floor($between/3600);
	$days = floor($between/86400);
	$weeks = floor($between/604800);
	$date_mdy = date("F d, Y", strtotime($date_now)); 


	if($between<3){
		$date_ago = "Just Now";
		return $date_ago;
	}else if($between<60){
		$date_ago = "$between seconds ago";
		return $date_ago;
	}else if($minutes==1){
		$date_ago = "$minutes minute ago";
		return $date_ago;
	}else if($minutes<60){
		$date_ago = "$minutes minutes ago";
		return $date_ago;
	}else if($hours==1){
		$date_ago = "$hours hour ago";
		return $date_ago;
	}else if($hours<24){
		$date_ago = "$hours hours ago";
		return $date_ago;
	}else if($days==1){
		$date_ago = "$days day ago";
		return $date_ago;
	}else if($days<7){
		$date_ago = "$days days ago";
		return $date_ago;
	}else if($weeks==1){
		$date_ago = "$weeks weeks ago";
		return $date_ago;
	}else if($between<2419200){
		$date_ago = "$weeks weeks ago";
		return $date_ago;
	}else{
		$date_ago = "$date_mdy";
		return $date_ago;
	}
}
?>