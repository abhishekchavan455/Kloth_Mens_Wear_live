<?php
include 'config.php';

$sql = "SELECT * FROM transactions";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Transactions</title>

    <!-- Bootstrap CDN for responsiveness -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
            font-family: Arial, sans-serif;
            padding: 20px;
            background: linear-gradient(to right, #ecf0f1, #e0eafc);
            color: #1f1f1f;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        #searchInput {
            width: 100%;
            max-width: 1000px;
            padding: 10px;
            margin: 0 auto 20px auto;
            display: block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: inset 1px 1px 2px rgba(0, 0, 0, 0.1);
            font-size: 16px;
        }

        #searchInput::placeholder {
            color: #999;
        }

        .table-responsive {
            overflow-x: auto;
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
            var input = document.getElementById('searchInput');
            var filter = input.value.toLowerCase();
            var table = document.getElementById('dataTable');
            var tr = table.getElementsByTagName('tr');

            for (var i = 1; i < tr.length; i++) {
                var visible = false;
                var td = tr[i].getElementsByTagName('td');
                for (var j = 0; j < td.length; j++) {
                    if (td[j] && td[j].innerText.toLowerCase().indexOf(filter) > -1) {
                        visible = true;
                        break;
                    }
                }
                tr[i].style.display = visible ? "" : "none";
            }
        }
    </script>
</head>
<body>

    <h1>View All Transactions</h1>
    <!-- 🔍 Search Box -->
    <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search for transactions...">

    <!-- 📊 Responsive Table -->
    <div class="table-responsive mt-3">
        <table class="table table-bordered table-hover" id="dataTable">
            <thead class="table-light">
                <tr>
                    <th>Transaction Id</th>
                    <th>Product Id</th>
                    <th>Product Name</th>
                    <th>Product Price</th>
                    <th>Product Quantity</th>
                    <th>Total Amount</th>
                    <th>Customer Contact</th>
                    <th>Payment Method</th>
                    <th>Customer Name</th>
                    <th>Date Time</th>
                    <th>Profit</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        for ($i = 0; $i < 11; $i++) {
                            echo "<td>" . htmlspecialchars($row[array_keys($row)[$i]]) . "</td>";
                        }
                        echo "</tr>";
                    }
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
        
    <div class="back-btn">
        <a href="admin_panal.html" class="btn btn-secondary">
            ← Back to Homepage
        </a>
    </div>

</body>
</html>
