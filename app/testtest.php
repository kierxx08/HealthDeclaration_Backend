<?php
require "assets/remotesql.php";


for($user=1;$user<=100;$user++){

    $user_ex = str_pad($user, 3, 0, STR_PAD_LEFT);
    $device_id = "user$user_ex";
    $gen_acc_ID = str_pad(rand(0, 99999999999), 11, 0, STR_PAD_LEFT);
    $url = "https://atm-bsumalvar.000webhostapp.com/app/images/no_profile.png";
    
        	    
    $sql_login_logs = "INSERT INTO `zsample` (`user`, `number`, `img`) VALUES ('$device_id','$gen_acc_ID', '$url')";
    if(mysqli_query($conn,$sql_login_logs)){
        echo "ok";
    }else{
        echo "not ok: $sql_login_logs";
    }
}
                    
?>