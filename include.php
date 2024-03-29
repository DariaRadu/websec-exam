<?php
// using a "fort knox" lvl of password generated from randomkeygen.com for security purposes
$secret_key="HoL]Y2tgJOF-V.$?URB7a/6*gO7:C,";


$iv_len=openssl_cipher_iv_length("aes-256-cbc");
$iv=openssl_random_pseudo_bytes($iv_len);

function gen_header() {
     //If the HTTPS is not found to be "on"
    if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
    {
        //Tell the browser to redirect to the HTTPS URL.
        header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
        //Prevent the rest of the script from executing.
        exit;
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <!-- <base href="/websec/websec-exam/"> -->
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
             <!-- Compiled and minified CSS -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
            <link rel="stylesheet" href="css/style.css" type="text/css">
            <title>YouConnect</title>
        </head>
        <body>
    <?php
}

function nav($loggedIn){

    if($loggedIn==0){
        ?>
        <nav>
            <div class="nav-wrapper">
            <a href="#" class="brand-logo">YouConnect</a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a href="/">Login</a></li>
                <li><a href="signup.php">Signup</a></li>
                <!-- <li><a href="posts.php">Timeline</a></li> -->
            </ul>
            </div>
        </nav>
        <?php
    }else{
        ?>
        <nav>
            <div class="nav-wrapper">
            <a href="#" class="brand-logo">YouConnect</a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a href="profile-page.php">Profile</a></li> 
                <li><a href="posts.php">Timeline</a></li>
                <!-- <li><a href="chat.php">Open Chat</a></li> -->
                <li><a href="logout.php">Logout</a></li>  
            </ul>
            </div>
        </nav> 
        <?php
    }
    
}

function footer(){
    ?>
        <!-- Compiled and minified JavaScript -->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <script src="js/main.js"></script>
        </body>
    </html>
    <?php
}


//other functions needed
function check_file_mime( $tmpname ) {
    $finfo = finfo_open( FILEINFO_MIME_TYPE );
    $mtype = finfo_file( $finfo, $tmpname );
    finfo_close( $finfo );
    if( $mtype == ( "image/png" ) || 
        $mtype == ( "image/jpeg" ) ||
        $mtype == ( "image/gif" ) ) {
        return TRUE;
    }
    else {
        return FALSE;
    }
}

function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

?>
