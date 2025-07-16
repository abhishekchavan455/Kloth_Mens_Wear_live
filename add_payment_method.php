<?php
$productIds = $_POST['productIds'] ?? [];
$totalAmount = $_POST['totalAmount'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Method</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .card {
            max-width: 900px;
            margin: auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }

        h3, h4 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .table th, .table td {
            vertical-align: middle;
            text-align: center;
        }

        .btn-success {
            padding: 10px 30px;
            font-size: 16px;
            border-radius: 30px;
        }

        @media (max-width: 576px) {
            .btn-success {
                width: 100%;
            }

            .table th, .table td {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="card bg-white">
    <h3>Review Your Order</h3>

    <div class="table-responsive mt-3">
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>Product ID</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($productIds as $productId) {
                    $price = $_POST["price_$productId"] ?? 0;
                    $qty = $_POST["quantity_$productId"] ?? 1;
                    $subtotal = $price * $qty;

                    echo "<tr>
                            <td>$productId</td>
                            <td>₹$price</td>
                            <td>$qty</td>
                            <td>₹$subtotal</td>
                          </tr>";

                    // Forward values for form submission
                    echo "<input type='hidden' name='productIds[]' value='$productId'>";
                    echo "<input type='hidden' name='price_$productId' value='$price'>";
                    echo "<input type='hidden' name='quantity_$productId' value='$qty'>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <h4 class="mt-3">Total Amount: ₹<?= $totalAmount ?></h4>

    <form action="proccess_order.php" method="post" class="mt-4">
        <?php
        foreach ($productIds as $productId) {
            $price = $_POST["price_$productId"] ?? 0;
            $qty = $_POST["quantity_$productId"] ?? 1;
            echo "<input type='hidden' name='productIds[]' value='$productId'>";
            echo "<input type='hidden' name='price_$productId' value='$price'>";
            echo "<input type='hidden' name='quantity_$productId' value='$qty'>";
        }
        ?>
        <input type="hidden" name="totalAmount" value="<?= $totalAmount ?>">

        <div class="mb-3">
            <label for="customerName" class="form-label">Customer Name:</label>
            <input type="text" class="form-control" id="customerName" name="customerName" required>
        </div>

        <div class="mb-3">
            <label for="contactNumber" class="form-label">Contact Number:</label>
            <input type="text" class="form-control" id="contactNumber" name="contactNumber" required>
        </div>

        <div class="mb-3">
            <label for="paymentMethod" class="form-label">Payment Method:</label>
            <select class="form-select" id="paymentMethod" name="paymentMethod" required>
                <option value="" disabled selected>Select Payment Method</option>
                <option value="UPI">GPay / UPI</option>
                <option value="Cash">Cash</option>
            </select>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-secondary">Confirm and Pay</button>
        </div>
    </form>
</div>

</body>
</html>
