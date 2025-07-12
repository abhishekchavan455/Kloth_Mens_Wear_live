<?php
// Enable error reporting for debugging (optional, turn off in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'config.php';

// Get form inputs
$product_id = $_GET['product_id'];
$price = $_GET['price'];

// Update query
$sql = "UPDATE add_product SET price = ? WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $price, $product_id);

// Execute and display result
if ($stmt->execute()) {
    echo "<h2 style='text-align:center; color:green;'>Product price updated successfully!</h2>";
} else {
    echo "<h2 style='text-align:center; color:red;'>Error updating price: " . $stmt->error . "</h2>";
}

// Close connection
$stmt->close();
$conn->close();
?>
