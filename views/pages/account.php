<?php
$userID = $_SESSION['user'];
?>
<div class="container">
    <div class="row gutters">
        <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
            <div class="card h-100">
                <div class="card-body">
                    <div class="account-settings">
                        <div class="user-profile">
                            <figure class="user-avatar">
                                <?php
                                if(getUserAvatar($userID->user_id) == null):
                                ?>
                                <img src="assets/img/user.png" alt="Maxwell Admin">
                                <?php
                                else:
                                ?>
                                <img src="assets/<?=getUserAvatar($userID->user_id)?>" alt="Maxwell Admin">
                                <?php
                                endif;
                                ?>
                                <form action="../../models/addUserAvatar.php" method="POST" enctype="multipart/form-data" id="uploadForm">
                                    <input type="file" name="avatar" id="file" class="inputfile" />
                                    <label for="file">
                                        <i class="fas fa-camera addPhoto"></i>
                                    </label>
                                </form>

                            </figure>
                            <h5 class="user-name"><?=$userID->first_name . ' ' . $userID->last_name?></h5>
                        </div>
                        <div id="profile-tabs">
                            <button class="btn btn-primary btn-block" id="editProfile">Profile info</button>
                            <button class="btn btn-primary btn-block" id="orderHistory">Order history</button>
                        </div>
                        <p class="text-center mt-3" id="photoMsg"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
            <div class="card h-100">
                <div class="card-body" id="userInfo">
                    <div class="row gutters">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <h6 class="mb-2 detailsHead">Personal Details:</h6>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="firstName">First Name</label>
                                <input type="text" class="form-control" id="firstName" disabled value="<?=$user->first_name?>">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="lastName">Last Name</label>
                                <input type="text" class="form-control" id="lastName" disabled value="<?=$user->last_name?>">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" disabled value="<?=$user->email?>">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <?php
                                $user = userHasAddressInfos($_SESSION['user']->user_id);
                                if($user):
                                $sql = "SELECT * FROM users u
                                INNER JOIN users_billing_adresses uba ON u.user_id = uba.user_id
                                WHERE u.user_id = :user_id";
                                $stmt = $conn->prepare($sql);
                                $stmt->bindParam(":user_id", $_SESSION['user']->user_id);
                                $stmt->execute();
                                $user = $stmt->fetch();
                                ?>
                                <input type="text" class="form-control" id="phone" disabled value="<?=$user->phone?>">
                                <?php else: ?>
                                <input type="text" class="form-control" id="phone" placeholder="Enter phone number">
                                <?php endif; ?>
                                <p class="checkError"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row gutters">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <h6 class="mt-3 mb-2">Address:</h6>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="address">Address</label>
                                <?php
                                if($user):
                                ?>
                                <input type="text" class="form-control" id="address" disabled value="<?=$user->address?>">
                                <?php else: ?>
                                <input type="text" class="form-control" id="address" placeholder="Enter Street">
                                <?php endif; ?>
                                <p class="checkError"></p>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="country">Country</label>
                                <?php
                                if($user):
                                ?>
                                <input type="text" class="form-control" id="country" disabled value="<?=$user->country?>">
                                <?php else: ?>
                                <input type="text" class="form-control" id="country" placeholder="Enter Country">
                                <?php endif; ?>
                                <p class="checkError"></p>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="city">City</label>
                                <?php
                                if($user):
                                ?>
                                <input type="text" class="form-control" id="city" disabled value="<?=$user->city?>">
                                <?php else: ?>
                                <input type="text" class="form-control" id="city" placeholder="Enter City">
                                <?php endif; ?>
                                <p class="checkError"></p>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="zip">Zip Code</label>
                                <?php
                                if($user):
                                ?>
                                <input type="text" class="form-control" id="zip" disabled value="<?=$user->zip_code?>">
                                <?php else: ?>
                                <input type="text" class="form-control" id="zip" placeholder="Enter Zip Code">
                                <?php endif; ?>
                                <p class="checkError"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row gutters">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="text-right">
                                <?php
                                if($user):
                                ?>
                                <button type="button" id="editInfos" name="edit" class="btn btn-secondary">Edit</button>
                                <button type="button" id="updateInfos" name="update" class="btn btn-primary">Update</button>
                                <?php else: ?>
                                <button type="button" id="updateInfos" name="update" class="btn btn-primary">Update</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="orders">
                    <div class="row gutters">
                        <?php
                        $sql = "SELECT * FROM orders o
                                INNER JOIN order_items os ON o.order_id = os.order_id
                                WHERE o.user_id = :user_id
                                GROUP BY o.order_id";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(":user_id", $_SESSION['user']->user_id);
                        $stmt->execute();
                        $orders = $stmt->fetchAll();
                        if($orders):
                        ?>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <h6 class="mb-2 detailsHead">Your orders:</h6>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Order Date</th>
                                            <th>Order Total</th>
                                            <th>Order Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach($orders as $order):
                                        ?>
                                        <tr>
                                            <td><?=$order->order_id?></td>
                                            <td><?=$order->created_at?></td>
                                            <td><?=$order->total_price?></td>
                                            <td><a href="" class="moreDetails">Details</a></td>
                                        </tr>
                                        <tr class="order-details">
                                        <td colspan="4">
                                        <?php
                                        $sql2 = "SELECT * FROM order_items os INNER JOIN products p ON os.product_id = p.product_id WHERE os.order_id = :order_id";
                                        $stmt2 = $conn->prepare($sql2);
                                        $stmt2->bindParam(":order_id", $order->order_id);
                                        $stmt2->execute();
                                        $orderItems = $stmt2->fetchAll();
                                        foreach($orderItems as $orderitem):
                                        ?>
                                        <div class="singleOrderItem">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <img src="assets/<?=$orderitem->image_src?>_small.png" alt="product" class="img-fluid">
                                                </div>
                                                <div class="col-md-9">
                                                    <h5><?=$orderitem->name?></h5>
                                                    <p>Quantity: <?=$orderitem->quantity?></p>
                                                    <p>Price: <?=$orderitem->product_price?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                        </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php else: ?>
                            <h3 class="text-center  mx-auto pt-3">You have no orders yet.</h3>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="Popup">
    <p class="popup-text"></p>
</div>

