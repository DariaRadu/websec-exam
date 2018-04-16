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
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

/* SHA512 HASHING
$salt = base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM));
$password = $userPass;
$hashed_password = hash("sha512", $password."palacsintA".$salt);
echo $hashed_password;
*/


//Getting user data from database

$retrieveUsers = $conn->prepare("SELECT password FROM youconnect.users WHERE email = :inputEmail ");
$retrieveUsers->bindParam(':inputEmail', $userEmail, PDO::PARAM_STR, 255);

$retrieveUsers->execute();

$users = $retrieveUsers->fetchAll();

//print_r($users[0]["password"]);


// temporary placeholder for correct login
$correctUserEmail= $users;


if ( count($users) > 0 ){
    //echo " existing user";
    $correctPassword = $users[0]["password"];

            if($hashed_password == $correctPassword)
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