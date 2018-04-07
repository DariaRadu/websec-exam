<?php

//DATABASE CONNECTION
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "youconnect";

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

//GETTING DATA FROM FORM
$txtFirstName = $_POST['txtFirstName'];
$txtLastName = $_POST['txtLastName'];
$txtPassword = $_POST['txtPassword'];
$txtEmail = $_POST['txtEmail'];
$urlChannel = $_POST['urlChannel'];

//preparing statement
$insertUserStmt=$conn->prepare("INSERT INTO users (first_name, last_name, email, password, channel) VALUES (:first_name, :last_name, :email, :password, :channel);");
$insertUserStmt->bindParam(':first_name', $txtFirstName, PDO::PARAM_STR, 45);
$insertUserStmt->bindParam(':last_name', $txtFirstName, PDO::PARAM_STR, 45);
$insertUserStmt->bindParam(':email', $txtEmail, PDO::PARAM_STR, 255);
$insertUserStmt->bindParam(':password', $txtPassword, PDO::PARAM_STR, 255);
$insertUserStmt->bindParam(':channel', $urlChannel, PDO::PARAM_STR, 255);
$insertUserStmt->execute();

?>