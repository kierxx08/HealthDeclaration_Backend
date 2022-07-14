<?php
require "assets/conn.php";
if(isset($_POST["account_id"]) && isset($_POST["new_username"])){
    $account_id = $_POST["account_id"];
    $new_username = $_POST["new_username"];

    $user_query = mysqli_query($conn,"SELECT * FROM `user_info` WHERE account_id='$nurse_id'");
    $user_fetch = mysqli_fetch_array($user_query);
    $old_username = $nurse_fetch['username'];
    $user_status = $nurse_fetch['status'];

    //check if user is verified
    if($user_status=="verified"){
        if($new_username != $old_username){
            $number_query = mysqli_query($conn,"SELECT * FROM `user_info` WHERE username='$new_username'");
            if(mysqli_num_rows($family_query)<1){

                $update_username_query = "update user_info set username='$username' where account_id='$account_id'";
                if(mysqli_query($conn,$update_username_query)){
                    echo "Success";
                }else{
                    echo "Failed to Update";
                }
            }else{
                echo "We Found Duplicate Username";
            }
        }else{
            echo "Please choose other Username";
        }
    }else{
        echo "Your account have a Problem\nContact Support";
    }

}else{
echo "Unauthorized Request";
}

?>