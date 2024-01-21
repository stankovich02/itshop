<?php
/*require_once '../../config/setup.php';*/
/*require_once '../../models/functions.php';*/
if (isset($_SESSION['user']) && $_SESSION['user']->role_id == 1){
/*require_once '../../config/connection.php';*/

global $conn;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Dashboard - NiceAdmin Bootstrap Template</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="includes/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="includes/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/styleAdmin.css" rel="stylesheet">
  <link href="assets/css/ms-style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin - v2.5.0
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.php" class="logo d-flex align-items-center">
        <span class="d-none d-lg-block text-primary">Go to homepage</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->
  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
        <?=printTablesFromDb()?>
      <!--  -->
    </ul>

  </aside><!-- End Sidebar-->
  <main id="main" class="main">
    <?php
    if(isset($_GET['table'])) {
        $table = $_GET['table'];
    } else {
        $table = "dashboard";
    }
        if ($table == "dashboard"):
    ?>
    <section class="section dashboard">
      <div class="row">
              <div class="col-xxl-12 col-xl-4">
                  <div class="card info-card customers-card">
                      <div class="card-body">
                          <h5 class="card-title">Site visits</h5>

                          <div class="d-flex align-items-center">
                              <div class="ps-3">
                                  <h6>
                                      <?php
                                     $file = file("data/log.txt");
                                     $indexPage = 0;
                                     $contactPage = 0;
                                     $shopPage = 0;
                                     $productPage = 0;
                                     $wishlistPage = 0;
                                     $cartPage = 0;
                                     $accountPage = 0;
                                     $numOfRows = count($file);
                                     $msInDay = 86400;
                                     $dateForCheck = date("d.m.Y H:i:s",time()-$msInDay);
                                     foreach ($file as $line)
                                      {
                                          list($page,$adress,$dateTime) = explode("\t",trim($line));
                                          if($dateTime > $dateForCheck){
                                              switch ($page){
                                              case $page == "/index.php" || $page == "https://itshoppssite.000webhostapp.com/":
                                                  $indexPage++;
                                                  break;
                                              case str_contains($page,"contact"):
                                                  $contactPage++;
                                                  break;
                                              case str_contains($page,"shop"):
                                                  $shopPage++;
                                                  break;
                                              case str_contains($page,"product-detail"):
                                                  $productPage++;
                                                  break;
                                              case str_contains($page,"wishlist"):
                                                  $wishlistPage++;
                                                  break;
                                              case str_contains($page,"cart"):
                                                  $cartPage++;
                                                  break;
                                              case str_contains($page,"account"):
                                                  $accountPage++;
                                                  break;
                                          }
                                          }

                                      }
                                      ?>
                                      <table class="table table-striped font-weight-normal table-bordered">
                                          <thead>
                                            <tr class="fs-4">
                                                <th>Page</th>
                                                <th>Visits (last 24h)</th>
                                                <th>Visits (in %)</th>
                                          </thead>
                                          <tbody class="fs-5">
                                            <tr>
                                                <td>Index</td>
                                                <td><?=$indexPage?></td>
                                                <td><?=round($indexPage / $numOfRows * 100,2)?>%</td>
                                            </tr>
                                            <tr>
                                                <td>Contact</td>
                                                <td><?=$contactPage?></td>
                                                <td><?=round($contactPage / $numOfRows * 100,2)?>%</td>
                                            </tr>
                                            <tr>
                                                <td>Shop</td>
                                                <td><?=$shopPage?></td>
                                                <td><?=round($shopPage / $numOfRows * 100,2)?>%</td>
                                            </tr>
                                            <tr>
                                                <td>Single product</td>
                                                <td><?=$productPage?></td>
                                                <td><?=round($productPage / $numOfRows * 100,2)?>%</td>
                                            </tr>
                                            <tr>
                                                <td>Wishlist</td>
                                                <td><?=$wishlistPage?></td>
                                                <td><?=round($wishlistPage / $numOfRows * 100,2)?>%</td>
                                            </tr>
                                            <tr>
                                                <td>Cart</td>
                                                <td><?=$cartPage?></td>
                                                <td><?=round($cartPage / $numOfRows * 100,2)?>%</td>
                                            </tr>
                                            <tr>
                                                <td>Account</td>
                                                <td><?=$accountPage?></td>
                                                <td><?=round($accountPage / $numOfRows * 100,2)?>%</td>
                                            </tr>
                                          </tbody>
                                      </table>
                                      <?php
                                      $loginsFile = file("data/logins.txt");
                                      $logins = 0;
                                        $curentDate = date("d.m.Y");
                                        foreach ($loginsFile as $index => $line)
                                        {
                                            list($username,$date) = explode(" : ",trim($line));
                                            if($date == $curentDate){
                                                $logins++;
                                            }
                                        }
                                      ?>
                                      <br/>
                                      <br/>
                                      <p>Number of today's logins : <?=$logins?></p>
                                  </h6>
                              </div>
                          </div>

                      </div>
                  </div>

              </div><!-- End Customers Card -->
            <!-- Sales Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title">Sales</h5>

                  <div class="d-flex align-items-center">
                    <div>
                      <h6>
                      <?php
                      $query = "SELECT COUNT(*) as total FROM orders";
                      $num = $conn->query($query)->fetch()->total;
                      echo $num;
                      ?>

                      </h6>
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Sales Card -->

            <!-- Revenue Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card revenue-card">
                <div class="card-body">
                  <h5 class="card-title">Revenue</h5>

                  <div class="d-flex align-items-center">
                    <div>
                      <h6>
                      <?php
                      $sql = "SELECT SUM(total_price) as total FROM orders";
                      $num = $conn->query($sql)->fetch()->total;
                      echo "$$num";
                      ?>

                      </h6>
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Revenue Card -->

            <!-- Customers Card -->
            <div class="col-xxl-4 col-xl-12">
              <div class="card info-card customers-card">
                <div class="card-body">
                  <h5 class="card-title">Customers</h5>

                  <div class="d-flex align-items-center">
                    <div>
                      <h6>
                      <?php
                      $sql = "SELECT COUNT(*) as total FROM users";
                      $num = $conn->query($sql)->fetch()->total;
                      echo $num;
                      ?>
                      </h6>
                    </div>
                  </div>

                </div>
              </div>

            </div><!-- End Customers Card -->



            <!-- Recent Sales -->
            <div class="col-12">
              <div class="card recent-sales overflow-auto">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Recent Sales</h5>

                  <table class="table table-borderless datatable">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Product</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Price</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sql = "SELECT * FROM order_items oi 
                    INNER JOIN products p ON oi.product_id = p.product_id 
                    INNER JOIN orders o ON oi.order_id = o.order_id 
                    INNER JOIN users u ON o.user_id = u.user_id
                    ORDER BY oi.created_at DESC";
                    $orders = $conn->query($sql)->fetchAll();
                    foreach($orders as $order):
                    ?>
                      <tr>
                        <th scope="row"><a href="#">#<?=$order->order_id?></a></th>
                        <td><?=$order->first_name . " " . $order->last_name?></td>
                        <td><a href="index.php?page=product-detail&id=<?=$order->product_id?>" class="text-primary"><?=$order->name?></a></td>
                        <td>x <?=$order->quantity?></td>
                        <td><?=$order->product_price?>&euro;</td>
                      </tr>
                    <?php endforeach; ?>
                    </tbody>
                  </table>

                </div>

              </div>
            </div><!-- End Recent Sales -->

            <!-- Top Selling -->
            <div class="col-12">
              <div class="card top-selling overflow-auto">

                <div class="card-body pb-0">
                  <h5 class="card-title">Top Selling</h5>

                  <table class="table table-borderless">
                    <thead>
                      <tr>
                        <th scope="col">Image</th>
                        <th scope="col">Product</th>
                        <th scope="col">Sold</th>
                        <th scope="col">Revenue</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sql = "SELECT p.image_alt,p.image_src,p.name,p.product_id, SUM(oi.quantity) as Sum, SUM(oi.quantity * oi.product_price) as Revenue FROM order_items oi INNER JOIN products p ON oi.product_id = p.product_id GROUP BY oi.product_id ORDER BY SUM(oi.quantity) DESC LIMIT 5";
                    $products = $conn->query($sql)->fetchAll();
                    foreach($products as $product):
                    ?>
                      <tr>
                        <th scope="row"><a href=""><img src="assets/<?=$product->image_src?>_small.png" alt="<?=$product->image_alt?>"></a></th>
                        <td><a href="index.php?page=product-detail&id=<?=$product->product_id?>" class="text-primary fw-bold"><?=$product->name?></a></td>
                        <td class="fw-bold"><?=$product->Sum?></td>
                        <td><?=$product->Revenue?>&euro;</td>
                      </tr>
                    <?php endforeach; ?>
                    </tbody>
                  </table>

                </div>

              </div>
            </div><!-- End Top Selling -->

          </div>
    </section>
            <?php
            else:
            $table = $_GET['table'];
            ?>
            <div>
            <h3 class="text-center"><?=strtoupper($table)?></h3>
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <?php
                        $sql = "SHOW COLUMNS FROM $table";
                        $result = $conn->query($sql);
                        $fields = $result->fetchAll();

                        $columns = array();
                        $counter = 0;
                        foreach ($fields as $i) {
                            $columns[] = $i->Field;
                        }
                        foreach ($columns as $column) {
                            if(str_contains($column,"_id") && $counter > 0){
                                $column = str_replace("_id","",$column);
                            }
                            echo "<th class='columnName' scope='col'>$column</th>";
                            $counter++;
                        }
                    ?>
                </tr>
                </thead>
                <tbody>
                <?php
                    $sql = "SELECT * FROM $table";
                    $result = $conn->query($sql)->fetchAll();
                    $rows = count($result);
                    if(isset($_GET['pagetable'])){
                        $page = $_GET['pagetable'];
                        $offset = ($page - 1) * 10;
                    }else{
                        $page = 1;
                        $offset = 0;
                    }
                    if($rows > 15){
                        $sql = "SELECT * FROM $table LIMIT $offset,15";
                    }
                    $result = $conn->query($sql)->fetchAll();
                    $data = array();
                    foreach ($result as $row) {
                        $row_data = array();
                        foreach (get_object_vars($row) as $column => $value) {
                            $row_data[$column] = $value;
                        }
                        $data[] = $row_data;
                    }
                ?>
                <?php foreach ($result as $row):
                    $userBanned = false;?>
                
                    <tr>
                        <?php foreach ($columns as $column):
                           
                            $idColumn = $columns[0];
                            if(strlen($row->$column) > 50){
                                $row->$column = substr($row->$column, 0, 50) . "...";
                            }
                            $row->$column = makeColumnFromForeignKey($column,$row->$column,$table,$idColumn);
                            if($row->$column == 1 && $column == "locked"){
                              $userBanned = true;
                            }
                            ?>
                            <td class="colValue"><?= $row->$column ?></td>
                           
                            
                        <?php endforeach; ?>
                        <td>
                            <a class="btn btn-primary btnEdit" data-id="<?= $row->$idColumn ?>" data-table="<?=$table?>" href="">Edit</a>
                            <a class="btn btn-primary btnDelete" data-id="<?= $row->$idColumn ?>" data-table="<?=$table?>" href="">Delete</a>
                            <?php
                            if($userBanned){
                                echo "<a class='btn btn-danger btnUnban' data-id='$row->user_id' data-name='$row->username' href=''>Unban</a>";
                            }
                            ?>
                        </td>

                    </tr>
                <?php endforeach; ?>
                </tbody>

            </table>
            <a class="btn btn-primary btnCreate" data-table="<?=$table?>" href="">Add new <?=$table?></a>
            <p class="adminMsg"></p>
            <?php
            if($rows > 15){
             makePagination($rows,$page,$table);
            }
            ?>
            </div>
           
        <?php
        endif;
        ?>

  </main><!-- End #main -->
  <!-- Button trigger modal -->
  <div class="modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content uv-modal">
              <div class="modal-body mBody">
              </div>
          </div>
      </div>
  </div>
  <div id="overlay-modal"></div>
  <!-- ======= Footer ======= -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center">UP</a>

  <!-- Vendor JS Files -->
  <script src="includes/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="includes/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="includes/vendor/chart.js/chart.umd.js"></script>
  <script src="includes/vendor/echarts/echarts.min.js"></script>
  <script src="includes/vendor/quill/quill.min.js"></script>
  <script src="includes/vendor/tinymce/tinymce.min.js"></script>
  <script src="includes/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/mainAdmin.js"></script>
  <script src="assets/js/custom.js"></script>

</body>

</html>
<?php
}
else{
    redirect("Location: index.php");
}
?>