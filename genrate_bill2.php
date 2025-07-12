<?php
include 'config.php';

// Retrieve selected product IDs
$selected = $_POST['selectedProducts'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Selected Products</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <style>
        .card { margin-bottom: 20px; }
        .card-img-top { max-height: 200px; object-fit: cover; }
        .quantity-controls { display: flex; align-items: center; margin-top: 10px; }
        .quantity-controls input { width: 50px; text-align: center; margin: 0 5px; }
        .cart-icon { position: fixed; top: 10px; right: 10px; font-size: 18px; background: #fff; padding: 6px 12px; border-radius: 4px; box-shadow: 0 0 5px rgba(0,0,0,0.1); }
    </style>

    <script>
        function updateQuantity(productId, inc) {
            const el = document.getElementById('quantity_' + productId);
            let v = parseInt(el.value) || 1;
            v = Math.max(1, v + inc);
            el.value = v;
            updateCartCount();
        }

        function updateCartCount() {
            let sum = 0;
            document.querySelectorAll('.quantity-input').forEach(e => sum += parseInt(e.value) || 0);
            document.getElementById('cartCount').innerText = sum;
        }

        function validateCart() {
            let totalQty = 0;
            document.querySelectorAll('.quantity-input').forEach(e => {
                totalQty += parseInt(e.value) || 0;
            });

            if (totalQty <= 0) {
                alert("Please select at least one product with quantity greater than 0.");
                history.back(); // Go back to previous page
                return false;
            }

            return true;
        }

        window.onload = updateCartCount;
    </script>
</head>
<body>
<div class="container">
    <h2 class="mt-4">Selected Products</h2>
    <div class="cart-icon">🛒 Cart: <span id="cartCount">0</span> items</div>

    <form action="add_to_cart.php" method="post" onsubmit="return validateCart();">
        <div class="row">
            <?php
            if ($selected) {
                foreach ($selected as $prodId) {
                    $stmt = $conn->prepare("SELECT * FROM add_product WHERE product_id = ?");
                    $stmt->bind_param("i", $prodId);
                    $stmt->execute();
                    $row = $stmt->get_result()->fetch_assoc();
                    if ($row) {
                        $img = base64_encode($row['photos']);
                        echo '
                        <div class="col-md-4">
                            <div class="card">
                                <img src="data:image/jpeg;base64,'. $img .'" class="card-img-top" alt="Product Image">
                                <div class="card-body">
                                    <h5 class="card-title">'. htmlspecialchars($row['productname']) .'</h5>
                                    <p class="card-text"><strong>Price:</strong> ₹'. $row['purchaserate'] .'</p>
                                    <p class="card-text"><strong>Category:</strong> '. htmlspecialchars($row['category']) .'</p>
                                    <p class="card-text"><strong>Subcategory:</strong> '. htmlspecialchars($row['subcategory']) .'</p>
                                    <p class="card-text"><strong>Type:</strong> '. htmlspecialchars($row['type']) .'</p>
                                    <p class="card-text"><strong>Sizes:</strong> '. htmlspecialchars($row['sizestart']) .' - '. htmlspecialchars($row['sizeend']) .'</p>
                                    <p class="card-text"><strong>Available:</strong> '. $row['qty'] .'</p>
                                    
                                    <div class="quantity-controls">
                                        <button type="button" class="btn btn-outline-secondary" onclick="updateQuantity('. $prodId .', -1)">-</button>
                                        <input id="quantity_'. $prodId .'" name="quantity_'. $prodId .'" class="form-control quantity-input" value="1" />
                                        <button type="button" class="btn btn-outline-secondary" onclick="updateQuantity('. $prodId .', 1)">+</button>
                                    </div>
                                    <input type="hidden" name="productIds[]" value="'. $prodId .'">
                                </div>
                            </div>
                        </div>';
                    }
                    $stmt->close();
                }
            } else {
                echo '<div class="col-12"><p class="text-danger">No products selected. Please go back and select products.</p></div>';
            }
            $conn->close();
            ?>
        </div>

        <!-- Submit Button -->
        <div class="form-group mt-4 text-end">
            <button type="submit" class="btn btn-success">Add to Cart</button>
        </div>
    </form>
</div>
</body>
</html>
