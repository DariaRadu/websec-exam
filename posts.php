<?php
//DB CONNECTION
session_start();
include "db.php";
//ADD POST TO DB
if (isset($_POST["post"]) && $_POST["post"]!=''){
    $newPost = $_POST["post"];
    $userPosting = 1/* $_SESSION["id"] */;
    $imgUrl="";
    $insertPost=$conn->prepare("INSERT INTO posts (user_id, date, post_text, img_url) VALUES (:userId, NOW(), :postText, :imgUrl);");
    $insertPost->bindParam(':userId', $userPosting, PDO::PARAM_INT);
    $insertPost->bindParam(':postText', $newPost, PDO::PARAM_STR, 280);
    $insertPost->bindParam(':imgUrl', $imgUrl, PDO::PARAM_STR, 45);
    $insertPost->execute();
}

//GET ALL POSTS

$getAllPosts=$conn->query("SELECT posts.date, posts.post_text, posts.img_url, users.first_name, users.last_name from posts JOIN users WHERE user_id=users.id;");
$getAllPosts->execute();
while($post= $getAllPosts->fetch( PDO::FETCH_ASSOC )){
    echo "<hr>User:  ". htmlentities($post['first_name']) ." ". htmlentities($post['last_name']) ."<br>\n" . htmlentities($post['post_text']) . "<br>\n";
    echo "<form method='post'> \n
            Comment: \n
            <textarea  name='post' placeholder='comment here'></textarea> \n
            <button type='submit'>comment</button> \n
        </form>";
//		echo "<hr>Username:  ". ($row['user_name']) . "<br>\n Comment: " . ($row['text']) . "<br>\n";
}


//ADD COMMENT
/* if (isset ($_POST[''])) */

//GET ALL COMMENTS FOR POSTS

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>
    <body>

    <form method="post">
        Post Here:
        <textarea  name="post" placeholder="What is on your mind?"></textarea>
        <button type="submit">POST</button>
    </form>
        
    </body>
</html>