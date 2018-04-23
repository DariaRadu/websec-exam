<?php
    //INCLUDE+DB CONNECTION
    include "db.php";
    include "include.php";
    
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    //GETTING DATA FROM FORM
    if($_POST){
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
                //preparing statement
                $insertUserStmt=$conn->prepare("INSERT INTO users (first_name, last_name, email, password, channel) VALUES (:first_name, :last_name, :email, :password, :channel);");
                $insertUserStmt->bindParam(':first_name', $txtFirstName, PDO::PARAM_STR, 45);
                $insertUserStmt->bindParam(':last_name', $txtFirstName, PDO::PARAM_STR, 45);
                $insertUserStmt->bindParam(':email', $txtEmail, PDO::PARAM_STR, 255);
                $insertUserStmt->bindParam(':password', $hashed_password, PDO::PARAM_STR, 255);
                $insertUserStmt->bindParam(':channel', $urlChannel, PDO::PARAM_STR, 255);
                $insertUserStmt->execute();
                echo "<p>You are not not a bot!</p>";
            }
    
    
    }
    
  
    gen_header();
?>

<div class="container container-signup">

    <form action='signup-user.php' method='post'>
        <input name='txtFirstName' type='text' placeholder="First name" required>
        <input name='txtLastName' type='text' placeholder="Last name" required>
        <input name='txtPassword' type='password' placeholder="Password" required>
        <input name='txtEmail' type='email' placeholder="email" required>
        <input name='urlChannel' type='text' placeholder="Link to Youtube Channel" required>
        <div class="captcha_wrapper">
		    <div class="g-recaptcha" data-sitekey="6LcfkFMUAAAAAIeG1FJdjlggLsMa6tpd1Npc0ulq"></div>
	    </div>
        <button  class="btn" type='submit'>Submit</submit>
    </form>
    
</div>
    
    <script src='https://www.google.com/recaptcha/api.js'></script>

</body>
</html>