<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign Up</title>
</head>
<body>
    <form action='signup-user.php' method='post'>
        <input name='txtFirstName' type='text' placeholder="First name" required>
        <input name='txtLastName' type='text' placeholder="Last name" required>
        <input name='txtPassword' type='password' placeholder="Password" required>
        <input name='txtEmail' type='email' placeholder="email" required>
        <input name='urlChannel' type='text' placeholder="Link to Youtube Channel" required>
        <div class="captcha_wrapper">
		    <div class="g-recaptcha" data-sitekey="6LcfkFMUAAAAAIeG1FJdjlggLsMa6tpd1Npc0ulq"></div>
	    </div>
        <button type='submit'>Submit</submit>
    </form>
    
    <div>

    </div>
    
    <script src='https://www.google.com/recaptcha/api.js'></script>

</body>
</html>