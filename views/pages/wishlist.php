<?php
if (!isset($_SESSION['user'])){
     redirect('models/login.php');
}
else{
?>
        <!-- Breadcrumb Start -->
<div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="index.php">Home</a>
                    <span class="breadcrumb-item active">Wishlist</span>
                </nav>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->
<!-- Wishlist Start -->
    <div class="container">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Wishlist</span></h2>
        <div class="row wish-wrapper">
            <?php
            $sql = "SELECT * FROM products p INNER JOIN prices pr ON p.product_id = pr.product_id 
            LEFT JOIN discounts d ON p.discount_id = d.discount_id
            INNER JOIN wishlist_products wp ON p.product_id = wp.product_id WHERE wp.wishlist_id = 
            (SELECT wishlist_id FROM wishlist WHERE user_id = {$_SESSION['user']->user_id})";
            $stmt = $conn->query($sql);
            $products = $stmt->fetchAll();
            $total_rows = $stmt->rowCount();
            if($total_rows > 0)
                foreach ($products as $product):
                $discountedPrice = getProductDiscountedPrice($product);
                $activePrice = $product->discount_id == null ? $product->price : $discountedPrice;
                $oldPrice = $product->discount_id == null ? "" : $product->price . "&euro;";
            ?>
            <div class="col-lg-4 mb-3">
                <div class="card text-center">
                    <img src="assets/<?=$product->image_src?>_large.png" class="card-img-top" alt="<?=$product->image_alt?>">
                    <div class="card-body">
                        <h5 class="card-title"><?=$product->name?></h5>

                        <div class="d-flex align-items-center justify-content-center mt-2">
                            <h5 class="ml-2"><?=$activePrice?>&euro;</h5>
                            <h6 class="text-muted ml-2 oldPrice"><del><?=$oldPrice?></del></h6>
                        </div>
                        <div class="btns-wrapper d-flex justify-content-between">
                            <a href="index.php?page=product-detail&id=<?=$product->product_id?>" class="btn btn-primary">View details</a>
                            <a href="../../models/deleteProductFromWishlist.php" data-id="<?=$product->product_id?>" class="btn btn-danger deleteProductWish">Remove product</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                endforeach;
            else{
                echo "<h3 class='mx-auto'>You don't have products in your wishlist.</h3>";
            }
            ?>
        </div>
    </div>
    <div class="Popup">
        <p class="popup-text"></p>
    </div>
<?php
}
?>


