<?php

//Starting Session
session_start();
include "db.php";

//include
include "include.php";

//variables
$postsImgFolder='img/posts/';

if (isset($_SESSION['id'])){
    $loggedIn=$_SESSION['id'];
}else{
    header('Location: /');
}

//ADD POST TO DB
if (isset($_POST["post"]) && $_POST["post"]!=''){
    if (!isset($_POST["csrf_token"]) || $_SESSION["csrf_token"]!=$_POST["csrf_token"])
		{
			echo "Security Error";
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
        move_uploaded_file( $_FILES['postImg']['tmp_name'] , $imgUrl );
    }
    $insertPost=$conn->prepare("INSERT INTO posts (user_id, date, post_text, img_url) VALUES (:userId, NOW(), :postText, :imgUrl);");
    $insertPost->bindParam(':userId', $userPosting, PDO::PARAM_INT);
    $insertPost->bindParam(':postText', $newPost, PDO::PARAM_STR, 280);
    $insertPost->bindParam(':imgUrl', $imgUrl, PDO::PARAM_STR, 45);
    $insertPost->execute();

    header('Location: '."profile-page.php");
}

//ADD COMMENT TO POST
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

    header('Location: '."profile-page.php");
}


//csrf token
$_SESSION["csrf_token"]=hash("sha256",rand()."rxY|1I]RkcmU.m2");

//GET PROFILE INFO
$getProfileInfo=$conn->query("SELECT first_name, last_name, channel, profile_pic FROM users WHERE id=".$_SESSION['id']);
$getProfileInfo->execute();

$userInfo=$getProfileInfo->fetch( PDO::FETCH_ASSOC );
$profileDiv="<div class='card-image'>
                <img src='".$userInfo['profile_pic']."'>
                <span class='card-title'>".$userInfo['first_name'].' '.$userInfo['last_name']."</span>
            </div>
            <a class='waves-effect waves-light modal-trigger' data-target='modalEditProfile'>
                <button class='btn btn-general mainpagebuttons btnLoginNew waves-effect waves-light' type='button' name='action'>
                    Edit
                </button>
            </a>
            <div class='card-content'>
                <p>This is your profile page </p>
            </div>
            <div class='red-text card-action card-link'>
                <a href='".$userInfo['channel']."'>YouTube Channel Link</a>
            </div>";

//GET ALL POSTS + COMMENTS
$postsDiv='<div class="posts card">';

//posts query
$getAllPosts=$conn->query("SELECT posts.id, posts.date, posts.post_text, posts.img_url, users.first_name, users.last_name from posts JOIN users ON user_id=users.id WHERE user_id=".$_SESSION['id']." ORDER BY posts.id DESC;");
$getAllPosts->execute();

while($post= $getAllPosts->fetch( PDO::FETCH_ASSOC )){
    $commentsDiv='<div class="comments card-action">';
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
    $getAllComments=$conn->query("SELECT comments.comment, users.first_name, users.last_name FROM comments JOIN users ON users.id=comments.user_id JOIN posts ON posts.id=comments.post_id WHERE post_id=".$post['id']);
    $getAllComments->execute();
    
    while($comment= $getAllComments->fetch(PDO::FETCH_ASSOC)){

        $commentsDiv=$commentsDiv."<div class='comment'>
                                        <p>User:  ". htmlentities($comment['first_name']) ." ". htmlentities($comment['last_name']) ."</p>
                                        <p>" . htmlentities($comment['comment']) . "</p>
                                    </div>";
    }

    /* $commentFormDiv="<div class='new-comment'>
                        <form method='post' action='profile_page.php?pid=".$post['id']."'> \n
                            Comment: \n
                            <input type='hidden' name='csrf_token' value='".$_SESSION["csrf_token"]."'>
                            <textarea  class='materialize-textarea' name='comment' placeholder='comment here' required></textarea> \n
                            <button class='btn btn-general' type='submit'>comment</button> \n
                        </form>
                    </div>"; */
    $commentsDiv=$commentsDiv./* $commentFormDiv. */"</div></div>";
    $postDiv=$postDiv.$commentsDiv.'</div>';
    $postsDiv=$postsDiv.$postDiv;
}

$postsDiv=$postsDiv."</div>";


gen_header();
nav($loggedIn);

?>



<div id="profilePage" class="row">

    <div id="profileBox" class=" col s4 offset-s1 container posts-container">
            <div class="new-post card">
                <?php
                echo $profileDiv;
                ?>
                        <!-- <div class="card"> -->
                    <!-- <div class="card-image">
                        <img src="graphics/profilepicture.jpg">
                        <span class="card-title">Captain Famine</span>
                    </div>
                    <a class="waves-effect waves-light modal-trigger" data-target="modalEditProfile">
                        <button id="btnLogin" class="btn btn-general mainpagebuttons btnLoginNew waves-effect waves-light" type="button" name="action">
                            Edit
                        </button>
                    </a>
                    
                    
                    <div class="card-content">
                        <p>Age: 23</p>
                        <p>Location: Copenhagen</p>
                        <p>Description: I make memes part-time. Kill me pls.</p>
                    </div>
                    <div class="red-text card-action card-link">
                        <a href="https://www.youtube.com/user/actionbundyclan ">YouTube Channel Link</a>
                    </div> -->
            </div>
                    <!-- </div> -->
    </div>

    <div id="profilePostBox" class=" col s6 container posts-container">
            <div class="new-post card">
            <!-- <div class="container posts-container">
            <div class="new-post card"> -->
    
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
    <!-- 
            </div>
            </div> -->

            <?php
            echo $postsDiv;
            ?>
    

</div>

    

<!-- MODAL -->
<div id="modalEditProfile" class="modal modal-fixed-footer">
    <div class="modal-content">
      <h4>Edit Profile</h4>
      <form method='post' enctype="multipart/form-data">
        <input name='txtFirstName' type='text' placeholder="First name" required>
        <input name='txtLastName' type='text' placeholder="Last name" required>
        <input name='txtEmail' type='email' placeholder="email" required>
        <input name='txtPassword' type='password' placeholder="Password" required>
        <input name='urlChannel' type='text' placeholder="Link to Youtube Channel" required>
       <!--  <input name='profilePic' type='file' required> -->
       <div class="file-field input-field">
            <div class="btn btn-general">
                <span>Picture</span>
                <input type="file" name="profilePic">
            </div>
            <div class="file-path-wrapper">
                <input class="file-path validate" type="text">
            </div>
        </div>
        <div class="captcha_wrapper">
		    <div class="g-recaptcha" data-sitekey="6LcfkFMUAAAAAIeG1FJdjlggLsMa6tpd1Npc0ulq"></div>
	    </div>
        <button  class="btn btn-general" type='submit'>Submit</button>
    </form>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Agree</a>
    </div>
  </div>

<?php

footer();

?>

