<?php 

include "conn.php";
    
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
        }else{
            if($conn->query($sql)){
                $success_message = "Registration Successful";
            }else{
                $error_message = "Registration Failed";
            }
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
    <title>Registraion Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  
                    



    <body style="background-image: url('img/streetwear.avif'); background-repeat: no-repeat; background-size: cover;">
   
    <?php include('includes/includes.php'); ?>

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


            </form>
       
        </div>
    </div>

    <?php include('includes/footer.php'); ?>
</body>
</html>