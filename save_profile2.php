<?php



if(isset($_POST["srcode"])){
    $srcode=$_POST['srcode'];
    
    $url = "https://dione.batstate-u.edu.ph/student/backend/public/index.php/data/api_get?service=acad%252Ffetch%252Facademic_records&srcode=$srcode";

    $json = file_get_contents($url);
    $json = json_decode($json);
    
    
    if (($json->personal != false && $srcode!=null)){
       
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://dione.batstate-u.edu.ph/student/backend/public/index.php/data/api_get?service=util%252Ffetch%252Fstudent%252Fphoto&id='.$srcode.'&r=1',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Cookie: PHPSESSID=091e2cb6074c55f47012e3ae20620c3a'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        
        //echo '<img src="' . $data . '" />';
        
        // Load the stamp and the photo to apply the watermark to
        $stamp = imagecreatefrompng('images/stamp_app.png');
        //$im = imagecreatefromjpeg('http://dione.batstate-u.edu.ph/public/sites/api/fetch_photo.php?id='.$srcodebase64.'');
        $data2 = explode( ',', $response );
        
        $data3 = base64_decode($data2[1]);
        
        if ($data2[1] != null) {
            $im = imagecreatefromstring($data3);
            
            
            // Set the margins for the stamp and get the height/width of the stamp image
            $marge_right = 0;
            $marge_bottom = 0;
            $sx = imagesx($stamp);
            $sy = imagesy($stamp);
            
            // Copy the stamp image onto our photo using the margin offsets and the photo 
            // width to calculate positioning of the stamp. 
            imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));
            
            $ifp = fopen( "$srcode.png", 'wb' ); 
            fwrite( $ifp, $data3);
            fclose( $ifp ); 

            // Output and free memory
            header('Content-type: image/png');
            imagepng($im);
            imagedestroy($im);
        
        }
    }else{
        $

        $im = imagecreatefrompng("images/no_profile.png");
    
        $stamp = imagecreatefrompng('images/stamp_app.png');
    
        // Set the margins for the stamp and get the height/width of the stamp image
        $marge_right = 0;
        $marge_bottom = 0;
        $sx = imagesx($stamp);
        $sy = imagesy($stamp);
        
        // Copy the stamp image onto our photo using the margin offsets and the photo 
        // width to calculate positioning of the stamp. 
        imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));
        
        $ifp = fopen( "$srcode.png", 'wb' ); 
        fwrite( $ifp, $data3);
        fclose( $ifp ); 

        // Output and free memory
        header('Content-type: image/png');
        imagepng($im);
        imagedestroy($im);
    }
}else{
	    echo "Unauthorized Request";
}
?>

function base64_to_jpeg($base64_string, $output_file) {
    // open the output file for writing
    $ifp = fopen( $output_file, 'wb' ); 

    // split the string on commas
    // $data[ 0 ] == "data:image/png;base64"
    // $data[ 1 ] == <actual base64 string>
    $data = explode( ',', $base64_string );

    // we could add validation here with ensuring count( $data ) > 1
    fwrite( $ifp, base64_decode( $data[ 1 ] ) );

    // clean up the file resource
    fclose( $ifp ); 

    return $output_file; 
}


?>