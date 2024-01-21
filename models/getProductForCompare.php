<?php
include "../config/connection.php";
if(isset($_GET['getCharacteristics'])){
    $id = $_GET['id'];
    $category = $_GET['category_name'];
    $output = "<table class='mx-auto mt-5'>
    <thead>
    </thead>
    <tbody>";
    $sql = "SELECT ch.name as Name,ch.characteristic_id as Id FROM categories c INNER JOIN categories_characteristics cc ON c.category_id = cc.category_id INNER JOIN characteristics ch ON cc.characteristic_id = ch.characteristic_id
    WHERE c.category_name = :category_name ORDER BY display_order ASC";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':category_name', $category);
      $stmt->execute();
      $result = $stmt->fetchAll();
      foreach ($result as $row) {
          $output .= "<tr class='spec-row'>";
          $output .= "<td class='spec-name'>$row->Name</td>";
          $sql2 = "SELECT ch.name as Name,ch.value as Value FROM characteristics ch INNER JOIN products_characteristics pc WHERE ch.parent_characteristic_id = $row->Id AND pc.product_id = $id AND pc.characteristic_id = ch.characteristic_id";
          $result2 = $conn->query($sql2)->fetchAll();
          $output .= "<td class='spec-value'>";
          foreach ($result2 as $row2) {
              if($row2->Name != ''){
                $output .= "<span class='val-name'>$row2->Name</span>". ": " . $row2->Value;
                $output .= "<br/>";
              }
              else{
                $output .= $row2->Value;
                $output .= "<br/>";
              }
          }
          $output .= "</td>";
          $output .= "</tr>";
      }
        $output .= "</tbody></table>";
        echo $output;
}
if(isset($_GET['compare'])){
    $id = $_GET['id'];
    $query = "SELECT product_id,image_src,name FROM products WHERE product_id = $id";
    $result = $conn->query($query)->fetch();
    echo json_encode($result);
}