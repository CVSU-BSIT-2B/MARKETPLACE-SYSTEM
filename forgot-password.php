<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" type="text/css" href="css/forgotPass.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
</head>
<body>
    <img id="logo" src="images/logo.png">
    <div class="container" id="forgotPass">
        <h1 class="form-title">Can't remember your password?</h1>
        <p class="title-p">Enter your email and we'll send a link to reset your password</p>
        <form id="signInForm" method="POST" action="send-password-reset.php">
            <div class="input-group">
                <input class="form-control" id="email" type="email" name="email" placeholder="Email" required>
            </div> 
            <div class="signbtn">
            <input type="submit" name="Send Link" value="Send Link">
            </div>
        </div>
        </form>
    </div>
</body>
</html>