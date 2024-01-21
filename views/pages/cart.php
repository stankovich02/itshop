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
                    <span class="breadcrumb-item active">Shopping Cart</span>
                </nav>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->


    <!-- Cart Start -->
    <div class="container-fluid">
        <div class="row px-xl-5 cartWrapper">
              <?php
                    $sql = "SELECT * FROM products p INNER JOIN prices pr ON p.product_id = pr.product_id
                            LEFT JOIN discounts d ON p.discount_id = d.discount_id  
                            INNER JOIN cart_products cp ON p.product_id = cp.product_id WHERE cp.cart_id = 
                            (SELECT cart_id FROM cart WHERE user_id = {$_SESSION['user']->user_id})";
                    $stmt = $conn->query($sql);
                    $products = $stmt->fetchAll();
                    $total_rows = $stmt->rowCount();
                    $total = 0;
                    $totalInCart = 0;
                    if($total_rows > 0){
                        echo " <div class='col-lg-8 table-responsive mb-5'>
                                <table class='table table-light table-borderless table-hover text-center mb-0'>
                                    <thead class='thead-dark'>
                                        <tr>
                                            <th>Image</th>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                            <th>Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody class='align-middle'>";
                          foreach ($products as $product):
                              $price =  $product->discount_id == null ? $product->price : getProductDiscountedPrice($product);
                              $total = $price * $product->quantity;
                              $totalInCart += $total;
                              ?>
                              <tr>
                                    <td class="align-middle"><img src="assets/<?=$product->image_src?>_small.png" alt="<?=$product->image_alt?>"></td>
                                  <td class="align-middle"><span class="font-weight-bold"><?=$product->name?></span></td>
                                  <td class="align-middle ProductPrice font-weight-bold" name="pr<?=$product->product_id?>">$<?=$price?></td>
                                  <td class="align-middle">
                                      <div class="input-group quantity mx-auto" style="width: 100px;">
                                          <div class="input-group-btn">
                                              <button class="btn btn-sm btn-primary btn-minus" data-id="<?=$product->product_id?>">
                                                  <i class="fa fa-minus"></i>
                                              </button>
                                          </div>
                                          <input type="text" class="form-control form-control-sm bg-secondary border-0 text-center" value="<?=$product->quantity?>">
                                          <div class="input-group-btn">
                                              <button class="btn btn-sm btn-primary btn-plus" data-id="<?=$product->product_id?>">
                                                  <i class="fa fa-plus"></i>
                                              </button>
                                          </div>
                                      </div>
                                  </td>
                                  <td class="align-middle totalProductPrice font-weight-bold" name="pr<?=$product->product_id?>">$<?=$total?></td>
                                  <td class="align-middle"><button class="btn btn-sm btn-danger deleteProductCart" data-id="<?=$product->product_id?>"><i class="fa fa-times"></i></button></td>
                              </tr>
                          <?php endforeach;?>
                    <?php
                        echo '</tbody>
                            </table>
                        </div>
                        <div class="col-lg-4">
                           <!-- <form class="mb-30" action="">
                                <div class="input-group">
                                    <input type="text" class="form-control border-0 p-4" placeholder="Coupon Code">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary">Apply Coupon</button>
                                    </div>
                                </div>
                            </form>-->
                            <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Cart Summary</span></h5>
                            <div class="bg-light p-30 mb-5">
                                <div class="border-bottom pb-2">
                                    <div class="d-flex justify-content-between mb-3">
                                        <h6>Subtotal</h6>
                                        <h6 id="cartSubTotalPrice"></h6>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <h6 class="font-weight-medium">Free shipping</h6>
                                    </div>
                                </div>
                                <div class="pt-2">
                                    <div class="d-flex justify-content-between mt-2">
                                        <h5>Total</h5>
                                        <h5 id="cartTotalPrice"></h5>
                                    </div>
                                    <a href="index.php?page=checkout"><button class="btn btn-block btn-primary font-weight-bold my-3 py-3">Proceed To Checkout</button></a>
                                </div>
                            </div>
                        </div>';
                    }
                    else{
                        echo "<h3 class='mx-auto'>You cart is empty.</h3>";
                    }
                    ?>
        </div>
    </div>
    <!-- Cart End -->
    <div class="loader-wrapper">
        <img src="assets/img/basket.png" class="loader" alt="loader">
    </div>

    <div class="Popup">
        <p class="popup-text"></p>
    </div>
<?php
}
?>



