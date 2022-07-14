<?php

if(isset($_POST["app_info"])){

$maintenance = false;

$myObj = new \stdClass();
$myObj->maintenance = $maintenance;
$myObj->app_latest_version = "1.01";
$myObj->app_latest_description = "Updated as of:\nApril 01, 2021 11:55:00 am\n\nTeacher:\nCan now Add a Class\nView Pending and Accepted Students\nView Class Info\nSoon: Attendance and Notification\n\nStudent:\nCan now Join a Class";
$myObj->app_link = "https://atm-bsumalvar.000webhostapp.com/app/app_download.html";

//$myObj->app_latest_description = "Hi Eca.\nAng ganda niyo po.";
//$myObj->app_link = "https://m.facebook.com/erica.xvii/";

if($maintenance == true){
    $myObj->maintenance_desc = "Smashing Bugs Right Now";
}


$myJSON = json_encode($myObj);

echo $myJSON;
}else{
    echo "Unauthorized Request";
}

?>