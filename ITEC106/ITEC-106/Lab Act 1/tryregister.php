<?php
session_start();
include "trydatabase.php";

$sql = "SELECT * FROM tbl_register";

if(isset($_POST['register'])){

$firstname = $_POST["firstname"];
$lastname = $_POST["lastname"];
$email = $_POST["email"];
$password = $_POST["password"];
$cpassword = $_POST["cpassword"];

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO tbl_register(firstname, lastname, email, password)
VALUES ('$firstname', '$lastname', '$email', '$hashedPassword')";

if($password != $cpassword){
    echo "Password does not match";
}else{
    if($conn->query($sql)){
   echo "Registration Successfull";
    }else{
   echo "Registration Failed";
}
}
    

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <form method="POST"> 
        
        <div class="form-floating mb-3">
        <input type="text" class="form-control" name="firstname" id="floatingInput" placeholder="name@example.com" required>
        <label for="floatingInput">First Name</label>
        </div>
        <div class="form-floating mb-3">
        <input type="text" class="form-control" name="lastname" id="floatingInput" placeholder="name@example.com" required>
        <label for="floatingInput">Last Name</label>
        </div>
        <div class="form-floating mb-3">
        <input type="email" class="form-control" name="email" id="floatingInput" placeholder="name@example.com" required>
        <label for="floatingInput">Email</label>
        </div>
        <div class="form-floating mb-3">
        <input type="password" class="form-control" name="password" id="floatingInput" placeholder="name@example.com" required>
        <label for="floatingInput"> Password</label>
        </div>
        <div class="form-floating mb-3">
        <input type="password" class="form-control" name="cpassword" id="floatingInput" placeholder="name@example.com" required>
        <label for="floatingInput">Confirm Password</label>
        </div>
        <div class="col-12 mx-3 my-3 d-flex justify-content-center">
        <button class="btn btn-primary" type="submit" name="register">Submit form</button>
        </div>
    
    </form>

     <p class="text-center">I have an account <a class="text-decoration-none" href="trylogin.php">Login here.</a></p>
    
</body>
</html>