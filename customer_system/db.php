<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = "localhost";
$user = "root";
$pass = "";
$db   = "customer_db"; 

try {
    $conn = new mysqli($host, $user, $pass, $db);
    

    $conn->set_charset("utf8mb4");

} catch (mysqli_sql_exception $e) {
    
    error_log($e->getMessage());
    exit("Can't connect to the database, make sure to check if database name is accurate " . $db);
}
?>