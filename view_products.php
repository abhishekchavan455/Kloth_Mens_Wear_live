<?php
// Database connection
include 'config.php';

// Fetch products
$sql = "SELECT * FROM add_product";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View All Products</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 25px;
        }

        #searchInput {
            width: 100%;
            max-width: 1000px;
            padding: 10px;
            margin: 0 auto 20px auto;
            display: block;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        #searchInput::placeholder {
            color: #999;
        }

        table img {
            max-width: 80px;
            height: auto;
            border-radius: 6px;
        }

        .product-img {
            max-width: 100px;
            height: auto;
        }

        table th {
            background-color: #f1f1f1;
        }

        table tr:hover {
            background-color: #f5f5f5;
        }

        @media (max-width: 576px) {
            th, td {
                font-size: 14px;
                padding: 8px;
            }

            h1 {
                font-size: 22px;
            }
        }
        .back-btn {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>

    <script>
        function searchTable() {
            var input = document.getElementById("searchInput");
            var filter = input.value.toUpperCase();
            var table = document.getElementById("productTable");
            var tr = table.getElementsByTagName("tr");

            for (var i = 1; i < tr.length; i++) {
                tr[i].style.display = "none";
                var td = tr[i].getElementsByTagName("td");
                for (var j = 0; j < td.length; j++) {
                    if (td[j]) {
                        var txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                            break;
                        }
                    }
                }
            }
        }
    </script>
</head>
<body>

    <h1>View All Products</h1>

    <!-- 🔍 Search Input -->
    <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search for any value...">

    <!-- 📊 Table Container -->
    <div class="table-responsive mt-3">
        <?php
        if ($result->num_rows > 0) {
            echo "<table id='productTable' class='table table-bordered table-hover'>";
            
            // Table Headers
            echo "<thead class='table-light'><tr>";
            while ($fieldinfo = $result->fetch_field()) {
                echo "<th>" . htmlspecialchars($fieldinfo->name) . "</th>";
            }
            echo "</tr></thead><tbody>";

            // Table Data
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                $colIndex = 1;
                foreach ($row as $key => $value) {
                    if ($colIndex == 14 && $value != null) {
                        // Assuming 14th column is image
                        $base64 = base64_encode($value);
                        echo "<td><img src='data:image/jpeg;base64,{$base64}' alt='Product Image' class='img-thumbnail product-img'/></td>";
                    } else {
                        echo "<td>" . htmlspecialchars($value) . "</td>";
                    }
                    $colIndex++;
                }
                echo "</tr>";
            }

            echo "</tbody></table>";
        } else {
            echo "<h2 class='text-center'>No records found.</h2>";
        }

        $conn->close();
        ?>
    </div>
    <div class="back-btn">
        <a href="admin_panal.html" class="btn btn-primary">
            ← Back to Homepage
        </a>
    </div>

</body>
</html>
