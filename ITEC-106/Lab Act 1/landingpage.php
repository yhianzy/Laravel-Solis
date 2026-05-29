<?php 

session_start();


if(!isset($_SESSION['user'])){

    header("Location: login.php");

}

?>

<h2>Welcome <?php echo $_SESSION['user']; ?> ! </h2>

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
    <title>Landing Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    <body style="background-image: url('img/streetwear.avif'); background-repeat: no-repeat; background-size: cover;">
   
    <?php include('includes/includes.php'); ?>



    
    <div class="jumbotron jumbotron-fluid" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); height: 45%; width: 70%; border-radius: 20px; border-style: solid; font-family: fantasy;">
    <div class="container text-center">
        <h1 class="display-4">Pressure makes diamonds, <br> Streets make legends</h1>
        <p class="lead mt-5">Tested by struggle, Proven by hustle</p>
        <button class="btn btn-light mt-3 p-3 w-25" style="border-radius: 20px; border-style: solid; border-color: black;">Shop now</button>
    </div>
    </div>

    <?php include('includes/footer.php'); ?>
</body>
</html>