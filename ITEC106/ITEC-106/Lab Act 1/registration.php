<?php 
session_start(); 
include "conn.php";

$sql = "SELECT * FROM tbl_register";

$result = $conn->query($sql);
   
   if(isset($_POST['register'])){

    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $contactnumber = $_POST["contactnumber"];
    $birth = $_POST["birth"];
    $password = $_POST["password"];
    $cpassword = $_POST["cpassword"];
    $city = $_POST['city'];
    $zip = $_POST["zip"];

   

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO tbl_register(firstname, lastname, username, email, contactnumber, birth, password, city, zip)
        VALUES
            ('$firstname', '$lastname', '$username', '$email', '$contactnumber', '$birth', '$hashedPassword', '$city', '$zip')";

        if($password != $cpassword){
            $error_message = "Password does not match";
             $_SESSION ['error'] = "Password does not match" ;
        }else{
            if($conn->query($sql)){
                $_SESSION['success'] =  "Registration Successful";

            }else{
                 $_SESSION['error'] = "Registration Failed";
            }
        }
            header("Location: registration.php");
            exit();
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
    <title>Registraion Form</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

    
  
                    



    <body style="background-image: url('img/streetwear.avif'); background-repeat: no-repeat; background-size: cover;">
   
   

    <div class="d-flex justify-content-center">
        <div class="card my-2 col-sm-5 p-4" style="font-family: fantasy;">
             <div class="container-fluid d-flex justify-content-center">
    <a class="navbar-brand" href="#"><img src="img/streetLogo.webp" alt="" style="height: 120px; width: 120px;"></a>
</div>
            <p class="text-center mt-2 fs-5" style="font-size: 25px">Register to TypeShi</p>
            
            
            <form method="POST" class="row g-3 needs-validation" novalidate>
                <div class="col-md-4 my-3">
                    <label for="validationCustom01" class="form-label">First name</label>
                    <input type="text" class="form-control" name="firstname" id="validationCustom01" placeholder="Mark" required>
                    <div class="valid-feedback">
                    Looks good!
                    </div>
                </div>
                <div class="col-md-4 my-3">
                    <label for="validationCustom02" class="form-label">Last name</label>
                    <input type="text" class="form-control"  name="lastname" id="validationCustom02" placeholder="Otto" required>
                    <div class="valid-feedback">
                    Looks good!
                    </div>
                </div>
                <div class="col-md-4 my-3">
                    <label for="validationCustomUsername" class="form-label">Username</label>
                    <div class="input-group has-validation">
                    <span class="input-group-text" id="inputGroupPrepend">@</span>
                    <input type="text" class="form-control" name="username" id="validationCustomUsername" aria-describedby="inputGroupPrepend" required>
                    <div class="invalid-feedback">
                        Please choose a username.
                    </div>
                    </div>
                </div>
               
               
               <div class="col-md-4 my-3">
                    <label for="validationCustom01" class="form-label">Email Address</label>
                    <input type="mail" class="form-control" name="email" id="validationCustom01" placeholder="Mark@gmail.com" required>
                    <div class="valid-feedback">
                    Looks good!
                    </div>
                </div>
               
                <div class="col-md-4 my-3">
                    <label for="validationCustom01" class="form-label">Contact Number</label>
                    <input type="number" class="form-control" name="contactnumber" id="validationCustom01" placeholder="09*********" required>
                    <div class="valid-feedback">
                    Looks good!
                    </div>
                </div>
            
               
                <div class="col-md-4 my-3">
                    <label for="validationCustom01" class="form-label">Date of birth</label>
                    <input type="date" class="form-control" name="birth" id="validationCustom01" placeholder="" required>
                    <div class="valid-feedback">
                    Looks good!
                    </div>
                </div>

                 <div class="col-md-6 my-3">
                    <label for="validationCustom01" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="validationCustom01" placeholder="" required>
                    <div class="valid-feedback">
                    Looks good!
                    </div>
                </div>
            
               
                <div class="col-md-6 my-3">
                    <label for="validationCustom01" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" name="cpassword" id="validationCustom01" placeholder="" required>
                    <div class="valid-feedback">
                    Looks good!
                    </div>
                </div>
               
                <div class="col-md-6 my-2">
                    <label for="validationCustom03" class="form-label">City</label>
                    <input type="text" class="form-control" name="city" id="validationCustom03" required>
                    <div class="invalid-feedback">
                    Please provide a valid city.
                    </div>
                </div>
                
                <div class="col-md-3 my-3">
                    <label for="validationCustom05" class="form-label">Zip</label>
                    <input type="text" class="form-control" name="zip" id="validationCustom05" required>
                    <div class="invalid-feedback">
                    Please provide a valid zip.
                    </div>
                </div>
                <div class="col-12 my-4 d-flex justify-content-center">
                    <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                    <label class="form-check-label" for="invalidCheck">
                        Agree to terms and conditions
                    </label>
                    <div class="invalid-feedback">
                        You must agree before submitting.
                    </div>
                    </div>
                </div>
               <div class="col-12 my-3 d-flex justify-content-center">
                    <button class="btn btn-primary" type="submit" name="register">Submit form</button>
                </div>
                 <div class="col-12 my-3 d-flex justify-content-center">
                <p class="d-flex justify-content-center">Already have an account? <a class="text-decoration-none d-flex justify-content-center" href="login.php"> Login here.</a></p>
                </div>

            </form>
       
        </div>
    </div>

 



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


    
    
    
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>