<?php

if(isset($_POST['account_id']) && isset($_POST['from'])){
    $account_id = $_POST["account_id"];
    $from = $_POST['from'];
    
    if($from=="user"){
        $target_dir = "account/profile/user/";
        $target_file = $target_dir . $profile_id . ".jpg";
        $pic_link = "https://hda-server.000webhostapp.com/".$target_file;

        if (file_exists($target_file)) {
            echo "Found;$pic_link";
        }
    }else{
        echo "No Result;$pic_link";
    }

    
}else{
    echo "Unauthorized Request";
}

?>