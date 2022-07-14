<?php
require "assets/conn.php";

if(isset($_POST["username"]) && isset($_POST["psw"]) && isset($_POST["device_key"])){

    $username= $_POST["username"];
    $password= $_POST["psw"];
    $phone_key= $_POST["device_key"];

    if($conn){
    		$sqlCheckUsername = "SELECT * FROM `user_info` WHERE username='$username'";
	    	$usernameQuery = mysqli_query($conn,$sqlCheckUsername);

	    	if (mysqli_num_rows($usernameQuery)>0) {
		    	$sqlLogin = "SELECT * FROM `user_info` WHERE username='$username' AND password='$password'";
                $loginQuery = mysqli_query($conn,$sqlLogin);
               
			    if (mysqli_num_rows($loginQuery)>0) {
                    $row_login=mysqli_fetch_array($loginQuery);

                    $user_acc_id = $row_login['account_id'];
                    $user_special_num = $row_login['special_num'];
                    $user_status = $row_login['status'];
                    
                    if($user_status=="verified"){
                        $brgy = "none";
                        $add_add = "none";
                        $pitf = "none";

                        if($user_special_num=="101719"){
                            $query = mysqli_query($conn,"SELECT *
                            FROM user_info, family_info, family_member
                            WHERE user_info.position_id = family_info.family_id
                            AND family_info.fam_mem_id = family_member.fam_mem_id AND user_info.account_id = '$user_acc_id'");
                            $fetch=mysqli_fetch_array($query);
                            $brgy = $fetch['fam_brgy'];
                            $add_add = $fetch['fam_add_add'];
                            $pitf = $fetch['pitf'];
                        }else if($user_special_num=="101720"){
                            $query = mysqli_query($conn,"SELECT *
                            FROM user_info, admin_info
                            WHERE user_info.position_id = admin_info.admin_id
                            AND user_info.account_id = '$user_acc_id'");
                            $fetch=mysqli_fetch_array($query);
                        }else if($user_special_num=="101721"){
                            $query = mysqli_query($conn,"SELECT *
                            FROM user_info, doctor_info
                            WHERE user_info.position_id = doctor_info.doctor_id
                            AND user_info.account_id = '$user_acc_id'");
                            $fetch=mysqli_fetch_array($query);
                        }else if($user_special_num=="101722"){
                            $query = mysqli_query($conn,"SELECT * FROM user_info, nurse_info WHERE user_info.position_id = nurse_info.nurse_id AND user_info.account_id = '$user_acc_id'");
                            $fetch=mysqli_fetch_array($query);
                        }
                            if(mysqli_num_rows($query)){
                            $id = $fetch['account_id'];
                            $fname = $fetch['fname'];
                            $lname = $fetch['lname'];
                            $bday = $fetch['bday'];
                            $pnumber = $fetch['pnumber'];
                            $sex = $fetch['sex'];
                            $position = $fetch['position_id'];
                            
                            mysqli_query($conn,"update device_info set account_id='$id' where device_id='$phone_key'");

                            echo "Login Success;$user_special_num;$id;$fname;$lname;$bday;$pnumber;$sex;$brgy;$add_add;$pitf;$position";
                            }else{
                                echo 'No Result';
                            }
                        
                    }else{
                        echo $user_status;
                    }
					
			    }else{
				    echo "Wrong Password";
			    }


		    }else{
			    echo "This Username is not Registered";
		    }

    }else{
	    echo "Connection Error";
    }

}else{
	echo "Unauthorized Request";
}
?>
