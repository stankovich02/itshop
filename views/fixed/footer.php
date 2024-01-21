<?php
    require_once 'config/connection.php';
    global $conn;
?>
<!-- Footer Start -->
  <div class="container-fluid bg-dark text-secondary mt-5 pt-5">
        <div class="row px-xl-5 pt-5">
            <div class="col-lg-4 col-md-12 mb-5 pr-3 pr-xl-5">
                <h5 class="text-secondary text-uppercase mb-4">Get In Touch</h5>
                <p class="mb-4">Feel free to contact us at any time and ask us anything.</p>
                <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>Knez Mihajlova 25, Belgrade, Serbia</p>
                <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>info@itshop.com</p>
                <p class="mb-0"><i class="fa fa-phone-alt text-primary mr-3"></i>+381 11 72574</p>
            </div>
            <div class="col-lg-8 col-md-12">
                <div class="row justify-content-between">
                    <div class="col-md-4 mb-5">
                        <h5 class="text-secondary text-uppercase mb-4">Quick Shop</h5>
                        <div class="d-flex flex-column justify-content-start">

                            <?php
                            $query = "SELECT * FROM navigation";
                            $result = $conn->query($query);
                            foreach ($result as $row) {
                                echo " <a class='text-secondary mb-2' href='". $row->href ."'><i class='fa fa-angle-right mr-2'></i>$row->name</a>";
                            }
                            ?>
                            <a class="font-weight-bold mb-2 text-danger" href="sitemap.xml"><i class="fa fa-angle-right mr-2"></i>Sitemap</a>
                            <a class="font-weight-bold mb-2 text-danger" href="documentation.pdf"><i class="fa fa-angle-right mr-2"></i>Documentation</a>
                        </div>
                    </div>
                    <div class="col-md-6 mb-5">
                        <h5 class="text-secondary text-uppercase mb-4">Newsletter</h5>
                        <p>Sign up to receive the most recent product news!</p>
                        <form action="">
                            <div class="input-group">
                                <input type="text" class="form-control emailSignUp" placeholder="Your Email Address">
                                <div class="input-group-append">
                                    <button class="btn btn-primary news-signUp">Sign Up</button>
                                </div>
                            </div>
                                <p id="newsError"></p>
                        </form>
                        <h6 class="text-secondary text-uppercase mt-4 mb-3">Follow Us</h6>
                        <div class="d-flex">
                            <a class="btn btn-primary btn-square mr-2" href="https://twitter.com/"><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-primary btn-square mr-2" href="https://www.facebook.com/"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-primary btn-square mr-2" href="https://www.linkedin.com/"><i class="fab fa-linkedin-in"></i></a>
                            <a class="btn btn-primary btn-square" href="https://www.instagram.com/"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row border-top mx-xl-5 py-4" style="border-color: rgba(256, 256, 256, .1) !important;">
            <div class="col-md-6 px-xl-0">
                <p class="mb-md-0 text-center text-md-left text-secondary">
                    &copy; <a class="text-primary">IT Shop</a>. All Rights Reserved.
                    <a class="text-primary">Marko Stankovic 41/21</a>
                </p>
            </div>
            <div class="col-md-6 px-xl-0 text-center text-md-right">
                <img class="img-fluid" src="assets/img/payments.png" alt="">
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>

    <div id="compareWrapper">
        <div class="compareProduct">
        </div>
        <div class="compareProduct">

        </div>
        <div class="compareProduct">  
        </div>
    <div>
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Contact Javascript File -->
    <script src="mail/jqBootstrapValidation.min.js"></script>
    <script src="mail/contact.js"></script>

    <!-- Template Javascript -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>