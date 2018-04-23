<?php

//include
include_once "include.php";

session_start();
if (isset($_SESSION['id'])){
    $loggedAccountId = $_SESSION['id'];
    //echo $loggedAccountId;
}


gen_header();
?>



<div id="loginPage">
    <div id="loginLeftSide">
        
        <div id="introduction">
            <!-- <img class="groupPhoto" src="graphics/group-photo.png" alt="logo"> -->
            Hello! Welcome to YouConnect! 

        </div>

    </div>
        


    <div id="loginRightSide">

        
        
        <!-- <img class="loginLogo" src="graphics/youconnect.png" alt="logo"> -->   

            <form action="check_login.php" method="post" id="frmLogin" class="col s12 form">
                <div class="row">
                        <div class="input-field col s12">
                            <input name="txtUserEmail" id="divUserInput" type="text" class="validate">
                            <label for="first_name">Insert Email</label>
                        </div>
                </div>

                <div class="row">
                        <div class="input-field col s12">
                            <input name="txtUserPassword" id="divPasswordInput" type="password" class="validate">
                            <label for="first_name">Insert Password</label>
                        </div>
                </div>
        

                <button id="btnLogin" class="btn btn-general mainpagebuttons btnLoginNew waves-effect waves-light" type="button" name="action">Login
                        <i class="material-icons right">send</i>
                </button>
                <button id="btnLogOut" class="btn btn-general mainpagebuttons btnLoginNew waves-effect waves-light" type="button" name="action">Logout
                        <i class="material-icons right">send</i>
                </button>

                <!-- <button type="submit" id="btnLogIn">Login</button> -->
            
            </form>

        </div>
    


</div>


<?php

footer();

?>
