<?php
// Get values from the form (GET or POST method)
$product_id = isset($_GET['product_id']) ? (int) $_GET['product_id'] : 0;
$qty = isset($_GET['qty']) ? (int) $_GET['qty'] : 0;

// Database connection
include 'config.php';

// Update query
$sql = "UPDATE add_product SET price = $qty WHERE product_id = $product_id";

if ($conn->query($sql) === TRUE) {
    echo "Product price updated successfully.";
} else {
    echo "Error updating product: " . $conn->error;
}

$conn->close();
?>
