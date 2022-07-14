<?php
require "assets/conn.php";
$family_id = $_GET['family_id'];

echo '
[  ';

$sqlCovid = mysqli_query($conn,"SELECT *
FROM family_member
WHERE fam_id = '$family_id' ORDER BY FIELD(pitf, 'Mother', 'Father') DESC");
$num_case = mysqli_num_rows($sqlCovid);
$i = 1;


while($case_fetch=mysqli_fetch_array($sqlCovid)){
	$case_id = $case_fetch['fname']." ".$case_fetch['lname'];

	$case_brgy_name = $case_fetch['pitf'];
	$case_detected = date("M d, Y",$case_fetch['date']);

	echo '
	{  
      "case_id":"'.$case_id.'",
      "url":"'.$case_detected.'",
      "case_brgy":"'.$case_brgy_name.'",
      "case_image":"https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcTayT6TO8auGABi8G6HesSJYLLs9zKzUxmAKg&usqp=CAU"
   }';

   if($i<$num_case){
   	echo ',';
   }
   $i += 1;
}
echo ']';

?>