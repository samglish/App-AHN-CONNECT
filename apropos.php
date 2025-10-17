<?php
session_start();
ob_start();
require_once "db.php";
require_once 'header.php';
require_once 'functions.php';


// Vérifier si l’utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}
?>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: iPortfolio
  * Template URL: https://bootstrapmade.com/iportfolio-bootstrap-portfolio-websites-template/
  * Updated: Mar 17 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->

  <main id="main">

    <!-- ======= Portfolio Details Section ======= -->
    <section id="portfolio-details" class="portfolio-details">
      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-8">
            <div class="portfolio-details-slider swiper">
              <div class="swiper-wrapper align-items-center">
                <div class="swiper-slide">
                  <img src="apropos/image1.jpg" alt="">
                </div>
                <div class="swiper-slide">
                  <img src="apropos/image2.jpg" alt="">
                </div>

                <div class="swiper-slide">
                <img src="apropos/image3.jpg" alt="">
                </div>

                <div class="swiper-slide">
                <img src="apropos/image4.jpg" alt="">
                </div>
                 <div class="swiper-slide">
                  <img src="apropos/image5.jpg" alt="">
                </div>

                <div class="swiper-slide">
                <img src="apropos/image6.jpg" alt="">
                </div>

                <div class="swiper-slide">
                <img src="apropos/image7.jpg" alt="">
                </div>
              </div>
              <div class="swiper-pagination"></div>
            </div>
          </div>

          </section><!-- End Services Section -->

          <!-- ======= Testimonials Section ======= -->
          <section id="testimonials" class="testimonials section-bg">
            <div class="container">
                    <div class="section-title">
                <h2>Département des Arts et Humanités Numériques</h2>
                <p>École Nationale Supérieure Polytechnique de Maroua</p>
              </div>
       Le Département des Arts et Humanités Numériques de l'École Nationale Supérieure Polytechnique de Maroua est un département dynamique et innovant, alliant arts, sciences humaines et technologies numériques. Il prépare les étudiants à un avenir où la créativité et les compétences techniques se rencontrent pour relever les défis numériques de demain.
               </div>

        </div>

      </div>
    </section><!-- End Portfolio Details Section -->

  </main><!-- End #main -->

  

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/typed.js/typed.umd.js"></script>
  <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

<?php require_once 'footer.php'; ?>
