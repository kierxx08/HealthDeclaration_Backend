<?php
require "assets/remotesql.php";

if(isset($_POST["class_id"]) && isset($_POST["get"]) && isset($_POST["account_id"]) && isset($_POST["device_id"])){
    $class_id = $_POST["class_id"];
    $get = $_POST["get"];
    $account_id = $_POST["account_id"];
    $device_id = $_POST["device_id"];
    $cur_timestamp = time();



    $account_device_query = mysqli_query($conn,"SELECT * FROM `login_logs` WHERE account_id='$account_id' AND device_id='$device_id'");

    if(mysqli_num_rows($account_device_query)>0){
        $account_query = mysqli_query($conn,"SELECT * FROM `login_info` WHERE account_id='$account_id'");
        $account_fetch=mysqli_fetch_array($account_query);
        $account_type = $account_fetch['type'];

        if(strpos($get, 'total') !== false){
            if ($account_type=="teacher"){
                $sql = mysqli_query($conn,"SELECT * FROM `attendance_info` WHERE class_id='$class_id' ORDER BY start DESC");
            }else{
                $sql = mysqli_query($conn,"SELECT * FROM `attendance_info` WHERE class_id='$class_id' AND start<$cur_timestamp ORDER BY start DESC");
            }
            $rows = mysqli_num_rows($sql);
            echo $rows;

        }else if(mysqli_num_rows($account_query)==1){
            
            $start = $_POST["start"];

            //$sql = mysqli_query($conn,"SELECT * FROM `attendance_info` WHERE class_id='$class_id' ORDER BY start DESC LIMIT $start, 20");
            /*$sql = mysqli_query($conn,"(SELECT * FROM `attendance_info` WHERE start>$cur_timestamp AND end>$cur_timestamp ORDER BY start DESC
                    UNION
                    SELECT * FROM `attendance_info` WHERE start<$cur_timestamp AND end>$cur_timestamp
                    UNION
                    SELECT * FROM `attendance_info` WHERE start<$cur_timestamp AND end<$cur_timestamp) LIMIT $start, 20");
            */
            if ($account_type=="teacher"){
                $sql = mysqli_query($conn,"(SELECT *, 1 as status FROM `attendance_info` WHERE start>$cur_timestamp AND end>$cur_timestamp AND class_id='$class_id'
                        UNION 
                        SELECT *, 2 as status FROM `attendance_info` WHERE start<$cur_timestamp AND end>$cur_timestamp AND class_id='$class_id'
                        UNION
                        SELECT *, 3 as status FROM `attendance_info` WHERE start<$cur_timestamp AND end<$cur_timestamp AND class_id='$class_id') 
                        ORDER BY status ASC, start DESC LIMIT $start, 20");
            }else{
                $sql = mysqli_query($conn,"(SELECT *, 1 as status FROM `attendance_info` WHERE start<$cur_timestamp AND end>$cur_timestamp AND class_id='$class_id'
                        UNION
                        SELECT *, 2 as status FROM `attendance_info` WHERE start<$cur_timestamp AND end<$cur_timestamp AND class_id='$class_id') 
                        ORDER BY status ASC, start DESC LIMIT $start, 20");
            }
            $rows = mysqli_num_rows($sql);
            

            if($rows>0){

                $i = 1;



                echo '

                {  "error":"false",

                    "attendance":[';

                    while($fetch=mysqli_fetch_array($sql)){
                        $start = $fetch['start'];
                        $end = $fetch['end'];

                        $start_year = date("Y", $start); 
                        $start_month = date("F", $start); 
                        $start_day = date("d", $start); 

                        $end_year = date("Y", $end); 
                        $end_month = date("F", $end); 
                        $end_day = date("d", $end); 


                        if($start_year == $end_year){
                            if($start_month == $end_month){
                                if($start_day == $end_day){
                                    $title_txt = date("F d, Y", $start);

                                    $month_txt = strtoupper(date("M", $start));
                                    $day_txt = date("d", $start);

                                    $start_txt = date("h:i a", $start);
                                    $end_txt = date("h:i a", $end);
                                }else{
                                    $title_txt = date("F d", $start)." - ".date("d, Y", $end);

                                    $month_txt = strtoupper(date("M", $start));
                                    $day_txt = date("d", $start);

                                    $start_txt = date("F d, Y h:i a", $start);
                                    $end_txt = date("F d, Y h:i a", $end);
                                }
                            }else{
                                $title_txt = date("F d ", $start) . " - ". date("F d, Y", $end);

                                $month_txt = strtoupper(date("M", $start));
                                $day_txt = date("d", $start);

                                $start_txt = date("F d, Y h:i a", $start);
                                $end_txt = date("F d, Y h:i a", $end);
                            }

                        }else{
                            $title_txt = date("F d, Y", $start) . " - ". date("F d, Y", $end);

                            $month_txt = strtoupper(date("M", $start));
                            $day_txt = date("d", $start);

                            $start_txt = date("F d, Y h:i a", $start);
                            $end_txt = date("F d, Y h:i a", $end);
                        }


                        echo '

                            {

                            "attendance_id":"'.$fetch['attendance_id'].'",

                            "title":"'.$title_txt.'",

                            "color":"'.get_color($start_day).'",

                            "month":"'.$month_txt.'",

                            "day":"'.$day_txt.'",

                            "start":"'.$start_txt.'",

                            "end":"'.$end_txt.'",

                            "edited":"'.$fetch['edited'].'",

                            "status":"'.$fetch['status'].'",

                            "date":"'.$fetch['date'].'"

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

    }else{
        echo "Account and Device not matched";
    }
}else{

    echo "Unauthorized Request";

}



function get_color($number) { 

    if($number == "01"){
        $color = "#3E7DCC";
    }else if($number == "02"){
        $color = "#000000";
    }else if($number == "03"){
        $color = "#00C8C8";
    }else if($number == "04"){
        $color = "#F9D84A";
    }else if($number == "05"){
        $color = "#8CC0FF";
    }else if($number == "06"){
        $color = "#4D525A";
    }else if($number == "07"){
        $color = "#202020";
    }else if($number == "08"){
        $color = "#C5D7C0";
    }else if($number == "09"){
        $color = "#FB8E7E";
    }else if($number == "10"){
        $color = "#7E909A";
    }else if($number == "11"){
        $color = "#8EC9BB";
    }else if($number == "12"){
        $color = "#F2CF58";
    }else if($number == "13"){
        $color = "#488A99";
    }else if($number == "14"){
        $color = "#6AB187";
    }else if($number == "15"){
        $color = "#0091D5";
    }else if($number == "16"){
        $color = "#EA6A47";
    }else if($number == "17"){
        $color = "#D32D41";
    }else if($number == "18"){
        $color = "#20283E";
    }else if($number == "19"){
        $color = "#1F3F49";
    }else if($number == "20"){
        $color = "#A5D8DD";
    }else if($number == "21"){
        $color = "#DBAE58";
    }else if($number == "22"){
        $color = "#484848";
    }else if($number == "23"){
        $color = "#AC3E31";
    }else if($number == "24"){
        $color = "#4CB5F5";
    }else if($number == "25"){
        $color = "#23282D";
    }else if($number == "26"){
        $color = "#B3C100";
    }else if($number == "27"){
        $color = "#CED2CC";
    }else if($number == "28"){
        $color = "#1C4E80";
    }else  if($number == "29"){
        $color = "#F8CA9D";
    }else if($number == "30"){
        $color = "#FA6E4F";
    }else{
        $color = "#8F9CB3";
    }

    return $color;
}

?>