<?php 

session_start();
include "conn.php";

if(isset($_POST['login'])){

   
    $email = $_POST["email"];
    $password = $_POST["password"];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "SELECT * FROM tbl_user WHERE email='$email'";
    $result = $conn->query($sql);

    if($result->num_rows > 0 ){
        $user = $result->fetch_assoc();

        if(password_verify($password, $user['password'])){
            $_SESSION['user'] = $user['fullname'];
            header("Location: landingpage.php");
        
            }else {
                echo "Invalid Password!";
            }
            } else {
            echo "User not found";
    }

}

?>

<form method="POST">

    Email: <input type="text" name="email" required> <br><br>
    Password: <input type="password" name="password" required> <br><br>
    <button type="submit" name="login">login</button>

</form>








    




