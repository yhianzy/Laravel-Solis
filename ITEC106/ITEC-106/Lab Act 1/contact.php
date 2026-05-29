<?php 

include "conn.php";

if(isset($_POST['submit'])){

$fullname = $_POST["fullname"];
$email = $_POST["email"];
$contact = $_POST["contact"];
$message = $_POST["message"];

$sql = "INSERT INTO tbl_contact(fullname, email, contact, message)
        VALUES('$fullname', '$email', '$contact', '$message')" ;
        
        if($conn->query($sql)){

        echo "Message successfully sent";

         }else{

        echo "Error" . $conn->error;

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
    <title>Contact Form</title>
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
            <p class="text-center mt-1 fs-5" style="font-size: 25px">Contact TypeShi</p>
            
            <form method="POST" class="row g-3 needs-validation" novalidate>
                <div class="col-md-4 my-3">
                    <label for="validationCustom01" class="form-label">Full name</label>
                    <input type="text" class="form-control" name="fullname" id="validationCustom01" placeholder="Mark" required>
                    <div class="valid-feedback">
                    Looks good!
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
                    <input type="number" class="form-control" name="contact" id="validationCustom01" placeholder="09*********" required>
                    <div class="valid-feedback">
                    Looks good!
                    </div>
                </div>
            
               
            
             <div class="col-md-5 my-3">
                <label for="validationCustom01" class="form-label">Our Location</label>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d241.62735325304246!2d121.04878549364736!3d14.30924514492371!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397d7001f20108d%3A0x1a8ffd288dfc3ec1!2sHOME!5e0!3m2!1sen!2sph!4v1771230207369!5m2!1sen!2sph" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div> 



              <div class="col-md-5 my-3">
                    <label for="message">Message:</label>
                    <textarea id="message" name="message" rows="15" cols="50"></textarea>

                   
                </div>   


               <div class="col-12 my-5 d-flex justify-content-center">
                    <button class="btn btn-primary" type="submit" name="submit" >Submit form</button>
                </div>


            </form>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>
</body>
</html>