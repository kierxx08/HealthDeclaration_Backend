<?php
require "assets/conn.php";
if(isset($_POST["account_id"]) && isset($_POST["old_password"]) && isset($_POST["new_password"])){
    $account_id = $_POST["account_id"];
    $old_password = $_POST["old_password"];
    $new_password = $_POST["new_password"];

    $user_query = mysqli_query($conn,"SELECT * FROM `user_info` WHERE account_id='$account_id'");
    $user_fetch = mysqli_fetch_array($user_query);
    $old_username = $user_fetch['username'];
    $user_pass= $user_fetch['password'];
    $user_status = $user_fetch['status'];
    
    //check if user is verified
    if($user_status=="verified"){
        if($old_password != $user_pass){
            if($old_password != $new_password){
                $number_query = mysqli_query($conn,"SELECT * FROM `user_info` WHERE username='$new_username'");
                

                    $update_username_query = "update user_info set password='$new_password' where account_id='$account_id'";
                    if(mysqli_query($conn,$update_username_query)){
                        echo "Success";
                    }else{
                        echo "Failed to Update";
                    }

            }else{
                echo "Use Different New Password";
            }
        }else{
            echo "You type wrong Current Password";
        }
    }else{
        echo "Your account have a Problem\nContact Support";
    }

}else{
echo "Unauthorized Request";
}

?>