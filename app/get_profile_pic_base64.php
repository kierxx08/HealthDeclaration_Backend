<?php

if(isset($_POST["account_id"])){
    
    $account_id = $_POST["account_id"];
    $path = 'user_profile/'.$account_id.'.png';
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $data = base64_encode($data);
    echo $data;

}else{
    echo "Unauthorized Request";
}
?>