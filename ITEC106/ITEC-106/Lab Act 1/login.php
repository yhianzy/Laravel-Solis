<?php 

session_start();

include "conn.php";

if(isset($_POST['login'])){

   
    $email = $_POST["email"];
    $password = $_POST["password"];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "SELECT * FROM tbl_register WHERE email='$email'";
    $result = $conn->query($sql);

    if($result->num_rows > 0 ){
        $user = $result->fetch_assoc();

        if(password_verify($password, $user['password'])){
            $_SESSION['user'] = $user['firstname'];
            $_SESSION['email'] = $user['email'];
            
            header("Location: landingpage.php");
        
            }else {
                $_SESSION['error'] =  "Invalid Password!";
            }
            } else {
                $_SESSION['error'] = "User not found";
    }

}

?>


<!DOCTYPE html>
<html lang="en">

<style>

.btn:hover {
  transform: scale(1.05);
  box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;
}

</style>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

   
   

    <body style="background-image: url('img/streetwear.avif'); background-repeat: no-repeat; background-size: cover;">
   
    

    <div class="d-flex justify-content-center">
        <div class="card my-5 col-sm-3 p-4" style="font-family: fantasy;">
            <img src="img/streetLogo.webp" alt="" style="height: 270px;">
            <p class="text-center mt-2 fs-5" style="font-size: 25px">Log in to TypeShi</p>
           
           
        <form method="POST">
            <div class="form-floating mb-2">
                <input type="email" class="form-control" name="email" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Email address</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" name="password" id="floatingPassword" placeholder="Password">
                <label for="floatingPassword">Password</label>
            </div>
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" value="" id="checkDefault">
                <label class="form-check-label" for="checkDefault">
                    Remember email address
                </label>
            </div>
            <button type="submit" name="login" class="btn btn-primary mt-4 w-100">Log in</button>
            <a class="text-center mt-3 text-decoration-none d-flex justify-content-center" href="">Forgot password?</a>
            <hr>
            <p class="text-center">Not a TypeShi member? <a class="text-decoration-none" href="registration.php">Sign up here.</a></p>
        </div>
    </div>
    </form>


     <?php include('includes/footer.php'); ?>


    <?php if(isset($_SESSION['success'])){ ?>
        <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="successToast" class="toast align-items-center text-bg-success border-0" role="alert">
            <div class="d-flex">
            <div class="toast-body">
                <?php echo $_SESSION['success']; ?>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
        </div>
    <?php unset($_SESSION['success']); } ?>


     <?php if(isset($_SESSION['error'])){ ?>
        <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="errorToast" class="toast align-items-center text-bg-danger border-0" role="alert">
            <div class="d-flex">
            <div class="toast-body">
                <?php echo $_SESSION['error']; ?>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
        </div>
    <?php unset($_SESSION['error']); } ?>


    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var toastEl = document.getElementById('successToast');
         var toastError = document.getElementById('errorToast');
        if(toastEl){
            var toast = new bootstrap.Toast(toastEl);
            toast.show();
        }else if(toastError){
             var toast = new bootstrap.Toast(toastError);
            toast.show();
        } 
    });
   
    setTimeout(() => {
    document.querySelector('.toast')?.classList.remove('show');
    }, 3000);
    </script>

         




   

</body>
</html>