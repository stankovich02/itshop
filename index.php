<?php
    ob_start();
    require_once 'config/setup.php';
    require_once 'config/connection.php';
    require_once 'models/functions.php';
    global $conn;
?>
<?php
if(isset($_GET["page"])):
    $page = $_GET["page"];
    if(in_array($page,pages)){
        if($page != "admin"){
            require_once 'views/fixed/header.php';
        }
        include "views/pages/" . $page . ".php";
    }
    else{
        redirect("https://www.google.com");
    }
else:
    require_once 'views/fixed/header.php';
    ?>
    <!-- Carousel Start -->
    <div class="container-fluid mb-3">
        <div class="row px-xl-5">
            <div class="col-lg-8">
                <div id="header-carousel" class="carousel slide carousel-fade mb-30 mb-lg-0" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#header-carousel" data-slide-to="0" class="active"></li>
                        <li data-target="#header-carousel" data-slide-to="1"></li>
                        <li data-target="#header-carousel" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner">
                        <div class="carousel-item position-relative active" style="height: 430px;">
                            <img class="position-absolute w-100 h-100" src="assets/img/carousel-1.jpg" style="object-fit: cover;">
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                <div class="p-3" style="max-width: 700px;">
                                    <h1 class="display-4 text-white mb-3 animate__animated animate__fadeInDown">Branded Laptops</h1>
                                    <p class="mx-md-5 px-5 animate__animated animate__bounceIn">Your most favourite laptops brands at one place!</p>
                                    <a class="btn btn-outline-light py-2 px-4 mt-3 animate__animated animate__fadeInUp" href="index.php?page=shop">Shop Now</a>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item position-relative" style="height: 430px;">
                            <img class="position-absolute w-100 h-100" src="assets/img/carousel-2.jpg" style="object-fit: cover;">
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                <div class="p-3" style="max-width: 700px;">
                                    <h1 class="display-4 text-white mb-3 animate__animated animate__fadeInDown">Gaming Headphones</h1>
                                    <p class="mx-md-5 px-5 animate__animated animate__bounceIn">Improve Your gaming experience with our gaming headphones</p>
                                    <a class="btn btn-outline-light py-2 px-4 mt-3 animate__animated animate__fadeInUp" href="index.php?page=shop">Shop Now</a>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item position-relative" style="height: 430px;">
                            <img class="position-absolute w-100 h-100" src="assets/img/carousel-3.jpg" style="object-fit: cover;">
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                <div class="p-3" style="max-width: 700px;">
                                    <h1 class="display-4 text-white mb-3 animate__animated animate__fadeInDown">Mobile Phones</h1>
                                    <p class="mx-md-5 px-5 animate__animated animate__bounceIn">Spend Your day enjoying in best smart mobile phones!</p>
                                    <a class="btn btn-outline-light py-2 px-4 mt-3 animate__animated animate__fadeInUp" href="index.php?page=shop">Shop Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="product-offer mb-30" style="height: 200px;">
                    <img class="img-fluid" src="assets/img/offer-1.jpg" alt="">
                    <div class="offer-text">
                        <h6 class="text-white text-uppercase">Save 20%</h6>
                        <h3 class="text-white mb-3">Gaming Keyboards</h3>
                        <a href="index.php?page=shop" class="btn btn-primary">Shop Now</a>
                    </div>
                </div>
                <div class="product-offer mb-30" style="height: 200px;">
                    <img class="img-fluid" src="assets/img/offer-2.jpg" alt="">
                    <div class="offer-text">
                        <h6 class="text-white text-uppercase">Save 20%</h6>
                        <h3 class="text-white mb-3">Best Computer Peripherals</h3>
                        <a href="index.php?page=shop" class="btn btn-primary">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Carousel End -->


    <!-- Featured Start -->
    <div class="container-fluid pt-5">
        <div class="row px-xl-5 pb-3">
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                    <h1 class="fa fa-check text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0">Quality Product</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                    <h1 class="fa fa-shipping-fast text-primary m-0 mr-2"></h1>
                    <h5 class="font-weight-semi-bold m-0">Free Shipping</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                    <h1 class="fas fa-exchange-alt text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0">14-Day Return</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                    <h1 class="fa fa-phone-volume text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0">24/7 Support</h5>
                </div>
            </div>
        </div>
    </div>
    <!-- Featured End -->

    <!-- Categories Start -->
    <div class="container-fluid pt-5">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Categories</span></h2>
        <div class="row px-xl-5 pb-3">
            <?php
            $categories = getAll('categories');
            foreach ($categories as $category){
                $sql = "SELECT COUNT(*) AS total FROM products WHERE category_id = {$category->category_id}";
                $row = $conn->query($sql)->fetch();
                echo "<div class='col-lg-3 col-md-4 col-sm-6 pb-1'>
                            <a class='text-decoration-none category-link' href='index.php?page=shop' data-id='" . $category->category_id . "'>
                                <div class='cat-item d-flex align-items-center mb-4'>
                                <div class='overflow-hidden' style'width: 100px; height: 100px;'>
                                    <img class='img-fluid w-75' src='assets/img/cat-" . $category->category_id . ".jpg' alt='" . $category->category_name . "'>
                                </div>
                                    <div class='flex-fill pl-3'>
                                        <h5>" . $category->category_name . "</h5>
                                        <p class='text-body'>" . $row->total . " Products</p>
                                    </div>
                                </div>
                            </a>
                      </div>";
            }
            ?>
        </div>
    </div>
    <!-- Categories End -->

    <!-- Products Start -->
    <div class="container-fluid pt-5 pb-3">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3 disc-head">Products on discount</span></h2>
        <div class="row px-xl-5">
            <?php
            $sql = GET_ALL_PRODUCTS . " AND p.discount_id IS NOT NULL";
            $products = $conn->query($sql)->fetchAll();
            foreach($products as $product):
            ?>
            <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                <div class="product-item bg-light mb-4">
                    <p class="discount-perc">-<?=$product->discount_percent?>%</p>
                    <div class="product-img position-relative overflow-hidden">
                        <img class="img-fluid w-100" src="assets/<?=$product->image_src?>_large.png" alt="<?=$product->image_alt?>">
                        <div class="product-action">
                            <a class="btn btn-outline-dark btn-square addToCart" data-id="<?=$product->product_id?>"><i class="fa fa-shopping-cart"></i></a>
                            <a class="btn btn-outline-dark btn-square addToWish" data-id="<?=$product->product_id?>"><i class="far fa-heart"></i></a>
                            <a class="btn btn-outline-dark btn-square addToCompare" data-id="<?=$product->product_id?>"><i class="fa fa-sync-alt"></i></a>
                        </div>
                    </div>
                    <div class="text-center py-4">
                        <a class="h5 text-decoration-none text-truncate" href="views/pages/product-detail.php?id=<?=$product->product_id?>"><?=$product->name?></a>

                        <div class="d-flex align-items-center justify-content-center mt-2">
                            <?=getProductActivePrice($product)?>
                            <?=getProductOldPrice($product)?>
                        </div>
                        <div class="d-flex align-items-center justify-content-center mb-1">
                            <?php
                            echo getRatingForProduct($product->product_id);
                            $number = getProductNumberofReviews($product->product_id);
                            echo "<small class='text-muted ml-2'>(" . $number . ")</small>";
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- Products End -->

    <!-- Offer Start -->
    <div class="container-fluid pt-5 pb-3">
        <div class="row px-xl-5">
            <div class="col-md-6">
                <div class="product-offer mb-30" style="height: 300px;">
                    <img class="img-fluid" src="assets/img/offer-1.jpg" alt="">
                    <div class="offer-text">
                        <h3 class="text-white mb-3">Mechanical Keyboards</h3>
                        <a href="index.php?page=shop" class="btn btn-primary">Shop Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="product-offer mb-30" style="height: 300px;">
                    <img class="img-fluid" src="assets/img/offer-2.jpg" alt="">
                    <div class="offer-text">
                        <h3 class="text-white mb-3">Setup Peripherals</h3>
                        <a href="index.php?page=shop" class="btn btn-primary">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Offer End -->

    <!-- Products Start -->
    <div class="container-fluid pt-5 pb-3">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Latest Products</span></h2>
        <div class="row px-xl-5">
            <?php
            $sql = GET_ALL_PRODUCTS . " ORDER BY p.created_at DESC LIMIT 4";
            $products = $conn->query($sql)->fetchAll();
            foreach ($products as $product):
                $discountedPrice = getProductDiscountedPrice($product);
                ?>
                <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                    <div class="product-item bg-light mb-4">
                        <p class="discount-perc"><?php $product->discount_percent == null ? print "" : print "-" . $product->discount_percent . "%"; ?></p>
                        <div class="product-img position-relative overflow-hidden">
                            <img class="img-fluid w-100" src="assets/<?=$product->image_src?>_large.png" alt="<?=$product->image_alt?>">
                            <div class="product-action">
                                <?php if($product->in_stock == 1): ?>
                                    <a class="btn btn-outline-dark btn-square addToCart" data-id="<?=$product->product_id?>"><i class="fa fa-shopping-cart"></i></a>
                                <?php endif; ?>
                                <a class="btn btn-outline-dark btn-square addToWish" data-id="<?=$product->product_id?>"><i class="far fa-heart"></i></a>
                                <a class="btn btn-outline-dark btn-square addToCompare" data-id="<?=$product->product_id?>"><i class="fa fa-sync-alt"></i></a>
                            </div>
                        </div>
                        <div class="text-center py-4">
                            <a class="h5 text-decoration-none text-truncate" href="index.php?page=product-detail&id=<?=$product->product_id?>"><?=$product->name?></a>
                            <div class="d-flex align-items-center justify-content-center mt-2">
                               <?=getProductActivePrice($product)?>
                               <?=getProductOldPrice($product)?>
                            </div>
                            <div class="d-flex align-items-center justify-content-center mb-1">
                                <?php
                                echo getRatingForProduct($product->product_id);
                                $number = getProductNumberofReviews($product->product_id);
                                echo "<small class='text-muted ml-2'>(" . $number . ")</small>";
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    endif;
    ?>
    <!-- Products End -->
    <div class="modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content uv-modal">
              <div class="modal-body mBody">
              </div>
          </div>
      </div>
  </div>

    <div class="Popup">
        <p class="popup-text"></p>
    </div>


<?php
if(isset($_GET["page"])){
    $page = $_GET["page"];
    if($page != "admin") {
        require_once 'views/fixed/footer.php';
    }
}
else{
    require_once 'views/fixed/footer.php';
}
?>