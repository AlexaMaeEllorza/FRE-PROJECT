<?php
$host = 'localhost';
$db = 'rice_inventory_db';
$user = 'root'; // change if you set a custom user
$pass = '';     // change if your MySQL has a password

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>