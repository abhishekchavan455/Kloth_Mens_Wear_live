<?php
// Enable error reporting for debugging (turn off in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'config.php';

// Get form inputs safely
$product_id = isset($_GET['product_id']) ? (int) $_GET['product_id'] : 0;
$price = isset($_GET['price']) ? (int) $_GET['price'] : 0;

// Update query using prepared statement
$sql = "UPDATE add_product SET price = ? WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $price, $product_id);

// Execute and display result
if ($stmt->execute()) {
    echo "
    <script>alert('Product price updated successfully!');</script>
    <div style='
        font-family: Arial, sans-serif;
        text-align: center;
        margin-top: 50px;
    '>
        <h2 style='color: #27ae60;'>Product Price Updated Successfully!</h2>
        <a href='admin_panal.html' style='
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #2c3e50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        '>Go Back to Home</a>
    </div>
    ";
} else {
    echo "
    <script>alert('Error updating product: " . addslashes($stmt->error) . "');</script>
    <h2 style='text-align:center; color:red;'>Error updating price: " . htmlspecialchars($stmt->error) . "</h2>
    ";
}

// Close resources
$stmt->close();
$conn->close();
?>
