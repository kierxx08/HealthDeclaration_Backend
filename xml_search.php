<!DOCTYPE html>
<html>
<body>

<?php
$xml=simplexml_load_file("movies_list.xml") or die("Error: Cannot create object");
echo $xml->movie->Ratings . "<br>";
?>

</body>
</html>