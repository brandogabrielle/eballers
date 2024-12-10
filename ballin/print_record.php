<?php
include('db_connection.php');

if (!isset($_GET['id'])) {
    die("No record ID provided.");
}

$id = $_GET['id'];

// Fetch patient record
$query = "SELECT * FROM patient_registry WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$record = $result->fetch_assoc();

if (!$record) {
    die("Record not found.");
}

// Fetch services and options
$services_query = "SELECT services.service_name, service_options.option_name
                   FROM patient_services
                   JOIN services ON patient_services.service_id = services.service_id
                   LEFT JOIN service_options ON patient_services.option_id = service_options.option_id
                   WHERE patient_services.patient_id = ?";
$services_stmt = $conn->prepare($services_query);
$services_stmt->bind_param("i", $id);
$services_stmt->execute();
$services_result = $services_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="print_record.css"> <!-- Use the shared CSS file -->
    <title>Print Patient Record</title>
</head>
<body>
    <div class="record-container">
        <h1>Patient Record</h1>
        <div><strong>Registered Date:</strong> <?php echo htmlspecialchars($record['registry_date']); ?></div>
        <div><strong>Name:</strong> <?php echo htmlspecialchars($record['last_name'] . ', ' . $record['first_name'] . ' ' . $record['middle_name']); ?></div>
        <div><strong>Date of Birth:</strong> <?php echo htmlspecialchars($record['dob']); ?></div>
        <div><strong>Age:</strong> <?php echo htmlspecialchars($record['age']); ?></div>
        <div><strong>Address:</strong> <?php echo htmlspecialchars($record['address']); ?></div>
        <div><strong>Email:</strong> <?php echo htmlspecialchars($record['email']); ?></div>
        <div><strong>Mobile:</strong> <?php echo htmlspecialchars($record['mobile']); ?></div>
        <div><strong>Appointment Date:</strong> <?php echo htmlspecialchars($record['appointment_date']); ?></div>
        <h3>Services:</h3>
        <ul>
            <?php
            while ($service = $services_result->fetch_assoc()) {
                echo "<li>" . htmlspecialchars($service['service_name']);
                if (!empty($service['option_name'])) {
                    echo " - " . htmlspecialchars($service['option_name']);
                }
                echo "</li>";
            }
            ?>
        </ul>
        <div><strong>Additional Info:</strong> <?php echo htmlspecialchars($record['add_info']); ?></div>
    </div>
    <button class="print-button" onclick="window.print();">Print</button>
    <div class="bruh-button">
        <a href="patient_records.php">Go back to Records</a>
    </div>
</body>
</html>
