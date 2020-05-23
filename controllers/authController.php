<?php

session_start();


$errors = array();
$username = "";
$email = "";

require '../models/db.php'; //two dots for the first mainfile(userv) and sourcefile
require "emailController.php";

try {


    $database = new Connection();
    $db = $database->openConnection();
// print_r($_POST);
// check if user clicked on the signup button
    if (isset($_POST["signup-btn"])) {
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $passwordconf = $_POST["passwordconf"];

        //validation

        if (empty($username)) {
            $errors["username"] = "Username Required";
        }
        //validate the email to ensure that a valid email adddress has been provided
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = "Email addresss is invalid";
        }

        if (empty($email)) {
            $errors["email"] = "Email Required";
        }

        if (empty($password)) {
            $errors["password"] = "Password Required";
        }

//checking the password matches the password conf 
        if ($password !== $passwordconf) {
            $errors["password"] = "The two password do not match";
        }
//checking that no two users have the same username or email in our database   
        $emailQuery = "SELECT count(*) FROM users WHERE :username='$username' AND :email='$email' AND username<>'' AND email <> '' LIMIT 1";
        $stmt = $db->prepare($emailQuery);
        $stmt->BindParam(':username', $username);
        $stmt->BindParam(':email', $email);
        $stmt->execute();
        $row = $stmt->fetch();
        $count = $row[0];
        
        // if the number of rows is greater than 0, it means that there is a user with the same email
        if ($count > 0) {
            $errors["email"] = $count."Username or Email already exists, please login.";
        }
        
//using func_count if the number of errors in the errors array is 0, then we can proceed to save the user
        if (count($errors) === 0) {
            $password = password_hash($password, PASSWORD_DEFAULT);
            //generate a unique random string and store it in the token variable
            $token = bin2hex((random_bytes(50)));
            $verified = FALSE;

            $sql = ("INSERT INTO users (username, email, verified, token, password) VALUES (:username, :email, :verified, :token, :password)");
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':username', $username); 
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':verified', $verified);
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':password', $password);
            $stmt->execute();

//            if ($stmt->execute()) {
//                //login user, grabbing the userid from the database
//                //$_SESSION["id"] = $user["id"];
//                $_SESSION["username"] = $username;
//                $_SESSION["email"] = $email;
//                $_SESSION["verified"] = $verified;
//
//                sendVerificationEmail($email, $token);
//                //flash message 
//                $_SESSION["message"] = "You are now logged in!";
//                $_SESSION["alert-class"] = "alert-success";
//                //redirect 
//                header("location:homepage.php");
//                exit();
//            } else {
//                $errors["db_error"] = "Database error: Failed to register";
//            }
        }
    }

//if the user clicks on the login button 

    if (isset($_POST["login-btn"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];

        //validation
        if (empty($username)) {
            $errors["username"] = "Username Required";
        }

        if (empty($password)) {
            $errors["password"] = "Password Required";
        }

        if (count($errors) === 0) {
            $query = "SELECT * FROM users WHERE :username ='$username' AND :password ='$password'";
            $stmt = $db->prepare($query);
             $stmt->bindParam(':username', $username); 
            $stmt->bindParam(':password', $password);
            $stmt->execute();
           /* $stmt->execute(
                    array(
                        "username" => $_POST["username"],
                        "password" => $_POST["password"])); */
            
            $rows = $stmt->fetchall();
            foreach ($rows as $row){
                if (password_verify($password, $row["password"])) {
                 //$_SESSION["username"] == $_POST["username"];
                $_SESSION["message"] = "You are now logged in!";
                $_SESSION["alert-class"] = "alert-success";
                //redirect
                header("location:homepage.php");
                exit();       
            }else{
                   $errors["login_fail"] = "Wrong credentials provided";
            }
             
            }
        }
    }




//verify user by token 

    function verifyUser($token) {
        global $db;
        $sql = "SELECT * FROM users WHERE :token ='$token' LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $count = $stmt->rowCount();

        if ($count > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $update_query = "UPDATE users SET verified =1 WHERE token='$token'";
            $stmt = $db->prepare($update_query);
            //$stmt->bindParam(':verified', $verified);
            // $stmt->bindParam(':token', $token);
            $stmt->execute();
        }

        if ($update_query) {
            //log user in 
            //$_SESSION["id"] = $user["id"];
            $_SESSION["username"] = $username;
            $_SESSION["email"] = $email;
            $_SESSION["verified"] = 1;

            //flash message 
            $_SESSION["message"] = "Your email address was successfully verified";
            $_SESSION["alert-class"] = "alert-success";
            //redirect 
            header("location:homepage.php");
            exit();
        } else {
            echo "User not found";
        }
    }

} catch (Exception $e) {
    $errors = $e->getMessage();
}
