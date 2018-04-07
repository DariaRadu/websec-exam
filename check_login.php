<?php

//DATABASE CONNECTION
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "youconnect";

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

//Getting user data





//withabigDaria said i should  document this


// user data received from login

$userName = $_POST['txtUserName'];
$userPass = $_POST['txtUserPassword'];

// temporary placeholder for correct login
$correctUserName = "Adam";
$correctPassword = "123";

if ($correctUserName == $userName){
            if($userPass == $correctPassword)
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