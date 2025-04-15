<?php
include 'db.php';

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $sql = "SELECT * FROM products WHERE id=$id";
  $result = $conn->query($sql);
  echo json_encode($result->fetch_assoc());
} else {
  $sql = "SELECT * FROM products";
  $result = $conn->query($sql);
  $products = [];
  while ($row = $result->fetch_assoc()) {
    $products[] = $row;
  }
  echo json_encode($products);
}
?>
