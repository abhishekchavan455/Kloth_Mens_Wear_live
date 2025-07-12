<?php
// Enable error reporting (for development)
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'config.php';

// Get product ID from request (use $_GET or $_POST depending on how you send it)
if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']); // Sanitize input

    // Prepare and execute delete query
    $sql = "DELETE FROM add_product WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        echo "<h3>One product deleted successfully.</h3>";
    } else {
        echo "<h3>Error deleting product: " . $stmt->error . "</h3>";
    }

    $stmt->close();
} else {
    echo "<h3>Error: Product ID not provided.</h3>";
}

$conn->close();
?>
