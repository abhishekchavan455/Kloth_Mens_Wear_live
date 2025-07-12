<?php
include 'config.php';

// Capture POST data
$expenseName = $_POST['expenseName'];
$cash = $_POST['cash'];
$gpay = $_POST['gpay'];
$total = $_POST['total_amount'];
$date = $_POST['expenseDate'];

// Insert into database
$sql = "INSERT INTO personal_expenses (expense_name, cash_amount, gpay_amount, total_amount, expense_date)
        VALUES ('$expenseName', '$cash', '$gpay', '$total', '$date')";

if ($conn->query($sql) === TRUE) {
    echo "<h2>Expense recorded successfully!</h2><a href='add_personal_expenses.html'>Go Back</a>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
