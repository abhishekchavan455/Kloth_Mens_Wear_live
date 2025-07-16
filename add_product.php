<?php
include 'config.php';

// Get form data
$productname = $_POST['productname'];
$price = intval($_POST['price']);
$qty = intval($_POST['qty']);
$type = $_POST['type'];
$category = $_POST['category'];
$subcategory = $_POST['subcategory'];
$companyname = $_POST['companyname'];
$sizestart = $_POST['sizestart'];
$sizeend = $_POST['sizeend'];
$purchaserate = intval($_POST['purchaserate']);
$printedmrp = intval($_POST['printedmrp']);
$sname = $_POST['sname'];

// File upload handling
if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] == 0) {
    $imgData = file_get_contents($_FILES['productImage']['tmp_name']);
} else {
    die("<script>alert('Error uploading file'); window.history.back();</script>");
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO add_product 
    (productname, price, qty, category, type, subcategory, companyname, sizestart, sizeend, purchaserate, printedmrp, sname, photos) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("siissssssiiib", 
    $productname, 
    $price, 
    $qty, 
    $category, 
    $type, 
    $subcategory, 
    $companyname, 
    $sizestart, 
    $sizeend, 
    $purchaserate, 
    $printedmrp, 
    $sname, 
    $null);

$null = NULL;
$stmt->send_long_data(12, $imgData);

// Output based on result
if ($stmt->execute()) {
    echo "
    <script>alert('are you sure add this product?');</script>
    <div style='
        font-family: Arial, sans-serif;
        text-align: center;
        margin-top: 50px;
    '>
        <h2 style='color: #27ae60;'>Product Added Successfully!</h2>
        <a href='admin_login.php' style='
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
    echo "<script>alert('Error: " . addslashes($stmt->error) . "');</script>";
}

$stmt->close();
$conn->close();
?>
