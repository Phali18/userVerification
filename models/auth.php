<?php


Class auth{

  

public function duplicate_check(){
    
      $db = Db::getInstance();
    
  $emailQuery = "SELECT count(*) FROM users WHERE :username='$username' AND :email='$email' AND username<>'' AND email <> '' LIMIT 1";
        $stmt = $db->prepare($emailQuery);
        $stmt->BindParam(':username', $username);
        $stmt->BindParam(':email', $email);
        $stmt->execute();
        $row = $stmt->fetch();
$count = $row[0];

  if ($count > 0) {
            $errors["email"] = $count."Username or Email already exists, please login.";
            throw new Exception();   
        } 

}


public function insertuser(){
    
      $db = Db::getInstance();
    
  $sql = ("INSERT INTO users (username, email, verified, token, password) VALUES (:username, :email, :verified, :token, :password)");
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':username', $username); 
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
          

  $username = $_POST('username');
  $email = $_POST('email');
  $password = $_POST('password');

  $stmt->execute();
}
public function checkuser(){
    
      $db = Db::getInstance();
      
  $query = "SELECT * FROM users WHERE :username ='$username' AND :password ='$password'";
            $stmt = $db->prepare($query);
             $stmt->bindParam(':username', $username); 
            $stmt->bindParam(':password', $password);
            $stmt->execute();
     
            $rows = $stmt->fetchall();
            
            foreach ($rows as $row){
                if (password_verify($password, $row["password"])) {
                 //$_SESSION["username"] == $_POST["username"];
                      header('uploadblog.php');
                }
                else{
                    throw new Exception();
                }
                    
                }
                $_SESSION["message"] = "You are now logged in!";
                $_SESSION["alert-class"] = "alert-success";
         
//                should take them to a page here where they can actually upload the blog
     
}

}

//click login it activates this sort of function with a try and catch block - could even have a controller and action
// which calls the logmein function which automatically call the two methods of duplicate check and
// checkuser, otherwise if one doesnt pass smoothly it throwns the error and GAMEOVER they have to 
// try again. 


//// think we could put both of these in the above class as compound methods but i just wanted to get your 
//thoughts on it - when u press the button 'LOGIN' it can call the logmein action and when you press the 
// button 'SIGNUP' it can make the signmeup method to run


function logmein(){
try{
   
   auth::duplicate_check();
    auth::checkuser();
    
} catch (Exception $ex) {
echo "sorry, soemthing went wrong, try again";
}
}


function signmeup(){
    try{
        auth::duplicate_checl();
        auth::insertuser();
        
    } catch (Exception $ex) {
echo "something went wrong with singing you up, please try again";
    }
}