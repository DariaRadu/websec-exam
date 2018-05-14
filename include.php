<?php

function gen_header() {
    ?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
             <!-- Compiled and minified CSS -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
            <link rel="stylesheet" href="css/style.css">
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
                <li><a href="login.php">Login</a></li>
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

?>
