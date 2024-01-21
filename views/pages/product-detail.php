<?php
require_once 'models/functions.php';
require_once 'config/connection.php';
global $conn;

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $sql = GET_ALL_PRODUCTS . " AND p.product_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $product = $stmt->fetch();
    $discount = $product->discount_id == null ? "" : "<p class='discount-perc-single'>-" . $product->discount_percent . "%</p>";
    $price = $product->price;
    $discountedPrice = getProductDiscountedPrice($product);
    $activePrice = $product->discount_id == null ? $product->price . "&euro;" : $discountedPrice . "&euro;";
    $oldPrice = $product->discount_id == null ? "" : $product->price . "&euro;";
}
else{
    redirect("index.php");
}
require_once 'views/fixed/header.php';
global $conn;
?>
    <!-- Breadcrumb Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="index.php">Home</a>
                    <a class="breadcrumb-item text-dark" href="index.php?page=shop">Shop</a>
                    <span class="breadcrumb-item active"><?=$product->name?></span>
                </nav>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->


    <!-- Shop Detail Start -->
    <div class="container-fluid pb-5">
        <div class="row px-xl-5">
            <div class="col-lg-5 mb-30">
               <!-- <div id="product-carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner bg-light">
                        <div class="carousel-item active">-->
                <figure class="w-100" id="pr-img-wrapper">
                    <img class="img-fluid" src="assets/<?=$product->image_src?>_large.png" alt="<?=$product->image_alt?>"/>
                </figure>

                       <!-- </div>
                    </div>
                    <a class="carousel-control-prev" href="#product-carousel" data-slide="prev">
                        <i class="fa fa-2x fa-angle-left text-dark"></i>
                    </a>
                    <a class="carousel-control-next" href="#product-carousel" data-slide="next">
                        <i class="fa fa-2x fa-angle-right text-dark"></i>
                    </a>
                </div>-->
            </div>

            <div class="col-lg-7 h-auto mb-30">
                <div class="h-100 bg-light p-30">
                    <h3><?= $product->name ?></h3>
                    <div class="d-flex mb-3">
                        <div class="text-primary mr-2">
                            <?php
                            echo getRatingForProduct($product->product_id);
                            ?>
                        </div>
                        <small class="pt-1">
                            <?php
                            $number = getProductNumberofReviews($product->product_id);
                            if($number == 0){
                                echo "No reviews";
                            }
                            if($number == 1){
                                echo "1 review";
                            }
                            if($number > 1){
                                echo "$number reviews";
                            }

                            ?>
                        </small>
                    </div>
                    <h6 class="productInStock mb-3">
                        <?php
                        if($product->in_stock > 0){
                            echo "Availability: <span class='inStock'>In stock</span>";
                        }
                        else{
                            echo "Availability: <span class='OutOfStock'>Out of stock</span>";
                        }
                        ?>
                    </h6>
                    <h5 class="font-weight-semi-bold mb-4">
                        <?php
                        $sql = "SELECT category_name FROM categories WHERE category_id = :id";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':id', $product->category_id);
                        $stmt->execute();
                        $category = $stmt->fetch();
                        echo "Category: " . "$category->category_name";
                        ?>
                    </h5>
                    <div class="product-price">
                        <h3 class="font-weight-semi-bold mb-4 activePrice"><?= $activePrice?></h3>
                        <h3 class="font-weight-semi-bold mb-4 oldPrice"><del><?= $oldPrice?></del></h3>
                    </div>
                    <?=$discount?>
                    <?php if($product->in_stock > 0):?>
                    <div class="d-flex align-items-center mb-4 pt-2">
                        <div class="input-group quantity mr-3" style="width: 130px;">
                            <div class="input-group-btn d-flex">
                                <button class="btn btn-primary btn-minus" data-id="<?=$product->product_id?>">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                            <input type="text" id="prQuantity" class="form-control bg-secondary border-0 text-center" value="1">
                            <div class="input-group-btn d-flex">
                                <button class="btn btn-primary btn-plus" data-id="<?=$product->product_id?>">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <button class="btn btn-primary px-3 addToCartSingleProduct" data-id="<?=$product->product_id?>"><i class="fa fa-shopping-cart mr-1"></i> Add To
                            Cart</button>
                    </div>
                    <?php endif;?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row px-xl-5" id="tabs-spec">
            <div class="col">
                <div class="bg-light p-30">
                    <div class="nav nav-tabs mb-4 justify-content-center">
                        <a class="nav-item nav-link text-dark active" data-toggle="tab" href="#tab-pane-1">Specifications</a>
                        <a class="nav-item nav-link text-dark" data-toggle="tab" href="#tab-pane-2">Description</a>
                        <a class="nav-item nav-link text-dark" data-toggle="tab" href="#tab-pane-3">Reviews (<?=$number?>)</a>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab-pane-1">
                            <table class="mx-auto">
                                <thead>
                                </thead>
                                <tbody>
                                <?php
                                $sql = "SELECT ch.name as Name,ch.characteristic_id as Id FROM categories c INNER JOIN categories_characteristics cc ON c.category_id = cc.category_id INNER JOIN characteristics ch ON cc.characteristic_id = ch.characteristic_id
                              WHERE c.category_id = :category_id ORDER BY display_order ASC";
                                $stmt = $conn->prepare($sql);
                                $stmt->bindParam(':category_id', $product->category_id);
                                $stmt->execute();
                                $result = $stmt->fetchAll();
                                foreach ($result as $row) {
                                    echo "<tr class='spec-row'>";
                                    echo "<td class='spec-name'>$row->Name</td>";
                                    $sql2 = "SELECT ch.name as Name,ch.value as Value FROM characteristics ch INNER JOIN products_characteristics pc WHERE ch.parent_characteristic_id = $row->Id AND pc.product_id = $product->product_id AND pc.characteristic_id = ch.characteristic_id";
                                    $result2 = $conn->query($sql2)->fetchAll();
                                    echo "<td class='spec-value'>";
                                    foreach ($result2 as $row2) {
                                        if($row2->Name != ''){
                                            echo "<span class='val-name'>$row2->Name</span>". ": " . $row2->Value;
                                            echo "<br/>";
                                        }
                                        else{
                                                echo $row2->Value;
                                                echo "<br/>";
                                        }

                                    }
                                    echo "</td>";

                                    echo "</tr>";
                                }
                                ?>
                                <script>
                                    let specRows = document.querySelectorAll('.spec-row');
                                    specRows.forEach(function (row) {
                                        let specValue = row.querySelector('.spec-value');
                                       if (specValue.innerHTML === '') {
                                          /*  row.style.display = 'none';*/
                                            row.remove();
                                        }
                                    });
                                </script>
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade" id="tab-pane-2">
                            <h4 class="mb-3 text-center">Product Description</h4>
                            <p><?="$product->description"?></p>
                        </div>
                        <div class="tab-pane fade" id="tab-pane-3">
                            <div class="row">
                                <div class="col-md-6 reviews-wrapper">
                                    <h4 class="mb-4">
                                     <?php
                                     if($number == 0){
                                         echo "No reviews";
                                     }
                                     if($number == 1){
                                         echo "1 review";
                                     }
                                     if($number > 1){
                                         echo "$number reviews";
                                     }
                                     ?>
                                        for <?=$product->name?>
                                    </h4>
                                    <?php
                                    $reviews =  getProductReviews($product->product_id);
                                    foreach($reviews as $review){
                                        $stars = getProductReviewRate($review->review_rate);
                                        echo "<div class='media mb-4'>
                                        <div class='media-body'>
                                            <h6>$review->first_name  $review->last_name<small> - <i>$review->postedDate</i></small></h6>
                                            <div class='text-primary mb-2 user-rate'>
                                           $stars
                                            </div>
                                            <p>$review->review_text</p>
                                        </div>
                                    </div>";
                                    }
                                    ?>


                                </div>
                                <div class="col-md-6">
                                    <h4 class="mb-4">Leave a review</h4>
                                    <small>Your email address will not be published. Required fields are marked *</small>
                                    <div class="d-flex my-3">
                                        <?php
                                        if(isset($_SESSION['user'])):
                                            ?>
                                            <p class="mb-0 mr-2 rating-wrapper">Your Rating * :</p>
                                            <div class="text-primary single-pr-rating">
                                                <i class="far fa-star" data-rate="1"></i>
                                                <i class="far fa-star" data-rate="2"></i>
                                                <i class="far fa-star" data-rate="3"></i>
                                                <i class="far fa-star" data-rate="4"></i>
                                                <i class="far fa-star" data-rate="5"></i>
                                            </div>
                                        <?php
                                        endif;
                                        ?>

                                    </div>
                                    <p class="rate-error"></p>
                                    <form>
                                        <div class="form-group">
                                            <label for="message">Your Review *</label>
                                            <?php
                                            if(isset($_SESSION['user'])):
                                                ?>
                                                <textarea id="message" cols="30" rows="5" class="form-control"></textarea>
                                            <?php
                                            else:
                                                ?>
                                                <textarea id="message" cols="30" rows="5" class="form-control" disabled="true"></textarea>
                                            <?php
                                            endif;
                                            ?>
                                            <p class="error"></p>
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Your Full Name *</label>
                                            <?php
                                            if(isset($_SESSION['user'])):
                                            ?>
                                            <input type="text" class="form-control" disabled="true" id="name" value="<?=$_SESSION['user']->first_name ." ". $_SESSION['user']->last_name ?>">
                                            <?php
                                            else:
                                            ?>
                                            <input type="text" class="form-control" id="name" value="" disabled="true">
                                            <p class="error"></p>
                                            <?php
                                            endif;
                                            ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Your Email *</label>
                                            <?php
                                            if(isset($_SESSION['user'])):
                                            ?>
                                            <input type="email" class="form-control" disabled="true" id="email" value="<?=$_SESSION['user']->email?>">
                                            <?php
                                            else:
                                            ?>
                                            <input type="email" class="form-control" id="email" value="" disabled="true">
                                            <p class="error"></p>
                                            <?php
                                            endif;
                                            ?>
                                        </div>
                                        <div class="form-group mb-0">
                                            <?php
                                            if(isset($_SESSION['user'])):
                                                ?>
                                                <input type="button" value="Leave Your Review" id="postReview" data-prid=<?=$product->product_id?> class="btn btn-primary px-3">
                                            <?php
                                            else:
                                                ?>
                                                <p class="error">You must log in to rate a product and leave a review!</p>
                                            <?php
                                            endif;
                                            ?>

                                        </div>
                                        <p id="review-msg"></p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Shop Detail End -->


    <!-- Products Start -->
    <div class="container-fluid py-5">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Related products</span></h2>
        <div class="row px-xl-5">
            <div class="col">
                <div class="owl-carousel related-carousel">
                    <?php
                    $sql = GET_ALL_PRODUCTS . " AND p.product_id != :product_id AND p.category_id = :category_id ";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':product_id', $product->product_id);
                    $stmt->bindParam(':category_id', $product->category_id);
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                    foreach ($result as $product):
                    ?>
                    <div class="product-item bg-light">
                        <?= getProductDiscount($product); ?>
                        <div class="product-img position-relative overflow-hidden">
                            <img class="img-fluid w-100" src="assets/<?= $product->image_src ?>_large.png" alt="">
                            <div class="product-action">
                                <?php if($product->in_stock > 0):?>
                                <a class="btn btn-outline-dark btn-square addToCart" data-id="<?=$product->product_id?>"><i class="fa fa-shopping-cart"></i></a>
                                <?php endif;?>
                                <a class="btn btn-outline-dark btn-square addToWish" data-id="<?=$product->product_id?>"><i class="far fa-heart"></i></a>
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
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Products End -->
    <div class="loader-wrapper">
        <img src="assets/img/basket.png" class="loader" alt="loader">
    </div>
    <div class="Popup">
        <p class="popup-text"></p>
    </div>