<?php
include 'config.php';

$productIds = $_POST['productIds'] ?? [];
$totalAmount = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        .card {
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .total-amount {
            font-size: 1.3rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <h2 class="mt-4 mb-4 text-center">Shopping Cart</h2>

    <form action="add_payment_method.php" method="post">
        <div class="row">
            <?php
            if (!empty($productIds)) {
                foreach ($productIds as $productId) {
                    $quantityField = "quantity_" . $productId;
                    $quantity = isset($_POST[$quantityField]) ? (int) $_POST[$quantityField] : 1;

                    $stmt = $conn->prepare("SELECT productname, price FROM add_product WHERE product_id = ?");
                    $stmt->bind_param("i", $productId);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($row = $result->fetch_assoc()) {
                        $productName = htmlspecialchars($row['productname']);
                        $price = (int) $row['price'];
                        $totalProductPrice = $price * $quantity;
                        $totalAmount += $totalProductPrice;
                        ?>
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $productName ?></h5>
                                    <p class="card-text"><strong>Price:</strong> ₹<?= $price ?></p>
                                    <p class="card-text"><strong>Quantity:</strong> <?= $quantity ?></p>
                                    <p class="card-text"><strong>Total:</strong> ₹<?= $totalProductPrice ?></p>

                                    <!-- Hidden inputs to pass data -->
                                    <input type="hidden" name="productIds[]" value="<?= $productId ?>">
                                    <input type="hidden" name="quantity_<?= $productId ?>" value="<?= $quantity ?>">
                                    <input type="hidden" name="price_<?= $productId ?>" value="<?= $price ?>">
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    $stmt->close();
                }
                ?>
                <div class="col-12 text-right mt-4">
                    <p class="total-amount">Total Cart Amount: ₹<?= $totalAmount ?></p>
                    <input type="hidden" name="totalAmount" value="<?= $totalAmount ?>">
                </div>
                <?php
            } else {
                echo '<div class="col-12"><p class="text-danger">No products added to cart. Please go back and select products.</p></div>';
            }
            ?>
        </div>

        <!-- Proceed button -->
        <div class="form-group text-center mt-4">
            <button type="submit" class="btn btn-primary btn-lg px-4">Proceed to Payment Method</button>
        </div>
    </form>
</div>
</body>
</html>

<?php $conn->close(); ?>
