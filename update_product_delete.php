<?php
// Enable error reporting (turn off in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'config.php';

// Get product ID from request
if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']); // Sanitize input

    // Prepare delete query
    $sql = "DELETE FROM add_product WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        echo "
        <script>alert('Are You Sure delete Product ?');</script>
        <div style='
        font-family: Arial, sans-serif;
        max-width: 90%;
        width: 400px;
        margin: 50px auto;
        text-align: center;
        '>
            <h2 style='color: #e74c3c;'>One Product Deleted Successfully!</h2>
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
        <script>alert('Error deleting product: " . addslashes($stmt->error) . "');</script>
        <h3 style='color:red; text-align:center;'>Error deleting product: " . htmlspecialchars($stmt->error) . "</h3>
        ";
    }

    $stmt->close();
} else {
    echo "<script>alert('Error: Product ID not provided!');</script>
    <h3 style='color:red; text-align:center;'>Error: Product ID not provided.</h3>";
}

$conn->close();
?>
