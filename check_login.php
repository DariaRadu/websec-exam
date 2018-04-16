<?php

//DATABASE CONNECTION
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "youconnect";

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

// user data received from login

$userEmail = $_POST['txtUserEmail'];
$userPass = $_POST['txtUserPassword'];

// hashing the input password

$password = $userPass;

$peber= "uvzj38fgFefVAf2!?1";
$p_password = $userPass;
$hashed_password = password_hash($p_password.$peber,PASSWORD_DEFAULT);



//Getting user data from database

$retrieveUsers = $conn->prepare("SELECT password FROM youconnect.users WHERE email = :inputEmail ");
$retrieveUsers->bindParam(':inputEmail', $userEmail, PDO::PARAM_STR, 255);

$retrieveUsers->execute();

$users = $retrieveUsers->fetchAll();



// temporary placeholder for correct login
$correctUserEmail= $users;


if ( count($users) > 0 ){
    //echo " existing user";
    $correctPassword = $users[0]["password"];
    // verifying the hashed password
    $hashed_password_correct = password_verify($p_password.$peber, $correctPassword);
            if($hashed_password_correct == $correctPassword)
                {
                echo "Success: you are now logged in";
                }
                else
                {
                    echo "Error: Wrong username or password";
                };
            }
                else
{echo "oh no";};

?>