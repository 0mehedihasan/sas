<?php
// dbcon.php
$host = "localhost:5222";
$user = "root";
$pass = "";
$dbs = [
    "sas_six",
    "sas_seven",
    "sas_eight",
    "sas_other"
];

// Array to hold the database connections
$conn = [];

// Create a connection for each database
foreach ($dbs as $dbName) {
    $dbConnection = new mysqli($host, $user, $pass, $dbName);
    
    // Check connection
    if ($dbConnection->connect_error) {
        die("Connection failed for $dbName: " . $dbConnection->connect_error);
    }

    // Store the connection in the array
    $conn[$dbName] = $dbConnection;
}
?>
