<?php

//SESSION TIMEOUT 
$time = $_SERVER['REQUEST_TIME'];

/**
* for a 30 minute timeout, specified in seconds
*/
$timeout_duration = 10;

/**
* Here we look for the user's LAST_ACTIVITY timestamp. If
* it's set and indicates our $timeout_duration has passed,
* blow away any previous $_SESSION data and start a new one.
*/
if (isset($_SESSION['LAST_ACTIVITY']) && 
   ($time - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    session_start();
}

/**
* Finally, update LAST_ACTIVITY so that our timeout
* is based on it and not the user's login time.
*/
$_SESSION['LAST_ACTIVITY'] = $time;

//include
include_once "include.php";
//DATABASE CONNECTION
include "db.php";

session_start();
if (isset($_SESSION['id'])){
    $loggedAccountId = $_SESSION['id'];
    //echo $loggedAccountId;
}

$warnings="";
// user data received from login

if (isset($_POST['txtUserEmail'])&& isset($_POST['txtUserPassword'])){
    if (!isset($_POST["csrf_token"]) || $_SESSION["csrf_token"]!=$_POST["csrf_token"])
    {
        echo "Security error.";
        exit();
    }
    $userEmail = $_POST['txtUserEmail'];
    $userPass = $_POST['txtUserPassword'];
    // hashing the input password

    $password = $userPass;

    $peber= "uvzj38fgFefVAf2!?1";
    $p_password = $userPass;
    $hashed_password = password_hash($p_password.$peber,PASSWORD_DEFAULT);



    //Getting user data from database

    $retrieveUsers = $conn->prepare("SELECT password, id FROM youconnect.users WHERE email = :inputEmail ");
    $retrieveUsers->bindParam(':inputEmail', $userEmail, PDO::PARAM_STR, 255);

    $retrieveUsers->execute();

    $users = $retrieveUsers->fetchAll();



    // temporary placeholder for correct login
    $correctUserEmail= $users;





    // CAPTCHA LOGIN

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
                    if ( count($users) > 0 ){
                        echo " existing user";
                        $correctPassword = $users[0]["password"];
                        // verifying the hashed password
                        $hashed_password_correct = password_verify($p_password.$peber, $correctPassword);
                                if($hashed_password_correct == $correctPassword)
                                    {
                                    echo "Success: you are now logged in";
                                    $userId = $users[0]["id"];
                                    $_SESSION['id']=$userId;
                                    header("Location: posts.php");
                                    exit();  
                                /*  //adding the user ID to the session with encryption
                                    
                                    $secret_message= $userId;

                                    // using a "fort knox" lvl of password generated from randomkeygen.com for security purposes
                                    $secret_key="HoL]Y2tgJOF-V.$?URB7a/6*gO7:C,";

                                    
                                    $iv_len=openssl_cipher_iv_length("aes-256-cbc");
                                    $iv=openssl_random_pseudo_bytes($iv_len);

                                    $secret_id = openssl_encrypt($secret_message,"aes-256-cbc",$secret_key,OPENSSL_RAW_DATA,$iv);
                                    $_SESSION['id'] = $secret_id;
                                    echo $secret_id;
                                    echo $_SESSION['id'];

                                    $output = openssl_decrypt($secret_id, 'AES-256-CBC', $secret_key, OPENSSL_RAW_DATA, $iv);
                                    
                                    echo "This id is".$output; */


                                    
                                    }
                                    else
                                    {
                                        $warnings="<p>Email and/or password incorrect.</p>";
                                    };
                                }
                                    else
                    {$warnings="<p>Email and/or password incorrect.</p>";};
                }
            }
//csrf token
$_SESSION["csrf_token"]=hash("sha256",rand()."1y=gNjFK5e[-8>-");

gen_header();
nav(0);
?>

<div id="loginPage" class="row" >
    <div id="loginLeftSide" class="col s6">
        
        <div id="introduction" class="introText">
            <!-- <img class="groupPhoto" src="graphics/group-photo.png" alt="logo"> -->
            <h5>Hello! Welcome to the YouConnect Community!</h5>
            <p>On this forum, you will be able to contact other youtubers and extend
                you own network.</p>
        </div>

    </div>
        


    <div id="loginRightSide" class="col s4">

        
        
        <!-- <img class="loginLogo" src="graphics/youconnect.png" alt="logo"> -->   

            <form method="post" id="frmLogin" class="col s12 form">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"] ?>">
                <div class="row">
                        <div class="input-field col s12">
                            <input name="txtUserEmail" id="divUserInput" type="text" class="validate">
                            <label for="first_name">Insert Email</label>
                        </div>
                </div>

                <div class="row">
                        <div class="input-field col s12">
                            <input name="txtUserPassword" id="divPasswordInput" type="password" class="validate">
                            <label for="first_name">Insert Password</label>
                        </div>
                </div>

                <div class="captcha_wrapper">
		            <div class="g-recaptcha" data-sitekey="6LcfkFMUAAAAAIeG1FJdjlggLsMa6tpd1Npc0ulq"></div>
	            </div>
        

                <button id="btnLogin" class="btn btn-general mainpagebuttons btnLoginNew waves-effect waves-light" type="submit" name="action">Login
                        <i class="material-icons right">send</i>
                </button>
            
            </form>
            <?php
                echo $warnings;
            ?>
        </div>
    

</div>


<?php

footer();

?>
