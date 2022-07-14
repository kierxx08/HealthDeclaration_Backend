<?php

function get_student_full_name($conn,$account_id) {
    $query = mysqli_query($conn,"SELECT * FROM `student_info` WHERE account_id='$account_id'");
    $fetch = mysqli_fetch_array($query);
    return $fetch['fname'].' '.$fetch['lname'];
}
?>