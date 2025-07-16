<?php
include 'config.php';

// Capture POST data
$expenseName = $_POST['expenseName'];
$cash = $_POST['cash'];
$gpay = $_POST['gpay'];
$total = $_POST['total_amount'];
$date = $_POST['expenseDate'];

// Use prepared statement
$stmt = $conn->prepare("INSERT INTO expense (expense_name, cash_amount, gpay_amount, total_amount, expenseDate) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sddds", $expenseName, $cash, $gpay, $total, $date);

// Execute and show result
if ($stmt->execute()) {
    echo "
    <script>alert('Expense recorded successfully!');</script>
    <div style='
        font-family: Arial, sans-serif;
        max-width: 90%;
        width: 400px;
        margin: 50px auto;
        text-align: center;
    '>
        <h2 style='color: #27ae60;'>Shop Expense Recorded Successfully!</h2>
        <a href='add_shop_expenses.html' style='
            display: inline-block;
            margin-top: 20px;
            padding: 12px 24px;
            background-color: #2c3e50;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 16px;
        '>Go Back</a>
    </div>
    ";
} else {
    echo "
    <script>alert('Error: " . addslashes($stmt->error) . "');</script>
    <h3 style='text-align:center; color:red;'>Error recording expense: " . htmlspecialchars($stmt->error) . "</h3>
    ";
}

$stmt->close();
$conn->close();
?>
