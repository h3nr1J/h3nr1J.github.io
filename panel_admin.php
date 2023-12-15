<?php
include("db.php");

// Assuming sp_ListarAdministradores returns the administrator's information
$sql = "CALL sp_ListarAdministradores()";
$result = $conn->query($sql);

// Check if the query was successful
if ($result) {
    // Fetch the administrator's information
    $adminInfo = $result->fetch_assoc();

    // Close the result set
    $result->close();
} else {
    // Handle the error if the query fails
    // You might want to log the error or display an error message
    echo "Error: " . $conn->error;
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido al sistema</title>
    <link rel="stylesheet" href="index.css">
    <style>

    </style>
</head>

<body>

    <?php include('sidebar.php'); ?>

</body>

</html>