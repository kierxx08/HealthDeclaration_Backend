<?php
require "assets/conn.php";
if(isset($_GET['access_token']) && isset($_GET['subscriber_number'])){
    $access_token = $_GET['access_token'];
    $subscriber_number = $_GET['subscriber_number'];
    $date = time();

    $query = "INSERT INTO `globe_labs_api`(`access_token`, `number`, `date`)
                                VALUES ('$access_token','$subscriber_number','$date')";
    
    if(mysqli_query($conn,$query)){
        echo "Success";
    }else{
        echo "Failed";
    }
}else{
	echo "Unauthorized Request";
}



?>