<?php

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "student_course_system";

    $conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$i = 0;
function unique_id() {
    
        $i = $i + 1;
    
    return $i;
 
}

?>


