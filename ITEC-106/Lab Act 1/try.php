<?php 

include "conn.php";

if(isset($_POST['register'])){

   
    $fullname = $_POST["fullname"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO tbl_user (fullname, email, password)
        VALUES ('$fullname', '$email', '$hashedPassword' )";

    if($conn->query($sql)){

        echo "Registration Successful";

    }else{

        echo "Error" . $conn->error;

    }

}

?>

<form method="POST">

    Fullname: <input type="text" name="fullname" required> <br><br>
    Email: <input type="email" name="email" required> <br><br>
    Password: <input type="password" name="password" required> <br><br>
    <button type="submit" name="register">Register</button>

</form>








    




