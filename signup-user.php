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
$response = $_POST["g-recaptcha-response"];

//HASHING THE INPUT PASSWORD

$password = $txtPassword;

$peber = "uvzj38fgFefVAf2!?1";
$p_password = $password;
$hashed_password = password_hash($p_password.$peber,PASSWORD_DEFAULT);

//CAPTCHA 2.0

$url = 'https://www.google.com/recaptcha/api/siteverify';
	$data = array(
		'secret' => '6LcfkFMUAAAAAFjGG9bRR17M2YQaHDU60nhFCqpC',
		'response' => $_POST["g-recaptcha-response"]
	);
	$options = array(
		'http' => array (
			'method' => 'POST',
			'content' => http_build_query($data)
		)
	);
	$context  = stream_context_create($options);
	$verify = file_get_contents($url, false, $context);
	$captcha_success=json_decode($verify);
	if ($captcha_success->success==false) {
		echo "<p>You are a bot! Go away!</p>";
	} else if ($captcha_success->success==true) {

        $insertUserStmt=$conn->prepare("INSERT INTO users (first_name, last_name, email, password, channel) VALUES (:first_name, :last_name, :email, :password, :channel);");
        $insertUserStmt->bindParam(':first_name', $txtFirstName, PDO::PARAM_STR, 45);
        $insertUserStmt->bindParam(':last_name', $txtFirstName, PDO::PARAM_STR, 45);
        $insertUserStmt->bindParam(':email', $txtEmail, PDO::PARAM_STR, 255);
        $insertUserStmt->bindParam(':password', $hashed_password, PDO::PARAM_STR, 255);
        $insertUserStmt->bindParam(':channel', $urlChannel, PDO::PARAM_STR, 255);
        $insertUserStmt->execute();
		echo "<p>You are not not a bot!</p>";
	}

//preparing statement


?>