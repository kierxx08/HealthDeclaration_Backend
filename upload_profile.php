<?php 
require "assets/conn.php";
 
if(isset($_FILES['pic']['name']) && isset($_POST['profile_id']) && isset($_POST['from'])){

    $profile_id = $_POST['profile_id'];
    $from = $_POST['from'];
    $profile_pic = $_FILES['pic']['name'];
    
    if($from=="user"){
        $target_dir = "account/profile/user/";
    }

    
    $target_file = $target_dir . $profile_id . ".jpg";
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["pic"]["tmp_name"]);
    if($check !== false) {
    }else{
        $response['error'] = true;
        $response['message'] = 'File is not an image.';
        $uploadOk = 0;
    }

    /*
    // Check if file already exists
    if (file_exists($target_file)) {
    $response['error'] = true;
    $response['message'] = 'Sorry, file already exists.';
    $uploadOk = 0;
    }
    */
    // Check file size
    if ($_FILES["pic"]["size"] > 500000) {
    $response['error'] = true;
    $response['message'] = 'Sorry, your file is too large.';
    $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
    $response['error'] = true;
    $response['message'] = 'Sorry, only JPG, JPEG & PNG files are allowed.';
    $uploadOk = 0;
    }

    // if everything is ok, try to upload file
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["pic"]["tmp_name"], $target_file)) {
            
            //pushing the array in response 
                $response['error'] = false;
                $response['message'] = "Success";

        } else {
            $response['error'] = true;
            $response['message'] = 'Sorry, there was an error uploading your file.';
        }
    }

    echo json_encode($response);
}else{
    echo "<h1>404 Not Found</h1>";
    echo "The page that you have requested could not be found.";
}

?>