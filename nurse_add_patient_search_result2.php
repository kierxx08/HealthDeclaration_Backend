<?php
require "assets/conn.php";
$search = $_GET['search'];

echo '
[  ';

$sqlCovid = mysqli_query($conn,"SELECT * FROM `family_member` WHERE fam_id = '$search'");
$num_case = mysqli_num_rows($sqlCovid);
$i = 1;
while($case_fetch=mysqli_fetch_array($sqlCovid)){
	$case_id = $case_fetch['fname']." ".$case_fetch['lname'];

	$case_brgy_name = $case_fetch['pitf'];
   $case_detected = date("M d, Y",$case_fetch['date']);

   if($case_brgy_name=="Father"){
      $img = "https://cdn0.iconfinder.com/data/icons/avatars-icons/110/avatar_profile_face_father_husband_man_mustache-512.png";
   }else if($case_brgy_name=="Mother"){
      $img = "https://cdn0.iconfinder.com/data/icons/avatars-icons/110/avatar_profile_face_Redhead_woman_mother_wife-512.png";
   }else if($case_brgy_name=="Son"){
      $img = "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTIGq5Oq67C0aA4ppNbXuaOzw-38WfscDpI4Q&usqp=CAU";
   }else if($case_brgy_name=="Daughter"){
      $img = "https://image.flaticon.com/icons/png/512/146/146005.png";
   }


	echo '
	{  
      "case_id":"'.$case_id.'",
      "url":"'.$case_detected.'",
      "case_brgy":"'.$case_brgy_name.'",
      "case_image":"'.$img.'"
   }';

   if($i<$num_case){
   	echo ',';
   }
   $i += 1;
}
echo ']';
?>