<?php 

session_start();
if(!isset($_SESSION['user'])){

    header("Location: login.php");

}

?>

<?php 
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
    $role = $_POST["role"];


    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

   
    $sql = "INSERT INTO tbl_register(firstname, lastname, username, email, contactnumber, birth, password, city, zip, role)
        VALUES
            ('$firstname', '$lastname', '$username', '$email', '$contactnumber', '$birth', '$hashedPassword', '$city', '$zip', '$role')";

        if($password != $cpassword){
            $error_message = "Password does not match";
             $_SESSION ['error'] = "Password does not match" ;
        }else{
            if($conn->query($sql)){
                $_SESSION ['success'] =  "Registration Successful";

            }else{
                 $_SESSION ['error'] = "Registration Failed";
            }
        }
            header("Location: dashboard.php");
            exit();

        }

      
        // Delete
    if(isset($_POST['delete_user'])){

    // 1. Initialize
    $email = $_POST['del_email'];

    // 2. sql query
    $sql = "DELETE FROM tbl_register WHERE email='$email'";

    // 3. sql execution
    if($conn->query($sql)){
        $_SESSION['success'] = "User deleted successfully";
    }else{
        $_SESSION['error'] = "User deletion failed.";
    }

    // 4. Refresher ng page 
    header("Location: dashboard.php");
    exit();

}

    // edit
if(isset($_POST['edit_user'])){
    
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $status = $_POST['status'];
    $contactnumber = $_POST['contactnumber'];
    $birth = $_POST['birth'];
    $password = trim($_POST['password']);
    $city = $_POST['city'];
    $zip = $_POST['zip'];

    $sql = "UPDATE tbl_register SET firstname='$firstname', 
                                    lastname='$lastname', 
                                    username='$username', 
                                    email='$email', role='$role', status='$status', 
                                    contactnumber='$contactnumber', 
                                    birth='$birth', 
                                    city='$city', 
                                    zip='$zip'";
                    
    if(!empty($password)){
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql .= ", password='$hashedPassword'";
    }
    
    $sql .= " WHERE firstname='$firstname'";

    if($conn->query($sql)){
        $_SESSION['success'] = "User updated successfully!";
    }else{
        $_SESSION['error'] = "Update failed: " . $conn->error;
    }
    header("Location: dashboard.php");
    exit();
}



      


    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    

</head>

