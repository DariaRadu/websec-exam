<?php

//Starting Session
session_start();
include "db.php";

//include
include "include.php";


gen_header();
nav();

?>



<div id="profilePage" class="row">

    <div id="profileBox" class=" col s4 offset-s1 container posts-container">
            <div class="new-post card">
                <div class="row">
                    <div class="col s12 m7">
                        <div class="card">
                            <div class="card-image">
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
                            </div>
                        </div>
                    </div>
            </div>
    </div>
    </div>

    <div id="profilePostBox" class=" col s6 container posts-container">
            <div class="new-post card">
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
            </div>

    </div>

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

