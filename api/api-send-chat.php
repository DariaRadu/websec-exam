<?php

session_start();
include "db.php";

//include
include "include.php";


//CHECK SESSION
if (isset($_SESSION['id'])){
    $loggedIn=$_SESSION['id'];
}else{
    $loggedIn=0;
    header('Location: /');
}

if (!isset($_POST["csrf_token"]) || $_SESSION["csrf_token"]!=$_POST["csrf_token"])
		{
			echo "Security error.";
			exit();
		}
    $newMessage = $_POST["message"];
    $userMessaging = $_SESSION["id"];
    $insertMessage=$conn->prepare("INSERT INTO chat (user_id, message) VALUES (:userId, :message);");
    $insertMessage->bindParam(':userId', $userMessaging, PDO::PARAM_INT);
    $insertMessage->bindParam(':message', $newMessage, PDO::PARAM_STR, 300);
    $insertMessage->execute();
}