<?php

//include
include_once "include.php";

session_start();
if (isset($_SESSION['id'])){
    $loggedAccountId = $_SESSION['id'];
    echo $loggedAccountId;
}


gen_header();

?>


    <form action="check_login.php" method="post" id="frmLogin">
        <input type="text" id="divUserInput" name="txtUserEmail" placeholder="Insert Email" >
        <input type="password" id="divPasswordInput" name="txtUserPassword" placeholder="Insert Password" >
        <button type="submit" id="btnLogIn">Login</button>
    </form>





</body>
</html>