<?php


Class auth{

  private $email ; 
  private $hashpasword ; 
  private $realpasword;
 
  
  //should this be in the controller -- line 12!!

//  
  // assign the variables, 
  // if statement in the controller - P - if statements validating and then calling the model
  
  // go through the queries and change them to the DB names
    public function __construct($email, $hashpassword, $realpassword) {
  
  if (isset($_POST["signup-btn"])) {
        $this->email = $_POST["email"];
        $this->hashpassword = hash($_POST["realpassword"]) ;
        $this->realpassword = $_POST["realpassword"];
       }
  }


public function duplicate_check(){
    
      $db = Db::getInstance();
    
  $emailQuery = "SELECT count(*) FROM admin_login WHERE :email='$email' AND :haspassword='$hashpassword' AND email<>'' AND haspassword <> '' LIMIT 1";
        $stmt = $db->prepare($emailQuery);
        $stmt->BindParam(':email', $email);
        $stmt->BindParam(':hashpassword', $hashpassword);
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
    
  $sql = ("INSERT INTO admin_login (email, hashpassword, realpassword) VALUES (:email, :hashpassword, :realpassword)");
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':hashpassword', $hashpassword);
            $stmt->bindParam(':realpassword', $realpassword);
          


  $email = $_POST('email');
  $password = $_POST('realpassword');
  

  $stmt->execute();
}
public function checkuser(){
    
      $db = Db::getInstance();
      
  $query = "SELECT * FROM admin_login WHERE :email ='$email' AND :hashpassword ='$hashpassword'";
            $stmt = $db->prepare($query);
             $stmt->bindParam(':email', $email); 
            $stmt->bindParam(':hashpassword', $hashpassword);
            $stmt->execute();
     
            $rows = $stmt->fetchall();
            
            foreach ($rows as $row){
                if (password_verify($hashpassword, $row["hashpassword"])) {
                 //$_SESSION["username"] == $_POST["username"];
                    
                    
                    $_Session['email'] = $this->email;
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