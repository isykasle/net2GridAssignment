<?php

include 'db_connection.php';

function createTable(){
$conn = OpenCon();


// sql code to create table
$sql = "CREATE TABLE theValues(
    id INT AUTO_INCREMENT PRIMARY KEY, 
    aValue INT NOT NULL,
    aTimestamp INT NOT NULL
    )";

if ($conn->query($sql) === TRUE) {
echo "Table theValues created successfully";
} else {
echo "Error creating table: " . $conn->error;
}

CloseCon($conn);
}
?>