<?php
require '../controllers/authController.php';
//verfiy user token
if (isset($_GET["token"])) {
    $token = $_GET["token"];
    verifyUser($token);
}
//log out function 
/*if (isset($_GET["logout"])) {
    session_destroy();
    unset($_SESSION["username"]); 

    //redirecting
    header("location: login.php");
    exit(); }*/

?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <!-- Bootstrap link -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <!--external stylesheet -->
        <link rel="stylesheet" href="css/style.css">
        <title>Home Page</title>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-4 offset md-4 form-div login">
                    <?php if (isset($_SESSION["message"])): ?>
                        <div class="alert <?php echo $_SESSION["alert-class"]; ?>">
                            <?php
                            echo $_SESSION["message"];
                            unset($_SESSION["message"]);
                            unset($_SESSION["alert-class"]);
                            ?>
                        </div>
                    <?php endif; ?> 
                    <h3>Welcome,<?php echo $_SESSION["username"]; ?> </h3>
                    <a href ="homepage.php?logout=1" class="logout">Logout</a>
                    
                    <?php if (!$_SESSION["verified"]): ?>
                        <div class="alert alert-warning">
                            You need to verify your account.
                            Sign in to your email account and click on the
                            verification link we have just emailed you at
                            <strong> <?php echo $_SESSION["email"] ?></strong>  
                        </div>
                    <?php endif; ?>
                    <?php if ($_SESSION["verified"]): ?>
                        <button class="btn btn-block btn-lg btn-primary">I am verified!</button>
                    <?php endif; ?>
                </div>   


            </div>



        </div>

        <?php
        // put your code here
        ?>
    </body>
</html>
