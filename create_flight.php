<?php
include './includes/db_connect.php';

// Kiểm tra đăng nhập bằng COOKIE
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
$error = [];
// Xử lý khi form được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $flightNumber = $_POST['flight_number'];
    $totalPassengers = $_POST['total_passengers'];
    $description = $_POST['description'];
    $airlineId = $_POST['airline_id'];

    if (empty($flightNumber)) {
        $errors['flight_number'] = "Flight Number is required.";
    } elseif (!is_numeric($flightNumber)) {
        $errors['flight_number'] = "Flight Number must be a positive number.";
    }

    if (isset($_FILES['flight_image']) && $_FILES['flight_image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "img/";
        $name_img = $_FILES["flight_image"]["name"];
        $target_file = $target_dir . $name_img;
        move_uploaded_file($_FILES["flight_image"]['tmp_name'], $target_file);
    } else {
        $errors['flight_image'] = "Image  is required or upload failed.";
    }

    if (empty($totalPassengers)) {
        $errors['total_passengers'] = "Total Passengers is required.";
    } elseif (!is_numeric($totalPassengers) || $totalPassengers <= 0) {
        $errors['total_passengers'] = "Total passengers must be a positive number.";
    }

    if (empty($description)) {
        $errors['description'] = "Description is required.";
    }

    if (empty($airlineId)) {
        $errors['airlineId'] = "Airline is required.";
    }

    if (empty($errors)) {
        $sql = "INSERT INTO flights ( flight_number, image, total_passengers, description, airline_id) VALUES ( '$flightNumber', '$name_img', '$totalPassengers', '$description', '$airlineId')";

        $conn->exec($sql);
        header("Location: index.php");
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Flight</title>
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
            <h2 class="title">Add New Flight</h2>
            <form action="" method="post" enctype="multipart/form-data">
                <label for="flight_number">Flight Number:</label>
                <input type="text" name="flight_number"><br>
                <?php if (isset($errors['flight_number'])) echo "<div style='color: red;'>{$errors['flight_number']}</div>"; ?><br>

                <label for="image">Image URL:</label>
                <input type="file" name="flight_image" accept="image/*"><br>
                <?php if (isset($errors['flight_image'])) echo "<div style='color: red;'>{$errors['flight_image']}</div>"; ?><br>

                <label for="total_passengers">Total Passengers:</label>
                <input type="text" name="total_passengers"><br>
                <?php if (isset($errors['total_passengers'])) echo "<div style='color: red;'>{$errors['total_passengers']}</div>"; ?><br>

                <label for="description">Description:</label>
                <input type="text" name="description"><br>
                <?php if (isset($errors['description'])) echo "<div style='color: red;'>{$errors['description']}</div>"; ?><br>


                <label for="airline_id">Airline:</label>
                <select name="airline_id">
                    <?php
                    $airlinesQuery = "SELECT * FROM airlines";
                    $airlinesResult = $conn->query($airlinesQuery);

                    $airlines = $airlinesResult->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($airlines as $airline) {
                        echo "<option value='{$airline['airline_id']}'>{$airline['airline_name']}</option>";
                    }
                    ?>
                </select><br>

                <input type="submit" name="submit" value="Add Flight">
            </form>
            <?php if (isset($errors['general'])) echo "<div style='color: red;'>Error: {$errors['general']}</div>"; ?>


            <br>
            <a href='index.php'>Back to Flight List</a>
            <br>
            <a href='logout.php'>Logout</a>
        </div>
    </div>
</body>

</html>