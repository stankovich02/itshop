<?php
require_once '../config/connection.php';
require_once 'functions.php';
if(isset($_POST['search'])){
    $search = $_POST['search'];
    if($search == ""){
        echo "<p id='noProducts'>No products found!</p>";
        return;
    }
    else if(!empty($search)){
        $search = trim($search);
        $search = preg_replace('/\s+/', ' ', $search);
        $sql = GET_ALL_PRODUCTS . " AND p.name LIKE '%$search%'";
        $stmt = $conn->query($sql);
        $products = $stmt->fetchAll();
        $output = '';
        if(count($products) == 0){
            echo "<p id='noProducts'>No products found!</p>";
        }
        else{
            foreach($products as $product){
                $discountedPrice = getProductDiscountedPrice($product);
                $activePrice = $product->discount_id == null ? $product->price : $discountedPrice;
                $oldPrice = $product->discount_id == null ? "" : $product->price . "&euro;";
                $discount = $product->discount_id == null ? "" : "<p class='discount-perc'>-" . $product->discount_percent . "%</p>";
                $rating = getRatingForProduct($product->product_id);
                $number = getProductNumberofReviews($product->product_id);
                $output .= "<div class='singleProduct'>
                        <a href='index.php?page=product-detail&id=" . $product->product_id . "'>
                            <img src='assets/" . $product->image_src . "_small.png' alt='" . $product->image_alt . "'>
                        </a>
                        <div class='searchedProductInfo'>
                        <a href='index.php?page=product-detail&id=" . $product->product_id . "'>
                            <p class='text-dark productNameSearch'>" . $product->name . "</p>
                        </a>
                            <div class='searchedProductPrice'>
                                <h5 class='searchedProductActivePrice'>" . $activePrice . "&euro;</h5>
                                <h5 class='searchedProductOldPrice'><del>" . $oldPrice . "</del></h5>                   
                            </div>
                        </div>
                        <div class='searchedProductDiscount'>
                         $discount
                         <div id='prRating'>
                         $rating                      
                         <small class='text-muted ml-2'>(" . $number . ")</small>
                         </div>                         
                        </div>                  
                    </div><hr class='splitLine'>";
            }
            echo $output;
        }
    }

}



