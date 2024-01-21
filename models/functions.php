<?php
const pages = ["account", "admin", "cart", "checkout", "contact", "product-detail", "shop", "wishlist","compare"];
const GET_ALL_PRODUCTS = "SELECT * FROM products p 
                          INNER JOIN prices pr ON p.product_id = pr.product_id 
                          LEFT JOIN discounts d ON p.discount_id = d.discount_id
                          WHERE pr.active = 1";

function get_user_by_id($id) {
    global $conn;
    $query = 'SELECT * FROM users WHERE id = :id';
    $statement = $conn->prepare($query);
    $statement->bindValue(':id', $id);
    $statement->execute();
    $user = $statement->fetch();
    $statement->closeCursor();
    return $user;
}
function getPaginate($pageNumber){
    $perPage = 9;
    $offset = ($pageNumber - 1) * $perPage;
    $sql = "SELECT * FROM products LIMIT $perPage OFFSET $offset";
    return ceil($productsNumber / 6);
}
function makePagination($length,$activePage,$table){
    $html = "";
    $numOfPages = ceil($length / 15);
    $html = "<nav><ul class='pagination justify-content-center flex-wrap'>";
    for ($i = 1; $i <= 1; $i++){
        if($activePage == 1){
            $html .= "<li class='page-item active pageLink'><a class='page-link' href='index.php?page=admin&table=$table&pagetable$i'>$i</a></li>";
            continue;
        }
            $html .= "<li class='page-item pageLink'><a class='page-link' href='index.php?page=admin&table=$table&pagetable=$i'>$i</a></li>";
    }
    for ($i = 2; $i <= $numOfPages; $i++)
    {
       if($i == $activePage){
        $html .= "<li class='page-item active pageLink'><a class='page-link' href='index.php?page=admin&table=$table&pagetable=$i'>$i</a></li>";
        continue;
            continue;
        }
        $html .= "<li class='page-item pageLink'><a class='page-link' href='index.php?page=admin&table=$table&pagetable=$i'>$i</a></li>";
    }
    $html .= "</ul></nav>";
    echo $html;

}
function getAllProducts(){
    global $conn;
    $query = "SELECT * FROM products p INNER JOIN prices pr ON p.product_id = pr.product_id 
         LEFT JOIN discounts d ON p.discount_id = d.discount_id";
    $statement = $conn->query($query);
    return $statement->fetchAll();
}
function getProductDiscountedPrice($product){
        $discount = $product->discount_percent;
        $price = $product->price;
        return $price - ($price * $discount / 100);
}
function getProductDiscount($product){
   return $product->discount_id == null ? "" : "<p class='discount-perc'>-" . $product->discount_percent . "%</p>";
}
function getProductActivePrice($product){
    $price =  $product->discount_id == null ? $product->price : getProductDiscountedPrice($product);
    return "<h5 class='ml-2'>$price&euro;</h5>";
}
function getProductOldPrice($product){
    $price =  $product->discount_id == null ? "" : $product->price . "&euro;";
    return "<h6 class='text-muted ml-2 oldPrice'><del>$price</del></h6>";
}
function getCategoriesFilter(){
    global $conn;
    $query = "SELECT COUNT(c.category_id) as Number, c.category_id , c.category_name FROM categories c INNER JOIN 
                        products p ON c.category_id = p.category_id GROUP BY c.category_id, c.category_name";
    return $conn->query($query);
}
function getBrandsFilter(){
    global $conn;
    $query = "SELECT COUNT(b.brand_id) as Number, b.brand_id , b.brand_name FROM brands b INNER JOIN 
                        products p ON b.brand_id = p.brand_id GROUP BY b.brand_id, b.brand_name";
    return $conn->query($query);
}
function getProductNumberofReviews($id){
    global $conn;
    $sql = "SELECT COUNT(*)  as Number FROM reviews WHERE product_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $result =  $stmt->fetch();
    return $result->Number;

}
function getRatingForProduct($id){
    global $conn;
    $sql = "SELECT AVG(review_rate) as Rating FROM reviews WHERE product_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $rating = $stmt->fetch();
    $number = round($rating->Rating);
    $output = "";
    //<small class="fa fa-star text-primary mr-1"></small>
    for ($i = 1; $i <= $number; $i++) {
        $output .= "<i class='fas fa-star text-primary'></i>";
    }
    for ($i = 1; $i <= 5 - $number; $i++) {
        $output .= "<i class='far fa-star text-primary'></i>";
    }
    return $output;
}
function userHasAddressInfos($id){
    global $conn;
    $sql = "SELECT * FROM users u
                                JOIN users_billing_adresses uba  ON uba.user_id = u.user_id
                                WHERE u.user_id = $id";
    $stmt = $conn->query($sql);
    return $stmt->fetch();
}
function getProductReviewRate($number){
    $stars = "";
    for ($i = 1; $i <= $number; $i++) {
        $stars .= "<i class='fas fa-star'></i>";
    }
    for ($i = 1; $i <= 5 - $number; $i++) {
        $stars .= "<i class='far fa-star'></i>";
    }
    return $stars;
}
function getAll($table){
    global $conn;
    $query = "SELECT * FROM $table";
    $statement = $conn->prepare($query);
    $statement->execute();
    return $statement->fetchAll();
}
function getProductReviews($id){
    global $conn;
    $sql = "SELECT r.review_text,r.review_rate,r.created_at as postedDate,u.first_name,u.last_name
    FROM reviews r INNER JOIN users u ON r.user_id = u.user_id WHERE r.product_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    try {
        $stmt->execute();
        $reviews = $stmt->fetchAll();
        return $reviews;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
function getUsersNumberOfProductsInWishlist($user_id){
    global $conn;
    $sql = "SELECT COUNT(*) as Broj FROM wishlist w JOIN wishlist_products wp ON w.wishlist_id = wp.wishlist_id 
            WHERE w.user_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    return $stmt->fetch()->Broj;
}
function getUsersNumberOfProductsInCart($user_id){
    global $conn;
    $sql = "SELECT COUNT(*) as Broj FROM cart c JOIN cart_products cp ON c.cart_id = cp.cart_id 
            WHERE c.user_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    return $stmt->fetch()->Broj;
}
function insertUserIntoCartOrWish($user_id, $table){
    global $conn;
    $sql = "INSERT INTO $table (user_id) VALUES (:id)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $user_id);
    try {
        $stmt->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
function UserExistsInCartOrWish($user_id, $table){
    global $conn;
    $sql = "SELECT * FROM $table WHERE user_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    return $stmt->rowCount();
}
function UserAlreadyHaveProductInWishlist($product_id,$user_id){
    global $conn;
    $sql = "SELECT * FROM wishlist w JOIN wishlist_products wp ON w.wishlist_id = wp.wishlist_id 
            JOIN users u ON w.user_id = u.user_id
            WHERE u.user_id = :user_id AND wp.product_id = :product_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();
    return $stmt->rowCount();
}
function UserAlreadyHaveProductInCart($product_id,$user_id){
    global $conn;
    $sql = "SELECT * FROM cart c JOIN cart_products cp ON c.cart_id = cp.cart_id 
            WHERE c.user_id = :user_id AND cp.product_id = :product_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();
    return $stmt->rowCount();
}
function countProductPriceAtCheckout($id,$quantity){
    global $conn;
    $sql = "SELECT * FROM products p LEFT JOIN discounts d ON p.discount_id = d.discount_id
            INNER JOIN prices pr ON p.product_id = pr.product_id
            WHERE p.product_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $product = $stmt->fetch();
    $activePrice =  $product->discount_id == null ? $product->price : getProductDiscountedPrice($product);
    $price = $activePrice * $quantity;
    return "$$price";
}
function getUserAvatar($id){
    global $conn;
    $sql = "SELECT avatar FROM users WHERE user_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $user = $stmt->fetch();
    return $user->avatar;
}
function getColumnNames($table){
    global $conn;
    $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = :table";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':table', $table);
    $stmt->execute();
    return $stmt->fetchAll();
}
function makeColumnFromForeignKey($column,$value,$table,$idColumn){
    if($column == "image_src"){
        $value = "<img src='assets/" . $value . "_small.png' alt='" . $value . "'>";
      }
      if($column == "discount_id" && $column != $idColumn && $value != null){
        $value = getValueForID($value,"discounts",$column,"discount_percent");
      }
      if($column == "category_id" && $column != $idColumn && $value != null){
        $value = getValueForID($value,"categories",$column,"category_name");
      }
      if($column == "brand_id" && $column != $idColumn && $value != null){
        $value = getValueForID($value,"brands",$column,"brand_name");
      }
      if($column == "product_id" && $column != $idColumn && $value != null){
        $value = getValueForID($value,"products",$column,"name");
      }
      if($column == "user_id" && $column != $idColumn && $value != null){
        $value = getValueForID($value,"users",$column,"username");
      }
      if($column == "payment_id" && $column != $idColumn && $value != null){
        $value = getValueForID($value,"payment_types","payment_type_id","type");
      }
      if($column == "role_id" && $column != $idColumn && $value != null){
        $value = getValueForID($value,"roles",$column,"name");
      }
      if($column == "parent_characteristic_id" && $column != $idColumn && $value != null){
        $value = getValueForID($value,"characteristics","characteristic_id","name");
      }
      return $value;
}
function getValueForID($value,$table,$id,$name){
    global $conn;
    $sql = "SELECT $name FROM $table WHERE $id = $value";
    return $conn->query($sql)->fetch()->$name;
}
function printTablesFromDb(){
   $tables = array(
        (object) [
          'text' => 'Dashboard',
          'value' => 'dashboard'
        ],
        (object) [
          'text' => 'Categories',
          'value' => 'categories'
         ],
         (object) [
           'text' => 'Characteristics',
           'value' => 'characteristics'
         ],
        (object) [
            'text' => 'Products',
            'value' => 'products'
        ],
        (object) [
            'text' => 'Messages',
            'value' => 'messages'
        ],
        (object) [
            'text' => 'Prices',
            'value' => 'prices'
        ],
        (object) [
            'text' => 'Discounts',
            'value' => 'discounts'
        ],
        (object) [
            'text' => 'Orders',
            'value' => 'orders'
        ],
        (object) [
            'text' => 'Roles',
            'value' => 'roles'
        ],
        (object) [
            'text' => 'Users',
            'value' => 'users'
        ],
        (object) [
            'text' => "Reviews",
            'value'=> "reviews"
        ]
      );

      foreach($tables as $table){
          echo "<li class='nav-item'>
          <a class='nav-link collapsed tableLink' href='index.php?page=admin&table=". $table->value . "' data-table="  . $table->value . ">
              <i class='bi bi-grid'></i>
              <span>" . $table->text . "</span>
          </a>
        </li>";
      }
}
function uploadImg($image,$table){
    
    $fileName = $image['name'];
    $fileTmpName = $image['tmp_name'];
    $fileSize = $image['size'];
    $fileError = $image['error'];
    $fileType = $image['type'];
    $fileExt = explode('.', $fileName);
    $newFileName = "";
    for($i = 0; $i < count($fileExt) - 1; $i++){
       $newFileName .= $fileExt[$i];
    }
    $fileActualExt = strtolower(end($fileExt));
    $allowed = array('jpg', 'jpeg', 'png');
    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            if ($fileSize < 1000000) {
                if($table == "products"){
                    // $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                    list($width, $height) = getimagesize($fileTmpName);
                    if($width < 100 && $height < 100){
                        echo "Image is too small!";
                        return;
                    }
                    $smallImgWidth = 100;
                    $smallImgHeight = 100;
                    if($fileType == "image/jpeg") 
                    { 
                        $currentImage = imagecreatefromjpeg($fileTmpName);
                    }
                    else 
                    { 
                        $currentImage = imagecreatefrompng($fileTmpName);
                    }
                    $smallEmptyImage = imagecreatetruecolor($smallImgWidth, $smallImgHeight);
                    imagecopyresampled($smallEmptyImage, $currentImage, 0, 0, 0, 0, $smallImgWidth, $smallImgHeight, $width, $height);
                    $fileDestinationSmall = '../assets/img/products/' . $newFileName . "_small." . $fileActualExt;
                    if($fileActualExt == "png"){
                        $newSmallImage = imagepng($smallEmptyImage, $fileDestinationSmall);
                    }
                    else{
                        $newSmallImage = imagejpeg($smallEmptyImage, $fileDestinationSmall, 100);
                    }
                    if($width > 500 && $height > 500){
                        $largeImgWidth = 500;
                        $largeImgHeight = 500;
                    }
                    else if($height < 500 && $width < 500){
                        $largeImgHeight = null;
                        $largeImgWidth = null;
                    }
                    else if($height < 500 && $width > 500){
                        $largeImgHeight = $height;
                        $largeImgWidth = 500;
                    }
                    else if($height > 500 && $width < 500){
                        $largeImgHeight = 500;
                        $largeImgWidth = $width;
                    }
                    $fileDestinationLarge = '../assets/img/products/' . $newFileName . "_large." . $fileActualExt;
                    if($largeImgHeight != null && $largeImgWidth != null){
                        $largeEmptyImage = imagecreatetruecolor($largeImgWidth, $largeImgHeight);
                        imagecopyresampled($largeEmptyImage, $currentImage, 0, 0, 0, 0, $largeImgWidth, $largeImgHeight, $width, $height);
                        if($fileActualExt == "png"){
                            $newLargeImage = imagepng($largeEmptyImage, $fileDestinationLarge);
                        }
                        else{
                            $newLargeImage = imagejpeg($largeEmptyImage, $fileDestinationLarge, 100);
                        }
                    }
                    // move_uploaded_file($fileTmpName, $fileDestination);
                    $value = "img/products/" . $newFileName;
                }
                else{
                    list($width, $height) = getimagesize($fileTmpName);
                    if($width < 100 && $height < 100){
                        echo "Image is too small!";
                        http_response_code(400);
                        die();
                    }
                    else if($width == 100 && $height == 100){
                        move_uploaded_file($fileTmpName,"../img/userAvatars/". $fileName);
                        $value = "img/userAvatars/" . $fileName;
                    }
                    else{
                        $imgWidth = 100;
                        $imgHeight = 100;
                        if($fileType == "image/jpeg" || $fileType == "image/jpg") 
                        { 
                            $currentImage = imagecreatefromjpeg($fileTmpName);
                        }
                        else 
                        { 
                            $currentImage = imagecreatefrompng($fileTmpName);
                        }
                        $emptyImage = imagecreatetruecolor($imgWidth, $imgHeight);
                        imagecopyresampled($emptyImage, $currentImage, 0, 0, 0, 0, $imgWidth, $imgHeight, $width, $height);
                        $fileDestination = '../assets/img/userAvatars/' . $fileName;
                        if($fileExt == "png"){
                            $newImage = imagepng($emptyImage, $fileDestination);
                        }
                        else{
                            $newImage = imagejpeg($emptyImage, $fileDestination, 100);
                        }
                        $value = "img/userAvatars/" . $fileName;
                    }
                    
                }
             
            } else {
                echo "Your file is too big!";
            }
        } else {
            echo "There was an error uploading your file!";
        }
    } else {
        echo "You cannot upload files of this type!";
    }
    return $value;
}
function checkIfImageExists($table){
    $image = "";
    if($table == "products"){
        if(isset($_FILES['image_src'])){
            $image = uploadImg($_FILES['image_src'],$table);
        }
        else{
            $image = null;
        }
    }
    else if($table == "users"){
        if(isset($_FILES['avatar'])){
            $image = uploadImg($_FILES['avatar'],$table);
        }
        else{
            $image = "img/userAvatars/default.jpg";
        }
    }
    return $image;
}
function checkUserPassword($id){
    global $conn;
    $sql = "SELECT password FROM users WHERE user_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $user = $stmt->fetch();
    return $user->password;
}
function makeTitle($title){
   switch($title){
    case "index.php":
        return "IT Shop - Home";
        break;
    case "index.php?page=shop":
        return "IT Shop - Shop";
        break;
    case "index.php?page=contact":
        return "IT Shop - Contact";
        break;
    case "index.php?page=cart":
        return "IT Shop - Cart";
        break;
    case "index.php?page=wishlist":
        return "IT Shop - Wishlist";
        break;
    case "index.php?page=checkout":
        return "IT Shop - Checkout";
        break;
    case "index.php?page=account":
        return "IT Shop - Account";
        break;
    case str_contains($title,"index.php?page=product-detail"):
        global $conn;
        $id = $_GET['id'];
        $sql = "SELECT name FROM products WHERE product_id = $id";
        $productName = $conn->query($sql)->fetch()->name;
        return "IT Shop | $productName";
        break;
   }
}
function redirect($page){
    header("Location: $page");
    exit();
}
