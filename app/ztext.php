<?php
require "assets/remotesql.php";

if(isset($_POST['start'])){
    $start = $_POST['start'];
    $end = $start+20;

    //fetch table rows from mysql db
    $query = mysqli_query($conn,"SELECT * FROM `zsample` LIMIT $start, 20");

    //create an array
    $emparray = array();
    while($row =mysqli_fetch_assoc($query))
    {
        $emparray[] = $row;
    }
    echo json_encode($emparray);
    
}else if(isset($_POST['total'])){
    $query = mysqli_query($conn,"SELECT * FROM `zsample`");
    $num = mysqli_num_rows($query);
    
    echo $num;
}


?>