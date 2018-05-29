<?php
//DB CONNECTION
session_start();
include "db.php";

//include
include "include.php";

//variables
$postsImgFolder='/img/posts/';

//CHECK SESSION
if (isset($_SESSION['id'])){
    $loggedIn=$_SESSION['id'];
}else{
    $loggedIn=0;
    header('Location: /');
}


//ADD POST TO DB
if (isset($_POST["post"]) && $_POST["post"]!=''){
    if (!isset($_POST["csrf_token"]) || $_SESSION["csrf_token"]!=$_POST["csrf_token"])
		{
			echo "Security error.";
			exit();
		}
    $newPost = $_POST["post"];
    $userPosting = $_SESSION["id"];
    $imgUrl="";
    if (isset($_FILES['postImg']) && check_file_mime($_FILES['postImg']['tmp_name'])){
        $picturePath = $_FILES['postImg']['name'];
        $extension = pathinfo($picturePath, PATHINFO_EXTENSION);
        $sPostImgName= md5($picturePath).'.'.$extension;
        $imgUrl = $postsImgFolder.$sPostImgName;
        move_uploaded_file( $_FILES['postImg']['tmp_name'] , $_SERVER['DOCUMENT_ROOT'].$imgUrl );
    }
    $insertPost=$conn->prepare("INSERT INTO posts (user_id, date, post_text, img_url) VALUES (:userId, NOW(), :postText, :imgUrl);");
    $insertPost->bindParam(':userId', $userPosting, PDO::PARAM_INT);
    $insertPost->bindParam(':postText', $newPost, PDO::PARAM_STR, 280);
    $insertPost->bindParam(':imgUrl', $imgUrl, PDO::PARAM_STR, 45);
    $insertPost->execute();

    //header('Location: '."posts.php");
}


//ADD COMMENT
if (isset ($_POST['comment']) && ($_POST['comment']!="") && isset ($_GET['pid'])){
    if (!isset($_POST["csrf_token"]) || $_SESSION["csrf_token"]!=$_POST["csrf_token"])
		{
			echo "Security Error";
			exit();
		}
    $newComment = $_POST["comment"];
    $userCommenting = $_SESSION["id"];
    $postId = $_GET['pid'];
    $insertComment=$conn->prepare("INSERT INTO comments (user_id, post_id, comment) VALUES (:userId, :postId, :comment);");
    $insertComment->bindParam(':userId', $userCommenting, PDO::PARAM_INT);
    $insertComment->bindParam(':postId', $postId, PDO::PARAM_INT);
    $insertComment->bindParam(':comment', $newComment, PDO::PARAM_STR, 280);
    $insertComment->execute();

    header('Location: '."posts.php");
}

//csrf token
$_SESSION["csrf_token"]=hash("sha256",rand()."rxY|1I]RkcmU.m2");


//GET ALL POSTS + COMMENTS
$postsDiv='<div class="posts card">';

//posts query
$getAllPosts=$conn->query("SELECT posts.id, posts.date, posts.post_text, posts.img_url, users.first_name, users.last_name, users.iv from posts JOIN users ON user_id=users.id ORDER BY posts.id DESC;");
$getAllPosts->execute();

while($post= $getAllPosts->fetch( PDO::FETCH_ASSOC )){
    $post["first_name"] = openssl_decrypt($post["first_name"], 'AES-256-CBC', $secret_key, OPENSSL_RAW_DATA, $post['iv']);
    $post["last_name"] = openssl_decrypt($post["last_name"], 'AES-256-CBC', $secret_key, OPENSSL_RAW_DATA, $post['iv']);
    $commentsDiv='<div class="comments card-action">';
   /*  $postDiv="<div class='post'>
            <p>User:  ". htmlentities($post['first_name']) ." ". htmlentities($post['last_name']) ."</p>
            <p>" . htmlentities($post['post_text']) . "</p>"; */
    $postDiv='<div class="card post">';
    if ($post['img_url']!=null){
        $postDiv=$postDiv.'<div class="card-image">
                                <img src='.$post['img_url'].'>
                            </div>';
    }
    $postDiv=$postDiv.'<div class="card-stacked">
                            <div class="card-content">
                                <p><strong>'. htmlentities($post['first_name']) ." ". htmlentities($post['last_name']) .':</strong></p>
                                <p>'. htmlentities($post['post_text']) .'</p>
                            </div>';
                
                

    //comments
    $getAllComments=$conn->query("SELECT comments.comment, users.first_name, users.last_name, users.iv FROM comments JOIN users ON users.id=comments.user_id JOIN posts ON posts.id=comments.post_id WHERE post_id=".$post['id']);
    $getAllComments->execute();
    
    while($comment= $getAllComments->fetch(PDO::FETCH_ASSOC)){
        $comment["first_name"] = openssl_decrypt($comment["first_name"], 'AES-256-CBC', $secret_key, OPENSSL_RAW_DATA, $comment['iv']);
        $comment["last_name"] = openssl_decrypt($comment["last_name"], 'AES-256-CBC', $secret_key, OPENSSL_RAW_DATA, $comment['iv']);

        $commentsDiv=$commentsDiv."<div class='comment'>
                                        <p>User:  ". htmlentities($comment['first_name']) ." ". htmlentities($comment['last_name']) ."</p>
                                        <p>" . htmlentities($comment['comment']) . "</p>
                                    </div>";
    }

    $commentFormDiv="<div class='new-comment'>
                        <form method='post' action='posts.php?pid=".$post['id']."'> \n
                            <input type='hidden' name='csrf_token' value='".$_SESSION["csrf_token"]."'>
                            Comment: \n
                            <textarea  class='materialize-textarea' name='comment' placeholder='comment here' required></textarea> \n
                            <button class='btn btn-general' type='submit'>comment</button> \n
                        </form>
                    </div>";
    $commentsDiv=$commentsDiv.$commentFormDiv."</div></div>";
    $postDiv=$postDiv.$commentsDiv.'</div>';
    $postsDiv=$postsDiv.$postDiv;
}

$postsDiv=$postsDiv."</div>";

//header
gen_header();
nav($loggedIn);
?>
    <div class="container posts-container">
        <div class="new-post card">

            <form method="post" enctype="multipart/form-data">
                Post Here:
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"] ?>">
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