<?php 

session_start();
include "trydatabase.php";

if(isset($_POST['login'])){


$email = $_POST['email'];
$password = $_POST['password'];

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$sql = "SELECT * FROM tbl_register WHERE email = '$email'";
$result = $conn->query($sql);

if($result->num_rows > 0){
    $user = $result->fetch_assoc();

    if(password_verify($password, $user['password'])){
         
        $_SESSION['user'] = $user['firstname'];
        $_SESSION['email'] = $user['email'];

        header ("Location: trylanding.php");
        exit();
    }
    else {
                echo "Invalid Password!";
            }
            } else {
                echo "User not found";

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
       
        <div class="form-floating mb-3">
        <input type="email" class="form-control" name="email" id="floatingInput" placeholder="name@example.com" required>
        <label for="floatingInput">Email</label>
        </div>
        <div class="form-floating mb-3">
        <input type="password" class="form-control" name="password" id="floatingInput" placeholder="name@example.com" required>
        <label for="floatingInput"> Password</label>
        </div>
       
        <div class="col-12 mx-3 my-3 d-flex justify-content-center">
        <button class="btn btn-primary" type="submit" name="login">Submit form</button>
        </div>
    
    </form>

     <p class="text-center">Not a TypeShi member? <a class="text-decoration-none" href="tryregister.php">Sign up here.</a></p>

</body>
</html>