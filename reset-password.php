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
    <div class="container" id="newPass">
        <h1 class="form-title">Enter new password</h1>
        <p class="title-p">Enter a new password to reset the old password</p>
        <form id="signInForm" method="POST" action="process.php">
            <div class="input-group">
                <input class="form-control" id="email" type="email" name="email" placeholder="Email" required>
            </div> 
            <div class="input-group">     
                <input class="form-control" id="password" type="text" name="password" placeholder="Password" required>
            </div>
            <div class="forgot-pass">
            <a href="">Forgot Password</a>
            </div>
            <div class="signbtn">
            <input type="submit" name="signIn" value="Sign In">
            </div>
            <div class="register-link">
            <button id="signUpbtn" type="button">Don't have an account?</button>
        </div>
        </form>
    </div>
</body>
</html>