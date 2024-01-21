<?php
    if(!isset($_SESSION['user'])){
        redirect('logic/login.php');
    }
    $sql = "SELECT * FROM cart_products WHERE cart_id = (SELECT cart_id FROM cart WHERE user_id = :user_id)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":user_id", $_SESSION['user']->user_id);
    $stmt->execute();
    $cart = $stmt->rowCount();
    if ($cart == 0) {
        redirect('index.php');
    }
?>
    <!-- Breadcrumb Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="#">Home</a>
                    <a class="breadcrumb-item text-dark" href="#">Shop</a>
                    <span class="breadcrumb-item active">Checkout</span>
                </nav>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->


    <!-- Checkout Start -->
    <div class="container-fluid justify-content-center d-flex">
        <div class="row px-xl-5 checkoutRow">
            <div class="col-lg-8">
                <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Billing Address</span></h5>
                <div class="bg-light p-30 mb-5">
                    <div class="row">
                        <?php
                        $user = userHasAddressInfos($_SESSION['user']->user_id);
                        if($user):
                        ?>
                        <div class="col-md-6 form-group">
                            <label for="firstname">First Name</label>
                            <input class="form-control" type="text" id="firstname" disabled value="<?=$user->first_name?>">
                            <p class="checkError"></p>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="lastname">Last Name</label>
                            <input class="form-control" type="text" id="lastname" disabled value="<?=$user->last_name?>">
                            <p class="checkError"></p>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="email">E-mail</label>
                            <input class="form-control" type="text" id="email" disabled value="<?=$user->email?>">
                            <p class="checkError"></p>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="phone">Mobile No</label>
                            <input class="form-control" type="text" id="phone" disabled value="<?=$user->phone?>">
                            <p class="checkError"></p>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="address">Address</label>
                            <input class="form-control" type="text" id="address" disabled value="<?=$user->address?>">
                            <p class="checkError"></p>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="country">Country</label>
                            <input class="form-control" type="text" id="country" disabled value="<?=$user->country?>">
                            <p class="checkError"></p>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="city">City</label>
                            <input class="form-control" type="text" id="city" disabled value="<?=$user->city?>">
                            <p class="checkError"></p>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="zipcode">ZIP Code</label>
                            <input class="form-control" type="text" id="zipcode" disabled value="<?=$user->zip_code?>">
                            <p class="checkError"></p>
                        </div>
                        <?php else: ?>
                            <div class="col-md-6 form-group">
                                <label for="firstname">First Name</label>
                                <input class="form-control" type="text" id="firstname" placeholder="Mike">
                                <p class="checkError"></p>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="lastname">Last Name</label>
                                <input class="form-control" type="text" id="lastname" placeholder="Doe">
                                <p class="checkError"></p>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="email">E-mail</label>
                                <input class="form-control" type="text" id="email" placeholder="example@email.com">
                                <p class="checkError"></p>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="phone">Mobile No</label>
                                <input class="form-control" type="text" id="phone" placeholder="+381601547897">
                                <p class="checkError"></p>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="address">Address</label>
                                <input class="form-control" type="text" id="address" placeholder="123 Street">
                                <p class="checkError"></p>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="country">Country</label>
                                <input class="form-control" type="text" id="country" placeholder="Serbia">
                                <p class="checkError"></p>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="city">City</label>
                                <input class="form-control" type="text" id="city" placeholder="Belgrade">
                                <p class="checkError"></p>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="zipcode">ZIP Code</label>
                                <input class="form-control" type="text" id="zipcode" placeholder="123">
                                <p class="checkError"></p>
                            </div>
                            <a href="index.php?page=account" class="text-success">+ Add your shipping addres</a>
                        <?php endif; ?>
                        
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Order Total</span></h5>
                <div class="bg-light p-30 mb-5">
                    <div class="border-bottom">
                        <h6 class="mb-3">Products</h6>
                        <?php
                        $sql = "SELECT p.name,cp.quantity,p.product_id FROM cart_products cp
                        INNER JOIN products p ON cp.product_id = p.product_id
                        INNER JOIN cart c ON cp.cart_id = c.cart_id
                        WHERE c.user_id = :user_id";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute(['user_id' => $_SESSION['user']->user_id]);
                        $products = $stmt->fetchAll();
                        foreach ($products as $product):
                        ?>
                        <div class="d-flex justify-content-between">
                            <p data-id="<?=$product->product_id?>" class="prName"><?=$product->name?> <span class="font-weight-bold">x <span class="prQuantity" data-id="<?=$product->product_id?>"><?=$product->quantity?></span></span></p>
                            <p class="totalProductPrice" data-id="<?=$product->product_id?>"><?=countProductPriceAtCheckout($product->product_id,$product->quantity)?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="border-bottom pt-3 pb-2">
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
                    </div>
                </div>
                <div class="mb-5">
                    <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Payment</span></h5>
                    <div class="bg-light p-30">
                        <?php
                        $payments = getAll("payment_types");
                        foreach ($payments as $payment):
                        ?>
                        <div class="form-group">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" name="payment" id="<?=$payment->type?>" value="<?=$payment->payment_type_id?>">
                                <label class="custom-control-label" for="<?=$payment->type?>"><?=$payment->type?></label>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <p class="checkError"></p>
                        <button id="placeOrder" class="btn btn-block btn-primary font-weight-bold py-3">Place Order</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Checkout End -->
