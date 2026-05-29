<!DOCTYPE html>
<html lang="en">

<style>

.btn:hover {
  transform: scale(1.05);
  box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;
}

.carousel-caption h5,
.carousel-caption p {
  color: black;
  text-shadow: 0 1px 2px rgba(255, 255, 255, 0.8);
}

#carouselExampleCaptions {
  max-width: 70%;
  margin: 0 auto;
}

#carouselExampleCaptions .carousel-inner img {
  max-width: 100%;
  margin: 0 auto;
}

</style>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="carousel.js"></script>
   
   <body style="background-image: url('img/streetwear.avif'); background-repeat: no-repeat; background-size: cover;">
   
    <?php include('includes/includes.php'); ?>

   
           <div id="carouselExampleCaptions" class="carousel slide">
            <div class="carousel-indicators my-5 d-flex justify-content-center">
                <button type="button" data-target="#carouselExampleCaptions" data-slide-to="0" class="active" aria-current="true" aria-label="Slide 1" style="width: 30px;"></button>
                <button type="button" data-target="#carouselExampleCaptions" data-slide-to="1" aria-label="Slide 2" style="width: 30px"></button>
                <button type="button" data-target="#carouselExampleCaptions" data-slide-to="2" aria-label="Slide 3" style="width: 30px"></button>
            </div>
            <div class="carousel-inner" style="font-family: fantasy; margin-right: 45px;">
                <div class="carousel-item active">
                <img src="img/gang4.jpg" class="d-block mt-5" alt="" style="height: 80vh; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block my-5">
                    <h5 style="font-size: 50px">Culture & Movement</h5>
                    <p style="font-size: 20px">We’re not just clothing. We’re a movement.
                        <br>Born from late nights, loud dreams, and streets that never sleep.
                        <br>This brand represents ambition, independence, and creative rebellion.</p>
                </div>
                </div>
                <div class="carousel-item">
                <img src="img/gang2.jpg" class="d-block mt-5" alt="" style="height: 80vh; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block my-5">
                    <h5 style="font-size: 50px">Luxury Street</h5>
                    <p style="font-size: 20px">We merge street culture with refined craftsmanship.
                        <br>Every piece is designed with intention — bold silhouettes, premium fabrics, and details that speak without shouting.
                       <br> This is streetwear for those who move different.</p>
                </div>
                </div>
                <div class="carousel-item">
                <img src="img/gang3.jpg" class="d-block mt-5" alt="" style="height: 80vh; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block my-5">
                    <h5 style="font-size: 50px">Message-Driven</h5>
                    <p style="font-size: 20px">This brand stands for purpose.
                        <br>Every design tells a story — about growth, resilience, and staying real in a world full of copies.
                        <br>What you wear should say something before you even speak.</p>
                </div>
                </div>
            </div>
           


            
            </div>

    <?php include('includes/footer.php'); ?>
</body>
</html>