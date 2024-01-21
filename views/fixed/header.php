<?php
    /*require_once $_SERVER['DOCUMENT_ROOT'].'/IT Shop/config/setup.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/IT Shop/config/connection.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/IT Shop/models/functions.php';*/
    require_once 'models/functions.php';
/*    $actual_link = $_SERVER["REQUEST_URI"]; 
	$title = explode("/",$actual_link); // 23 this starting position of string 
    $title = makeTitle(end($title));*/
   
    global $conn;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>IT Shop</title> 
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="assets/img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">  

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="assets/css/priceslider.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="assets/css/style.css" rel="stylesheet">
    <!-- CSS -->
    <link href="assets/css/ms-style.css" rel="stylesheet">
</head>

<body>
    <!-- Topbar Start -->
    <div class="container-fluid">
        <div class="row bg-secondary py-1 px-xl-5">
            <div class="col-lg-12 text-center text-lg-right">

                <div class="d-flex align-items-center justify-content-end">
                    <?php
                    if(!isset($_SESSION['user'])){
                        echo " <a href='models/register.php'>
                        <button type='button' class='btn btn-primary'>Register</button>
                    </a>";
                        echo " <a href='models/login.php' id='login-link'>
                        <button type='button' class='btn btn-secondary'>Log in</button>
                    </a>";
                    }
                    ?>
                    <?php
                        if(isset($_SESSION['user'])){
                            $user = $_SESSION['user'];
                            echo "<div id='user-acc'><p>Welcome, ". $user->first_name ."</p><a href='index.php?page=account'>";
                            if(getUserAvatar($user->user_id) == null){
                                echo " <i class='fas fa-user acc-icon'></i>";
                            }
                            else{
                                echo "<figure id='profilePhoto'><img src='assets/".getUserAvatar($user->user_id) ."' alt='user' class='img-fluid'></figure>";
                            }
                            echo "</a></div>
                             <a href='models/logout.php' id='login-link'>
                                    <button type='button' class='btn btn-secondary'>Log out</button>
                                </a>";
                        }
                    ?>
                </div>
                <div class="d-inline-flex align-items-center d-block d-lg-none">
                    <a href="index.php?page=wishlist" class="btn px-0 ml-2">
                        <i class="fas fa-heart text-dark"></i>
                        <span class="badge text-dark border border-dark rounded-circle numberProductsWish" style="padding-bottom: 2px;">
                        <?php
                        if(isset($_SESSION['user'])){
                            $user = $_SESSION['user'];
                            echo getUsersNumberOfProductsInWishlist($user->user_id);
                        }
                        else{
                            echo 0;
                        }
                        ?>
                        </span>
                    </a>
                    <a href="index.php?page=cart" class="btn px-0 ml-2">
                        <i class="fas fa-shopping-cart text-dark"></i>
                        <span class="badge text-dark border border-dark rounded-circle numberProductsCart" style="padding-bottom: 2px;">
                          <?php
                          if(isset($_SESSION['user'])){
                              $user = $_SESSION['user'];
                              echo getUsersNumberOfProductsInCart($user->user_id);
                          }
                          else{
                              echo 0;
                          }
                          ?>
                        </span>
                    </a>
                </div>
            </div>
        </div>
        <div class="row align-items-center bg-light py-3 px-xl-5 d-none d-lg-flex">
            <div class="col-lg-3">
                <a href="index.php" class="text-decoration-none">
                    <span class="h1 text-uppercase text-primary bg-dark px-2">It</span>
                    <span class="h1 text-uppercase text-dark bg-primary px-2 ml-n1">Shop</span>
                </a>
            </div>
            <div class="col-lg-5 col-6 text-left px-0">
                <form action="">
                    <div class="input-group">
                        <input type="text" id="search" autocomplete="off" class="form-control" placeholder="Search for products">
                        <div class="input-group-append">
                            <span class="input-group-text bg-transparent text-primary">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                    </div>
                </form>
                <div id="searchedProducts">

                </div>
            </div>
            <div class="col-lg-4 col-2 text-right">
                <p class="m-0">Customer Service</p>
                <h5 class="m-0">+381 11 72575</h5>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid bg-dark mb-30">
        <div class="row px-xl-5 justify-content-end">
            <div class="col-lg-12">
                <nav class="navbar navbar-expand-lg bg-dark navbar-dark py-3 py-lg-0 px-0">
                    <a href="index.php" class="text-decoration-none d-block d-lg-none">
                        <span class="h1 text-uppercase text-dark bg-light px-2">It</span>
                        <span class="h1 text-uppercase text-light bg-primary px-2 ml-n1">Shop</span>
                    </a>
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <div class="navbar-nav mr-auto py-0">
                         <!--   <a href="contact.html" class="nav-item nav-link">Contact</a>-->
                            <?php
                            $query = "SELECT * FROM navigation";
                            $result = $conn->query($query);
                            foreach ($result as $row) {
                                echo "<a href='". $row->href . "' class='nav-item nav-link link'>" . $row->name . "</a>";
                            }
                            if(isset($_SESSION['user']) && $_SESSION['user']->role_id == 1){
                                echo "<a href='index.php?page=admin' class='nav-item nav-link link'>Admin</a>";
                            }
                            ?>
                        </div>
                        <div class="navbar-nav ml-auto py-0 d-none d-lg-block">
                            <a href="index.php?page=wishlist" class="btn px-0">
                                <i class="fas fa-heart text-primary"></i>
                                <span class="badge text-secondary border border-secondary rounded-circle numberProductsWish" style="padding-bottom: 2px;">
                                  <?php
                                  if(isset($_SESSION['user'])){
                                      $user = $_SESSION['user'];
                                      echo getUsersNumberOfProductsInWishlist($user->user_id);
                                  }
                                  else{
                                      echo 0;
                                  }
                                  ?>
                                </span>
                            </a>
                            <a href="index.php?page=cart" class="btn px-0 ml-3">
                                <i class="fas fa-shopping-cart text-primary"></i>
                                <span class="badge text-secondary border border-secondary rounded-circle numberProductsCart" style="padding-bottom: 2px;">
                                     <?php
                                     if(isset($_SESSION['user'])){
                                         $user = $_SESSION['user'];
                                         echo getUsersNumberOfProductsInCart($user->user_id);
                                     }
                                     else{
                                         echo 0;
                                     }
                                     ?>
                                </span>
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!-- Navbar End -->