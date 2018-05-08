<?php
    session_start();
    //INCLUDE+DB CONNECTION
    include "db.php";
    include "include.php";
    
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $warnings='';
    //profile pics folder
    $profilePicFolder='img/dp/';

    //GETTING DATA FROM FORM
    if($_POST){
        if (!isset($_POST["csrf_token"]) || $_SESSION["csrf_token"]!=$_POST["csrf_token"])
		{
			echo "Security Error";
			exit();
		}

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
                $warnings="<p>Captcha not verified, please try again.</p>";
            } else if ($captcha_success->success==true) {
                //FILE UPLOAD FIRST
                $sPathToPicture='';
                if (isset($_FILES['profilePic']['tmp_name'])){
                    /* echo $_FILES['profilePic']['tmp_name']; */
                    if(check_file_mime($_FILES['profilePic']['tmp_name'])){
                        $picturePath = $_FILES['profilePic']['name'];
                        $extension = pathinfo($picturePath, PATHINFO_EXTENSION);
                        $sProfilePicName = md5($picturePath).'.'.$extension;
                        $sPathToPicture = $profilePicFolder.$sProfilePicName;
                        move_uploaded_file( $_FILES['profilePic']['tmp_name'] , $sPathToPicture );
                    }else{
                        $warnings='Profile picture must be an image.';
                        exit();
                    } 
                }

                //preparing statement
                $insertUserStmt=$conn->prepare("INSERT INTO users (first_name, last_name, email, password, channel, profile_pic) VALUES (:first_name, :last_name, :email, :password, :channel, :profilePic);");
                $insertUserStmt->bindParam(':first_name', $txtFirstName, PDO::PARAM_STR, 45);
                $insertUserStmt->bindParam(':last_name', $txtLastName, PDO::PARAM_STR, 45);
                $insertUserStmt->bindParam(':email', $txtEmail, PDO::PARAM_STR, 255);
                $insertUserStmt->bindParam(':password', $hashed_password, PDO::PARAM_STR, 255);
                $insertUserStmt->bindParam(':channel', $urlChannel, PDO::PARAM_STR, 255);
                $insertUserStmt->bindParam(':profilePic', $sPathToPicture, PDO::PARAM_STR, 300);
                $insertUserStmt->execute();

                header("Location: /");
            }
    
    
    }
    
    //csrf token
    $_SESSION["csrf_token"]=hash("sha256",rand()."1y=gNjFK5e[-8>-");
    
    gen_header();
    nav(0);
?>

<div class="container container-signup">

    <form method='post' enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"] ?>">
        <input name='txtFirstName' type='text' placeholder="First name" required>
        <input name='txtLastName' type='text' placeholder="Last name" required>
        <input name='txtEmail' type='email' placeholder="email" required>
        <input name='txtPassword' type='password' placeholder="Password" required>
        <input name='urlChannel' type='text' placeholder="Link to Youtube Channel" required>
       <!--  <input name='profilePic' type='file' required> -->
       <div class="file-field input-field">
            <div class="btn btn-general">
                <span>Picture</span>
                <input type="file" name="profilePic">
            </div>
            <div class="file-path-wrapper">
                <input class="file-path validate" type="text">
            </div>
        </div>
        <div class="captcha_wrapper">
		    <div class="g-recaptcha" data-sitekey="6LcfkFMUAAAAAIeG1FJdjlggLsMa6tpd1Npc0ulq"></div>
	    </div>
        <button  class="btn btn-general" type='submit'>Submit</button>
    </form>
    <?php
        echo $warnings;
    ?>
</div>
    
<?php
    footer();
?>