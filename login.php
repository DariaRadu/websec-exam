<?php

//include
include_once "include.php";
//DATABASE CONNECTION
include "db.php";

session_start();
if (isset($_SESSION['id'])){
    $loggedAccountId = $_SESSION['id'];
    //echo $loggedAccountId;
}


// user data received from login

if (!isset($_POST)){
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




    if ( count($users) > 0 ){
        //echo " existing user";
        $correctPassword = $users[0]["password"];
        // verifying the hashed password
        $hashed_password_correct = password_verify($p_password.$peber, $correctPassword);
                if($hashed_password_correct == $correctPassword)
                    {
                    echo "Success: you are now logged in";
                    $userId = $users[0]["id"];

                    //adding the user ID to the session with encryption
                    
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
                    
                    echo "This id is".$output;


                    
                    }
                    else
                    {
                        echo "Error: Wrong username or password";
                    };
                }
                    else
    {echo "oh no";};
}

gen_header();
nav();
?>

<div id="loginPage">
    <div id="loginLeftSide">
        
        <div id="introduction">
            <!-- <img class="groupPhoto" src="graphics/group-photo.png" alt="logo"> -->
            Hello! Welcome to YouConnect! 

        </div>

    </div>
        


    <div id="loginRightSide">

        
        
        <!-- <img class="loginLogo" src="graphics/youconnect.png" alt="logo"> -->   

            <form action="check_login.php" method="post" id="frmLogin" class="col s12 form">
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
        

                <button id="btnLogin" class="btn btn-general mainpagebuttons btnLoginNew waves-effect waves-light" type="button" name="action">Login
                        <i class="material-icons right">send</i>
                </button>
                <button id="btnLogOut" class="btn btn-general mainpagebuttons btnLoginNew waves-effect waves-light" type="button" name="action">Logout
                        <i class="material-icons right">send</i>
                </button>

                <!-- <button type="submit" id="btnLogIn">Login</button> -->
            
            </form>

        </div>
    


</div>


<?php

footer();

?>
