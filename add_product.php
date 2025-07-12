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
    die("Error uploading file");
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

if ($stmt->execute()) {
    echo "Photo Added Successfully. <p><a href='view_products.php'>List Photos</a>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
