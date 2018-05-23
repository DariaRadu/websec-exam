<?php
//DB CONNECTION
session_start();
include "../db.php";

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