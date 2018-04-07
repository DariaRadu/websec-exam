<?php

$userName = $_POST['txtUserName'];
$userPass = $_POST['txtUserPassword'];

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