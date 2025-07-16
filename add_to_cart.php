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
        body {
            background: linear-gradient(to right, #ecf0f1, #e0eafc);
            color: #1f1f1f;
            font-family: Arial, sans-serif;
            padding: 30px;
        }

        .card-container {
            max-width: 960px;
            margin: auto;
        }

        .cart-card {
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
        }

        .product-card {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            margin-bottom: 20px;
        }

        .product-card .card-body {
            padding: 15px 20px;
        }

        .total-amount {
            font-size: 1.4rem;
            font-weight: 600;
        }

        @media (max-width: 576px) {
            .btn-lg {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="card-container">
    <div class="card cart-card">
        <h2 class="text-center mb-4">Shopping Cart</h2>

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

                            <div class="col-md-6 col-lg-4">
                                <div class="card product-card">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= $productName ?></h5>
                                        <p class="card-text"><strong>Price:</strong> ₹<?= $price ?></p>
                                        <p class="card-text"><strong>Quantity:</strong> <?= $quantity ?></p>
                                        <p class="card-text"><strong>Total:</strong> ₹<?= $totalProductPrice ?></p>

                                        <!-- Hidden inputs -->
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

                    <div class="col-12 mt-4 text-right">
                        <p class="total-amount">Total Cart Amount: ₹<?= $totalAmount ?></p>
                        <input type="hidden" name="totalAmount" value="<?= $totalAmount ?>">
                    </div>

                    <div class="col-12 text-center mt-3">
                        <button type="submit" class="btn btn-secondary btn-lg px-4">Proceed to Payment Method</button>
                    </div>

                    <?php
                } else {
                    echo '<div class="col-12"><p class="text-danger text-center">No products added to cart. Please go back and select products.</p></div>';
                }
                ?>
            </div>
        </form>
    </div>
</div>

</body>
</html>

<?php $conn->close(); ?>
