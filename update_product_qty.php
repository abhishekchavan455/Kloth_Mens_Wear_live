<?php
// Get values from the form (GET or POST method)
$product_id = isset($_GET['product_id']) ? (int) $_GET['product_id'] : 0;
$qty = isset($_GET['qty']) ? (int) $_GET['qty'] : 0;

// Database connection
include 'config.php';

// Use prepared statement for safety
$stmt = $conn->prepare("UPDATE add_product SET qty = ? WHERE product_id = ?");
$stmt->bind_param("ii", $qty, $product_id);

// Execute and check result
if ($stmt->execute()) {
    echo "
    <script>alert('Product Quntity updated successfully!');</script>
    <div style='
        font-family: Arial, sans-serif;
        text-align: center;
        margin-top: 50px;
    '>
        <h2 style='color: #27ae60;'>Quantity Updated Successfully!</h2>
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
    echo "<script>alert('Error updating product: " . addslashes($stmt->error) . "');</script>";
}

$stmt->close();
$conn->close();
?>
