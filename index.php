<?php
include './includes/db_connect.php';

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$query = "SELECT flights.*, a.airline_name FROM flights INNER JOIN airlines a ON flights.airline_id = a.airline_id";

$result = $conn->query($query);

if ($result->rowCount() > 0) {
    echo "<table border='1'>
    <tr>
        <th>Flight Number</th>
        <th>Image</th>
        <th>Total Passengers</th>
        <th>Description</th>
        <th>Airline</th>
        <th>Actions</th>
    </tr>";

    $rows = $result->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row) {
        echo "<tr>
            <td>{$row['flight_number']}</td>
            <td><img src='./img/{$row['image']}' alt='Flight Image' width='50'></td>
            <td>{$row['total_passengers']}</td>
            <td>{$row['description']}</td>
            <td>{$row['airline_name']}</td>
            <td>
                <a href='edit_flight.php?flight_id={$row['flight_id']}'>Edit</a>
                <a href='delete_flight.php?flight_id={$row['flight_id']}' onclick='return confirm(\"Are you sure?\")'>Delete</a>
            </td>
        </tr>";
    }

    echo "</table>";
} else {
    echo "<table border='1'>
    <tr>
        <th>Flight Number</th>
        <th>Image</th>
        <th>Total Passengers</th>
        <th>Description</th>
        <th>Airline</th>
        <th>Actions</th>
    </tr>";
    echo "</table>";
    echo "No flights found";
}

// Thêm mới chuyến bay
echo "<a href='create_flight.php'>Add New Flight</a>";

// Đăng xuất
echo "<a href='logout.php'>Logout</a>";
