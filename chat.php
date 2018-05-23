<?php
//DB CONNECTION
session_start();
include "db.php";

//include
include "include.php";

//variables

//CHECK SESSION
if (isset($_SESSION['id'])){
    $loggedIn=$_SESSION['id'];
}else{
    $loggedIn=0;
    header('Location: /');
}

//GET ALL CHAT
$chatDiv='';
$getChat=$conn->query("SELECT chat.id, chat.message, users.first_name, users.last_name from chat JOIN users ON user_id=users.id ORDER BY chat.id DESC LIMIT 20;");
$getChat->execute();

while($message= $getChat->fetch( PDO::FETCH_ASSOC )){
    $messageDiv='<div class="message">';
    $messageDiv=$messageDiv.'<p><strong>'. htmlentities($message['first_name']) ." ". htmlentities($message['last_name']) .':</strong></p>
                             <p>'. htmlentities($message['message']) .'</p>
                        </div>';
    $chatDiv=$chatDiv.$messageDiv;       
}  


gen_header();
nav($loggedIn);

$_SESSION["csrf_token"]=hash("sha256",rand()."2D_sJu]J^Mb]@t[");
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
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"] ?>">
            <input type="text" placeholder="Send something to your non-existing friends here!">
            <button type="button" class="btn btn-general">Send it</button>
        </form>
    </div>

    <div id="chat" class="chat card">
        <?php
            echo $chatDiv;
        ?>
    </div>
</div>

<?php
    footer();
?>