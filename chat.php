<?php
//DB CONNECTION
session_start();
include "db.php";

//include
include "include.php";

//variables
$postsImgFolder='img/posts/';

//CHECK SESSION
if (isset($_SESSION['id'])){
    $loggedIn=$_SESSION['id'];
}else{
    $loggedIn=0;
    header('Location: login.php');
}

gen_header();
nav($loggedIn);

?>

<div id="chatPage" class="container">
    <div class="chatWindow">
        <div class="chatMessage">
            <p>Whadap my dude</p>
        </div>
        <div class="chatMessage">
            <p>Suh dude</p>
        </div>
        
    </div>

    <div>
        <input type="text" placeholder="Send something to your non-existing friends here!">
        <button type="button">Send it</button>
    </div>
</div>

<?php
    footer();
?>