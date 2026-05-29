<?php 

session_start();
if(!isset($_SESSION['email'])){

    header("Location: login.php");

}


$email = $_SESSION['email']; 

//display default pfp
$defaultPic = "img/default.jpg";


//bind database 
include "conn.php";


//Display logged in users 


//SQL query 
$sql = "SELECT * FROM tbl_register WHERE email='$email'";

// execute sql
$result = $conn->query($sql);

// to fetch the data 
$user = $result->fetch_assoc();

//display pfp if theres value
if(!empty($user['profile_pic'])){

    $defaultPic = "img/" .  $user['profile_pic'];

}

// upload pfp
if(isset($_POST['upload_pic'])){
    $filename = $_FILES['profile_pic']['name']; //get name of the file
    $tempname = $_FILES['profile_pic']['tmp_name']; // temporary folder for the files upload 
    $fileSize = $_FILES['profile_pic']['size']; // will get the size of the file in bytes 

 // extract file extension
 $fileExt = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

 // declare array  for allowed file types 
 $allowed = ["jpg", "png", "jpeg", "webp"];

 // check if the extension is in the array 
 if(in_array($fileExt, $allowed)){
    if($fileSize < 2000000){
        
        $newfilename = time() . "_" . $filename;
        $uploadloc = "img/" . $newfilename;
        move_uploaded_file($tempname, $uploadloc);

        $sql = "UPDATE tbl_register SET profile_pic = '$newfilename' WHERE email = '$email'
        ";

        if($conn->query($sql)){
             $_SESSION['success'] = "Profile picture updated successfully";
        }else{
            $_SESSION['error'] = "Unable to upload profile picture";
        }

    }else{
        $_SESSION['error'] = "File is too large";
    }
 }else{
    $_SESSION['error'] = "Only JPG, JPEG, and PNG are allowed";
 }

  //refresh page 
  header("Location: profile.php");
  exit();

}

// update user profile
if(isset($_POST['update_user'])){

    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $contactnumber = $_POST['contactnumber'];
    $current_pass = $_POST['current_pass'];
    $new_pass = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];

    $hashedPassword = password_hash($new_pass, PASSWORD_DEFAULT);

    $sql = "UPDATE tbl_register SET 
            firstname = '$fullname',
            email = '$email'";

   if(!empty($current_pass) || !empty($new_pass) || !empty($confirm_pass)){

        if(password_verify($current_pass, $user['password'])){

            if($new_pass == $confirm_pass){

                $sql .= ", password ='$hashedPassword'";

            }else{
                 $_SESSION['error'] = "Password does not match";
                header("Location: profile.php");
                exit();
            }

        }else{
            $_SESSION['error'] = "Current password is incorrect or there are missing fields";
            header("Location: profile.php");
            exit();

   }
    
}  
    
    $sql .= "WHERE email = '$email'";

    if($conn->query($sql)){
        $_SESSION['success'] = "User profile updates successfully";
    }else{
         $_SESSION['error'] = "Unable to update profile picture";
    }

    header("Location: profile.php");
    exit();

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

</head>
<body style="background-color:#708090">
    
    <nav class="navbar navbar-expand-lg bg-dark"  data-bs-theme="dark">
  <div class="container-fluid">
    <a class="navbar-brand mx-4" href="landingpage.php">Back to dashboard</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
   <div class="collapse navbar-collapse d-flex justify-content-end" id="navbarSupportedContent">
      <ul class="navbar-nav">
         
       
      </ul>
    </div>
  </div>
</nav>


    <div class="row g-3 mx-5 p-5">
        <div class="col border border-dark my-5 rounded bg-white ">
            <div class="pfp d-flex justify-content-center my-5"><img src="img/pokemon.gif" alt="" srcset=""></img></div>
           
            
           <form method="POST" enctype="multipart/form-data"> 
                <div class="d-flex flex-column align-items-center">
               <img 
                    class="rounded-circle img-fluid mb-3 mx-auto"
                    width="200"
                    alt="Profile Picture"
                    src="<?php echo $defaultPic; ?>">
            
                        <input type="file" name="profile_pic" class="form-control w-50 mb-3">
                        <button type="submit" name="upload_pic" class="w-50 my-3 btn btn-success">Change Photo</button>
                
              
            </div>
        </div>
    
    
        <div class="col border border-dark my-5 mx-5 rounded bg-white ">
       
            <h2 class="my-5">User Profile</h2>
            <div class="row g-3 ">
                <div class="col ">
                     <label>Fullname</label>
                    <input type="text" class="form-control" name="fullname" value="<?php echo $user['firstname'] ?>" placeholder="First name" aria-label="First name">
                </div>
                <div class="col">
                     <label>Email</label>
                    <input type="text" class="form-control" name="email" value="<?php echo $user['email'] ?>" placeholder="Last name" aria-label="Last name">
                </div>
                <div class="">
                    <label>Phone number</label>
                    <input type="text" class="form-control" name="contactnumber" value="<?php echo $user['contactnumber'] ?>" placeholder="First name" aria-label="First name">
                </div>
                 <div class="">   
                      <label>Current Password</label>
                    <input type="password" class="form-control" name="current_pass" value="<?php echo $user['contactnumber'] ?>" placeholder="First name" aria-label="First name">
                </div>
                <div class="col">
                    <label>Password</label>
                    <input type="password" class="form-control" name="new_pass" value="<?php echo $user['contactnumber'] ?>" placeholder="First name" aria-label="First name">
                </div>
                 <div class="col">   
                      <label>New Password</label>
                    <input type="password" class="form-control" name="confirm_pass" value="<?php echo $user['contactnumber'] ?>" placeholder="First name" aria-label="First name">
                </div>
        
                <div class="d-flex gap-2 my-3">
                    <button type="submit" class="btn btn-success" name="update_user" >Save Changes</button>
                     <button type="reset" class="btn btn-secondary" name="update_user" >Cancel</button>
                </div>

            </form>  

        </div>
        
    </div>




    
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