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


//ADD COMMENT
if (isset ($_POST['comment']) && ($_POST['comment']!="") && isset ($_GET['pid'])){
    $newComment = $_POST["comment"];
    $userCommenting = 1/* $_SESSION["id"] */;
    $postId = $_GET['pid'];
    $insertComment=$conn->prepare("INSERT INTO comments (user_id, post_id, comment) VALUES (:userId, :postId, :comment);");
    $insertComment->bindParam(':userId', $userCommenting, PDO::PARAM_INT);
    $insertComment->bindParam(':postId', $postId, PDO::PARAM_INT);
    $insertComment->bindParam(':comment', $newComment, PDO::PARAM_STR, 280);
    $insertComment->execute();
}

//GET ALL POSTS + COMMENTS

//posts query
$getAllPosts=$conn->query("SELECT posts.id, posts.date, posts.post_text, posts.img_url, users.first_name, users.last_name from posts JOIN users WHERE user_id=users.id;");
$getAllPosts->execute();

while($post= $getAllPosts->fetch( PDO::FETCH_ASSOC )){
    echo "<div>
            <hr>User:  ". htmlentities($post['first_name']) ." ". htmlentities($post['last_name']) ."<br>\n" . htmlentities($post['post_text']) . "<br>\n";

    //comments
    $getAllComments=$conn->query("SELECT comments.comment, users.first_name, users.last_name FROM comments JOIN users ON users.id=comments.user_id JOIN posts ON posts.id=comments.post_id WHERE post_id=".$post['id']);
    $getAllComments->execute();
    
    while($comment= $getAllComments->fetch(PDO::FETCH_ASSOC)){

        echo "<div class='comment'><hr>User:  ". htmlentities($comment['first_name']) ." ". htmlentities($comment['last_name']) ."<br>\n" . htmlentities($comment['comment']) . "<br>\n </div>";
    }

    echo "<form method='post' action='posts.php?pid=".$post['id']."'> \n
            Comment: \n
            <textarea  name='comment' placeholder='comment here' required></textarea> \n
            <button type='submit'>comment</button> \n
        </form>
    </div>";
}

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
        <textarea  name="post" placeholder="What is on your mind?" required++++++++++++++++++++++++++++++++></textarea>
        <button type="submit">POST</button>
    </form>
        
    </body>
</html>