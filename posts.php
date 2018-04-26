<?php
//DB CONNECTION
session_start();
include "db.php";

//include
include "include.php";
//echo $_SESSION['id'];
//variables
$postsImgFolder='img/posts/';
//ADD POST TO DB
if (isset($_POST["post"]) && $_POST["post"]!=''){
    $newPost = $_POST["post"];
    $userPosting = 2/* $_SESSION["id"] */;
    $imgUrl="";
    if (isset($_FILES['postImg']) && check_file_mime($_FILES['postImg']['tmp_name'])){
        $picturePath = $_FILES['postImg']['name'];
        $extension = pathinfo($picturePath, PATHINFO_EXTENSION);
        $sPostImgName= md5($picturePath).'.'.$extension;
        $imgUrl = $postsImgFolder.$sPostImgName;
        move_uploaded_file( $_FILES['postImg']['tmp_name'] , $imgUrl );
    }
    $insertPost=$conn->prepare("INSERT INTO posts (user_id, date, post_text, img_url) VALUES (:userId, NOW(), :postText, :imgUrl);");
    $insertPost->bindParam(':userId', $userPosting, PDO::PARAM_INT);
    $insertPost->bindParam(':postText', $newPost, PDO::PARAM_STR, 280);
    $insertPost->bindParam(':imgUrl', $imgUrl, PDO::PARAM_STR, 45);
    $insertPost->execute();

    header('Location: '."posts.php");
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

    header('Location: '."posts.php");
}

//GET ALL POSTS + COMMENTS
$postsDiv='<div class="posts card">';

//posts query
$getAllPosts=$conn->query("SELECT posts.id, posts.date, posts.post_text, posts.img_url, users.first_name, users.last_name from posts JOIN users WHERE user_id=users.id ORDER BY posts.id DESC;");
$getAllPosts->execute();

while($post= $getAllPosts->fetch( PDO::FETCH_ASSOC )){
    $commentsDiv='<div class="comments">';
    $postDiv="<div class='post'>
            <p>User:  ". htmlentities($post['first_name']) ." ". htmlentities($post['last_name']) ."</p>
            <p>" . htmlentities($post['post_text']) . "</p>";

    //comments
    $getAllComments=$conn->query("SELECT comments.comment, users.first_name, users.last_name FROM comments JOIN users ON users.id=comments.user_id JOIN posts ON posts.id=comments.post_id WHERE post_id=".$post['id']);
    $getAllComments->execute();
    
    while($comment= $getAllComments->fetch(PDO::FETCH_ASSOC)){

        $commentsDiv=$commentsDiv."<div class='comment'>
                                        <p>User:  ". htmlentities($comment['first_name']) ." ". htmlentities($comment['last_name']) ."</p>
                                        <p>" . htmlentities($comment['comment']) . "</p>
                                    </div>";
    }

    $commentFormDiv="<div class='new-comment'>
                        <form method='post' action='posts.php?pid=".$post['id']."'> \n
                            Comment: \n
                            <textarea  class='materialize-textarea' name='comment' placeholder='comment here' required></textarea> \n
                            <button class='btn btn-general' type='submit'>comment</button> \n
                        </form>
                    </div>";
    $commentsDiv=$commentsDiv.$commentFormDiv."</div>";
    $postDiv=$postDiv.$commentsDiv."</div>";
    $postsDiv=$postsDiv.$postDiv;
}

$postsDiv=$postsDiv."</div>";


//header
gen_header();
nav();
?>
    <div class="container posts-container">
        <div class="new-post card">

            <form method="post" enctype="multipart/form-data">
                Post Here:
                <div class="input-field">
                    <textarea class="materialize-textarea" name="post" placeholder="What is on your mind?" required></textarea>
                    <!-- <input name="postImg" type='file'> -->
                </div>
                <div class="file-field input-field">
                    <div class="btn btn-general">
                        <span>image</span>
                        <input type="file" name="postImg">
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text">
                    </div>
                </div>
                <button class="btn btn-general" type="submit">POST</button>
            </form>

        </div>
    
        <?php
            echo $postsDiv;
        ?>
    </div>

<?php
footer();
?>