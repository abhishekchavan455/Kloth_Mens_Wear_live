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

    <!-- ✅ Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 40px;
            font-family: Arial, sans-serif;
        }

        h3, h4 {
            text-align: center;
        }

        table {
            background-color: #fff;
        }

        .form-section {
            background-color: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

<div class="container">
    <h3>Review Your Order</h3>
    
    <div class="table-responsive mt-4">
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

    <form action="proccess_order.php" method="post" class="form-section mt-4">
        <!-- Forward data as hidden inputs -->
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
                <option value="UPI">GPay</option>
                <option value="Cash">Cash</option>
            </select>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-success px-4">Confirm and Pay</button>
        </div>
    </form>
</div>

</body>
</html>
