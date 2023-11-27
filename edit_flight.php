<?php
include './includes/db_connect.php';

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$error = [];
$flightId = $_GET['flight_id'];

$flightQuery = "SELECT * FROM flights WHERE flight_id = :flightId";
$stmt = $conn->prepare($flightQuery);
$stmt->bindParam(':flightId', $flightId);
$stmt->execute();
$flight = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$flight) {
    echo "Flight not found.";
    exit();
}

// Xử lý khi form được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $newFlightNumber = $_POST['flight_number'];
    $newTotalPassengers = $_POST['total_passengers'];
    $newDescription = $_POST['description'];
    $newAirlineId = $_POST['airline_id'];

    $updateQuery = "UPDATE flights SET flight_number = '$newFlightNumber', total_passengers = '$newTotalPassengers', description = '$newDescription', airline_id ='$newAirlineId' WHERE flight_id = '$flightId'";
    $conn->exec($updateQuery);
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Flight</title>
    <link rel="stylesheet" href="./css/styles.css">
    <style>
        .title {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div>
            <h2 class="title">Edit Flight</h2>
            <form action="" method="post" enctype="multipart/form-data">
                <!-- Hiển thị dữ liệu hiện tại của chuyến bay trong các input -->
                <label for="flight_number">Flight Number:</label>
                <input type="text" name="flight_number" value="<?php echo $flight['flight_number']; ?>"><br>

                <label for="total_passengers">Total Passengers:</label>
                <input type="text" name="total_passengers" value="<?php echo $flight['total_passengers']; ?>"><br>

                <label for="description">Description:</label>
                <input type="text" name="description" value="<?php echo $flight['description']; ?>"><br>

                <label for="airline_id">Airline:</label>
                <select name="airline_id">
                    <?php
                    $airlinesQuery = "SELECT * FROM airlines";
                    $airlinesResult = $conn->query($airlinesQuery);
                    $airlines = $airlinesResult->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($airlines as $airline) {
                        $selected = ($airline['airline_id'] == $flight['airline_id']) ? "selected" : "";
                        echo "<option value='{$airline['airline_id']}' $selected>{$airline['airline_name']}</option>";
                    }
                    ?>
                </select><br>

                <input type="submit" name="submit" value="Update Flight">
            </form>

            <br>
            <a href='index.php'>Back to Flight List</a>
            <br>
            <a href='logout.php'>Logout</a>
        </div>
    </div>
</body>

</html>