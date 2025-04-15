<?php
include 'db.php'; // your db connection (make sure $conn is defined)

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

if ($method === 'GET' && $action === 'list') {
    // List cart items
    $cart = $conn->query("SELECT cart.id, products.name, products.price, cart.quantity 
                          FROM cart 
                          JOIN products ON cart.product_id = products.id");

    $items = [];
    while ($row = $cart->fetch_assoc()) {
        $row['price'] = (float)$row['price'];
        $row['quantity'] = (int)$row['quantity'];
        $row['id'] = (int)$row['id'];
        $items[] = $row;
    }

    echo json_encode($items);
} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if ($data['action'] === 'add') {
        $id = (int)$data['id'];
        $conn->query("INSERT INTO cart (product_id, quantity) VALUES ($id, 1)");
        echo json_encode(["status" => "success"]);
    } elseif ($data['action'] === 'remove') {
        $id = (int)$data['id'];
        $conn->query("DELETE FROM cart WHERE id = $id");
        echo json_encode(["status" => "deleted"]);
    } else {
        echo json_encode(["status" => "invalid action"]);
    }
} else {
    echo json_encode(["status" => "invalid method"]);
}
?>
