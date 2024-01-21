 <!-- Breadcrumb Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="index.php">Home</a>
                    <span class="breadcrumb-item active">Shop</span>
                </nav>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->


    <!-- Shop Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <!-- Shop Sidebar Start -->
            <div class="col-lg-3 col-md-4">
                <!-- Price Start -->
                <form id="filterForm">
                <div class="bg-light p-4 mb-30">
                <h5 class="section-title position-relative text-uppercase mb-3"><span class="pr-3">Filter by category</span></h5>
                        <?php
                        $categories = getCategoriesFilter();
                        foreach ($categories as $row): ?>
                        <div class='custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3'>
                            <input type="checkbox" data-id="<?=$row->category_id?>" name="category" class="custom-control-input category-filter" id="<?= $row->category_name ?>">
                            <label class="custom-control-label" for="<?=$row->category_name?>"><?=$row->category_name?></label>
                            <span class="badge border font-weight-normal"><?=$row->Number?></span>
                        </div>
                        <?php endforeach; ?>
                </div>
                    <div class="bg-light p-4 mb-30">
                        <h5 class="section-title position-relative text-uppercase mb-3"><span class="pr-3">Filter by brand</span></h5>
                    <?php
                    $brands = getBrandsFilter();
                    foreach ($brands as $row): ?>
                        <div class='custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3'>
                            <input type="checkbox" data-id="<?=$row->brand_id?>" name="brand" class="custom-control-input brand-filter" id="<?= $row->brand_name ?>">
                            <label class="custom-control-label" for="<?=$row->brand_name?>"><?=$row->brand_name?></label>
                            <span class="badge border font-weight-normal"><?=$row->Number?></span>
                        </div>
                    <?php endforeach; ?>
                    </div>
                </form>


                <!-- Price End -->

                <!-- Color Start -->
                <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Filter by price</span></h5>
                <div class="bg-light p-4 mb-30">
                    <form>
                        <div class="wrapper">
                            <div class="price-input">
                                <div class="field">
                                    <span>Min</span>
                                    <input type="number" class="input-min" value="0">
                                </div>
                                <div class="separator">-</div>
                                <div class="field">
                                    <span>Max</span>
                                    <input type="number" class="input-max" value="3000">
                                </div>
                            </div>
                            <div class="slider">
                                <div class="progress"></div>
                            </div>
                            <div class="range-input">
                                <input type="range" class="range-min" min="0" max="3000" value="0" step="100">
                                <input type="range" class="range-max" min="0" max="3000" value="3000" step="100">
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Color End -->
            </div>
            <!-- Shop Sidebar End -->


            <!-- Shop Product Start -->
            <div class="col-lg-9 col-md-8 main-shop-wrapper ">
                <div class="row mb-3">
                    <div class="col-12 pb-1">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <button class="btn btn-sm btn-light"><i class="fa fa-th-large"></i></button>
                                <button class="btn btn-sm btn-light ml-2"><i class="fa fa-bars"></i></button>
                            </div>
                            <div class="ml-2">
                                <!-- <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown">Sorting</button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#">Latest</a>
                                        <a class="dropdown-item" href="#">Popularity</a>
                                        <a class="dropdown-item" href="#">Best Rating</a>
                                    </div>
                                </div> -->
                                <label for="product-sort">Sort by:</label>
                                <select class="form-select px-2 py-2 text-center" id="product-sort" name="sort" aria-label="Default select example">
                                    <option selected value="default">Default</option>
                                    <option value="name-asc">Name A-Z</option>
                                    <option value="name-desc">Name Z-A</option>
                                    <option value="price-asc">Price ASC</option>
                                    <option value="price-desc">Price DESC</option>
                                    <option value="newest">Newest</option>
                                </select>
                                <!-- <div class="btn-group ml-2">
                                    <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown">Showing</button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#">10</a>
                                        <a class="dropdown-item" href="#">20</a>
                                        <a class="dropdown-item" href="#">30</a>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row pr-wrapper d-flex flex-wrap">
                    <?php
                        $sql = GET_ALL_PRODUCTS . " LIMIT 9";
                        $result = $conn->query($sql);
                        $products = $result->fetchAll();
                    foreach ($products as $product):
                        $discount = getProductDiscount($product);
                        ?>
                        <div class="col-lg-4 col-md-6 col-sm-6 pb-1">
                        <div class="product-item bg-light mb-4">
                            <?=$discount?>
                            <div class="product-img position-relative overflow-hidden">
                                <img class="img-fluid w-100" src="assets/<?=$product->image_src?>_large.png" alt"<?=$product->image_alt?>">
                                <div class="product-action">
                                    <?php if($product->in_stock > 0):?>
                                        <a class="btn btn-outline-dark btn-square addToCart" data-id="<?=$product->product_id?>"><i class="fa fa-shopping-cart"></i></a>
                                    <?php endif;?>
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
                    <div class="col-12">
                        <nav>
                            <ul class="pagination justify-content-center">
                                <?php
                                $productsForPaginate = getAll("products");
                                $num = count($productsForPaginate);
                                $numOfPages = ceil($num / 9);
                                for ($i = 1; $i <= 1; $i++):

                                    ?>
                                    <li class="page-item active pageLink"><a class="page-link" data-page="<?=$i?>"><?=$i?></a></li>
                                <?php endfor; ?>
                                <?php
                                for ($i = 2; $i <= $numOfPages; $i++):
                                ?>
                                    <li class="page-item pageLink"><a class="page-link" data-page="<?=$i?>"><?=$i?></a></li>
                                <?php endfor; ?>
                              <!--  <li class="page-item disabled"><a class="page-link" href="#">Previous</span></a></li>-->
                               <!-- <li class="page-item active pageLink"><a class="page-link" data-page="1" href="">1</a></li>
                                <li class="page-item pageLink"><a class="page-link" href="">2</a></li>
                                <li class="page-item pageLink"><a class="page-link" href="">3</a></li>-->
                               <!-- <li class="page-item"><a class="page-link" href="#">Next</a></li>-->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <!-- Shop Product End -->
        </div>
    </div>
    <!-- Shop End -->

    <div class="Popup">
        <p class="popup-text"></p>
    </div>