<body style="background-image: url('img/streetwear.avif'); background-repeat: no-repeat; background-size: cover;">
    
    <?php include 'navbar.php'; ?>

    <div class="container p-3" style="margin-top: 25px;">
       
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="text-white">Manage User</h3>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Add New User
            </button>
        </div>

        <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                  
                    <th>Id</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
               
                <?php while($row = $result->fetch_assoc()){ ?>
                <tr>

                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['contactnumber']; ?></td>
                   <td>
                        <?php 
                        $role = trim($row['role']); 
                        $role = strtolower($role);   
                        
                        if($role == "user"){ ?>
                            <span class="badge bg-primary">User</span>
                        <?php }elseif($role == "admin"){ ?>
                            <span class="badge bg-success">Admin</span>
                        <?php }else{ ?>
                            <span class="badge bg-secondary"><?php echo $row['role']; ?></span>
                        <?php } ?>
                    </td>
                                    
                   
                    <td>
                            <?php if($row['status'] == "Active"){ ?>
                                <span class="badge bg-success"><?php echo $row['status']; ?></span>
                            <?php }elseif($row['status'] == "Inactive"){ ?>
                                <span class="badge bg-danger"><?php echo $row['status']; ?></span>
                            <?php } ?>
                        </span>
                    </td>
                   
                   
                    <td>
                         <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['email']; ?>">
                        Edit
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $row['email']; ?>">
                        Delete
                        </button>
                    </td>

                     <!-- Delete Confirmation Modal -->

                        <div class="modal fade" id="deleteModal<?php echo $row['email']; ?>" aria-labelledby="deleteModalLabel" >
                            <div class="modal-dialog">

                            <form method="POST">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Confirm Delete</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="del_email" value="<?php echo $row['email']; ?>">
                                        Are you sure you want to delete this user?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button name="delete_user" class="btn btn-danger">Confirm Delete</button>
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>


                        <!-- Edit Modal -->

                        <div class="modal fade" id="editModal<?php echo $row['email']; ?>" aria-labelledby="editModalLabel" >
                            <div class="modal-dialog">

                            <form method="POST">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="editModalLabel">Edit User</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="edit_email" value="<?php echo $row['email']; ?>">
                                           <label class="form-label">Firstname</label>
                                            <div class="mb-3">
                                                <input type="text" class="form-control" name="firstname" placeholder="First Name" id="firstname" value="<?php echo $row['firstname']; ?>" required >
                                            </div>
                                             <label class="form-label">Lastname</label>
                                            <div class="mb-3">
                                                <input type="text" class="form-control" name="lastname" placeholder="Last Name" id="lastname" value="<?php echo $row['lastname']; ?>" required>
                                            </div>
                                             <label class="form-label">Username</label>
                                            <div class="mb-3">
                                                <input type="text" class="form-control" name="username" placeholder="Username" id="username" value="<?php echo $row['username']; ?>" required>
                                            </div>
                                             <label class="form-label">Email</label>
                                            <div class="mb-3">
                                                <input type="email" class="form-control" name="email" placeholder="Email" id="email" value="<?php echo $row['email']; ?>" required>
                                            </div>
                                             <div class="mb-3">
                                                <label class="form-label">Role</label>
                                                <select class="form-select" id="role" name="role" required>
                                                    <option value="User"<?php if($row['role'] == "User") echo "selected"; ?>>User</option>
                                                    <option value="Admin"<?php if($row['role'] == "Admin") echo "selected"; ?>>Admin</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Status</label>
                                                <select class="form-select" id="status" name="status" required>
                                                    <option value="Active"<?php if($row['status'] == "Active") echo "selected"; ?>>Active</option>
                                                    <option value="Inactive"<?php if($row['status'] == "Inactive") echo "selected"; ?>>Inactive</option>
                                                </select>
                                            </div>
                                             <label class="form-label">Contact Number</label>
                                            <div class="mb-3">
                                                <input type="text" class="form-control" name="contactnumber" placeholder="Contact Number" id="contactnumber" value="<?php echo $row['contactnumber']; ?>" required>
                                            </div>
                                             <label class="form-label">Birthday</label>
                                            <div class="mb-3">
                                                <input type="date" class="form-control" name="birth" id="birth" value="<?php echo $row['birth']; ?>" required>
                                            </div>
                                             <label class="form-label">Password</label>
                                            <div class="mb-3">
                                                <input type="password" class="form-control" name="password" placeholder="Password" id="password" value="<?php echo $row['password']; ?>" required>
                                            </div>
                                             <label class="form-label">City</label>
                                            <div class="mb-3">
                                                <input type="text" class="form-control" name="city" placeholder="City" id="city" value="<?php echo $row['city']; ?>" required>
                                            </div>
                                             <label class="form-label">Zip</label>
                                            <div class="mb-3">
                                                <input type="text" class="form-control" name="zip" placeholder="Zip Code" id="zip" value="<?php echo $row['zip']; ?>" required>
                                            </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" name="edit_user" class="btn btn-primary">Save Changes </button>
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>


                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    </div>

  
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="mb-3">
                            <input type="text" class="form-control" name="firstname" placeholder="First Name" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" name="lastname" placeholder="Last Name" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" name="username" placeholder="Username" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" class="form-control" name="email" placeholder="Email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select class="form-select" name="role" required>
                                <option value="User">User</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" name="contactnumber" placeholder="Contact Number" required>
                        </div>
                        <div class="mb-3">
                            <input type="date" class="form-control" name="birth" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" class="form-control" name="cpassword" placeholder="Confirm Password" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" name="city" placeholder="City" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" name="zip" placeholder="Zip Code" required>
                        </div>
                        <button type="submit" name="register" class="btn btn-primary w-100">Save Changes</button>
                    </form>
                </div>
            </div>
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