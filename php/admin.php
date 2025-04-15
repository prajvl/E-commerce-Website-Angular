<?php
$conn = new mysqli("localhost", "root", "", "shopzone_db");

// Check DB connection
if ($conn->connect_error) {
  echo json_encode(['success' => false, 'error' => 'DB connection failed: ' . $conn->connect_error]);
  exit;
}

// Handle deletion via AJAX (with 'action' and 'id')
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
  $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

  if ($id === 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid product ID']);
    exit;
  }

  $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
  $stmt->bind_param("i", $id);

  if ($stmt->execute()) {
    echo json_encode(['success' => true]);
  } else {
    echo json_encode(['success' => false, 'error' => 'Delete failed']);
  }

  $stmt->close();
  $conn->close();
  exit;
}

// Handle form submission (adding a product)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['action'])) {
  $name = $_POST['name'] ?? '';
  $description = $_POST['description'] ?? '';
  $price = $_POST['price'] ?? '';
  $image = $_POST['image'] ?? '';

  if ($name && $description && $price && $image) {
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $name, $description, $price, $image);

    if ($stmt->execute()) {
      header('Location: ../admin.html'); // Redirect back to admin panel
      exit;
    } else {
      echo "Error inserting product: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
  } else {
    echo "Missing fields in product form";
  }
}
?>
