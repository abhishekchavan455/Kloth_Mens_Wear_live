<?php
include 'config.php';

// Query to fetch data
$sql = "SELECT * FROM personal_expenses";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Personal Expenses</title>
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

    <h1>View Personal Expenses</h1>

    <!-- 🔍 Search Input -->
    <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search expenses...">

    <!-- 📊 Responsive Table -->
    <div class="table-responsive mt-3">
        <table class="table table-bordered table-hover" id="dataTable">
            <thead class="table-light">
                <tr>
                    <th>Expense Name</th>
                    <th>Cash Paid</th>
                    <th>Gpay Paid</th>
                    <th>Total Amount</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['expense_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['cash_amount']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['gpay_amount']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['total_amount']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['expense_date']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No records found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="back-btn">
        <a href="admin_panal.html" class="btn btn-primary">
            ← Back to Homepage
        </a>
    </div>

</body>
</html>

<?php
$conn->close();
?>
