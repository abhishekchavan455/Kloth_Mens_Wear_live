<?php
include 'config.php';

$productIds = $_POST['productIds'] ?? [];
$totalAmount = $_POST['totalAmount'] ?? 0;
$customerName = $_POST['customerName'] ?? '';
$contactNumber = $_POST['contactNumber'] ?? '';
$paymentMethod = $_POST['paymentMethod'] ?? '';
$orderDate = date("Y-m-d H:i:s");

$allSuccessful = true;
$errors = [];
$totalProfit = 0;

// Store transaction info for later display
$transactionDetails = [];

foreach ($productIds as $productId) {
    $qty = $_POST["quantity_$productId"] ?? 1;

    $stmt = $conn->prepare("SELECT productname, qty, price, purchaserate FROM add_product WHERE product_id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $productName = $row['productname'];
        $availableQty = $row['qty'];
        $price = $row['price'];
        $purchaseRate = $row['purchaserate'];

        if ($availableQty >= $qty) {
            $newQty = $availableQty - $qty;
            $subtotal = $price * $qty;
            $profit = ($price - $purchaseRate) * $qty;
            $totalProfit += $profit;

            // Update stock
            $updateStmt = $conn->prepare("UPDATE add_product SET qty = ? WHERE product_id = ?");
            $updateStmt->bind_param("ii", $newQty, $productId);
            $updateStmt->execute();

            // Insert transaction
            $insertStmt = $conn->prepare("INSERT INTO transactions (product_id, product_name, price, quantity, total_price, contact_number, payment_method, cname, order_date, profit) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $insertStmt->bind_param("isiiissssd", $productId, $productName, $price, $qty, $subtotal, $contactNumber, $paymentMethod, $customerName, $orderDate, $profit);
            $insertStmt->execute();

            // Store for invoice
            $transactionDetails[] = [
                'product_id' => $productId,
                'product_name' => $productName,
                'price' => $price,
                'quantity' => $qty,
                'subtotal' => $subtotal
            ];
        } else {
            $allSuccessful = false;
            $errors[] = "Insufficient stock for product ID $productId.";
        }
    } else {
        $allSuccessful = false;
        $errors[] = "Product ID $productId not found.";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation - Kloth Men's Wear</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f4f4;
            padding: 20px;
            font-family: 'Segoe UI', sans-serif;
        }
        .invoice-box {
            background: #fff;
            padding: 30px;
            max-width: 800px;
            margin: auto;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .logo {
            max-height: 60px;
        }
        .info {
            font-size: 14px;
            color: #555;
            text-align: right;
        }
        .invoice-table th {
            background: #007bff;
            color: #fff;
        }
        .invoice-table td, .invoice-table th {
            text-align: left;
            vertical-align: middle;
        }
        .text-right {
            text-align: right;
        }
        .caption {
            font-size: 20px;
            font-weight: 600;
            color: #28a745;
        }
        .contact-footer {
            margin-top: 30px;
            font-size: 14px;
            text-align: center;
            color: #666;
        }
        .btn-print {
            margin-top: 20px;
            display: block;
            width: 100%;
        }
        @media print {
            .btn-print { display: none; }
        }
    </style>
</head>
<body>
<div class="invoice-box">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <img src="kloth_logo.png" class="logo img-fluid mb-3 mb-md-0" alt="Kloth Logo">
        <div class="info">
            <strong>Kloth Men's Wear</strong><br>
            Bus Route, 5th Lane, Poorvarang, Mahalaxminagar,<br>
            Rajarampuri, Kolhapur, Maharashtra 416008<br>
            📧 klothmenswear@gmail.com | 📱 8830561355<br>
            📸 Instagram: @klothmenswear
        </div>
    </div>

    <h3 class="text-center caption">Visit Again</h3>

    <?php if ($allSuccessful): ?>
        <div class="alert alert-success">Thank You for Your Purchase</div>

        <p><strong>Customer Name:</strong> <?= htmlspecialchars($customerName) ?></p>
        <p><strong>Contact:</strong> <?= htmlspecialchars($contactNumber) ?></p>
        <p><strong>Payment Method:</strong> <?= htmlspecialchars($paymentMethod) ?></p>
        <p><strong>Order Date:</strong> <?= date("F d, Y \\a\\t h:i A", strtotime($orderDate)) ?></p>

        <!-- Wrap table in a responsive container -->
        <div class="table-responsive">
            <table class="table table-bordered invoice-table mt-3">
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactionDetails as $item): ?>
                        <tr>
                            <td><?= $item['product_id'] ?></td>
                            <td><?= htmlspecialchars($item['product_name']) ?></td>
                            <td>₹<?= number_format($item['price'], 2) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>₹<?= number_format($item['subtotal'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <h4 class="text-right">Total Amount: ₹<?= number_format($totalAmount, 2) ?></h4>

        <button class="btn btn-primary btn-print" onclick="window.print()">🖨️ Print Receipt</button>
    <?php else: ?>
        <div class="alert alert-danger">
            <strong>Errors occurred while processing your order:</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="contact-footer">
        For support, reach out on 📞 8830561355 or 📧 klothmenswear@gmail.com<br>
        Follow us on Instagram 👉 <strong>@klothmenswear</strong>
    </div>

    <div class="back-btn">
        <a href="admin_panal.html" class="btn btn-primary">
            ← Back to Homepage
        </a>
    </div>

</div>
</body>
</html>
