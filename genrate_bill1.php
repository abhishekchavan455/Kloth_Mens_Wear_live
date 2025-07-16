<?php
include 'config.php';

$sql = "SELECT product_id, productname, price, photos FROM add_product";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card {
            height: 400px;
        }
        .card-img {
            height: 200px;
            width: 350px;
        }
            body {
            background: linear-gradient(to right, #ecf0f1, #e0eafc);
            color: #1f1f1f;
        }
    </style>
    <script>
        function searchProducts() {
            let input = document.getElementById("searchInput").value.toLowerCase();
            let products = document.getElementsByClassName("product-card");

            for (let i = 0; i < products.length; i++) {
                let productName = products[i].getElementsByClassName("card-title")[0].innerText.toLowerCase();
                let productPrice = products[i].getElementsByClassName("card-text")[1].innerText.toLowerCase();

                if (productName.includes(input) || productPrice.includes(input)) {
                    products[i].style.display = "";
                } else {
                    products[i].style.display = "none";
                }
            }
        }
    </script>
</head>
<body>
<div class="container">
    <h2 class="mt-4">Product List</h2>

    <!-- Search bar -->
    <div class="form-group mt-4">
        <input type="text" class="form-control" id="searchInput" onkeyup="searchProducts()" placeholder="Search by product name or price...">
    </div>

    <!-- Form to submit selected products -->
    <form action="genrate_bill2.php" method="post">
    <div class="d-flex justify-content-start mt-3">
        <button type="submit" class="btn btn-secondary">View Selected Products</button>
    </div>
        <?php
        if ($result->num_rows > 0) {
            $count = 0;
            while($row = $result->fetch_assoc()) {
                if ($count % 3 == 0) {
                    if ($count > 0) echo '</div>';
                    echo '<div class="row">';
                }
                echo '<div class="col-md-4 mt-5 product-card">';
                echo '<div class="card">';
                echo '<div class="card-body">';

                $imageData = base64_encode($row['photos']);
                echo '<p class="card-text">';
                echo '<img src="data:image/jpeg;base64,' . $imageData . '" alt="Product Image" class="img-fluid card-img" />';
                echo '</p>';

                echo '<h5 class="card-title">' . htmlspecialchars($row['productname']) . '</h5>';
                echo '<p class="card-text"><strong>Selling Price:</strong> ' . $row['price'] . '</p>';

                echo '<div class="form-check">';
                echo '<input class="form-check-input" type="checkbox" name="selectedProducts[]" value="' . $row['product_id'] . '">';
                echo '<label class="form-check-label">Select Product</label>';
                echo '</div>';

                echo '</div></div></div>';

                $count++;
            }
            echo '</div>'; // close last row
        } else {
            echo "<p>No products found.</p>";
        }
        $conn->close();
        ?>

    </form>
</div>
</body>
</html>
