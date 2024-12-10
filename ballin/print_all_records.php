<?php
include('db_connection.php');

// Fetch all patient records
$query = "SELECT * FROM patient_registry";
$result = $conn->query($query);

if (!$result) {
    die("Error retrieving records: " . $conn->error);
}

// Fetch associated services for each patient
function getServices($conn, $patient_id) {
    $services_query = "SELECT services.service_name, service_options.option_name
                       FROM patient_services
                       JOIN services ON patient_services.service_id = services.service_id
                       LEFT JOIN service_options ON patient_services.option_id = service_options.option_id
                       WHERE patient_services.patient_id = ?";
    $stmt = $conn->prepare($services_query);
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $services_result = $stmt->get_result();

    $services = [];
    while ($row = $services_result->fetch_assoc()) {
        $service = $row['service_name'];
        if (!empty($row['option_name'])) {
            $service .= " (" . $row['option_name'] . ")";
        }
        $services[] = $service;
    }
    return $services;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="print_all_records.css">
    <title>Print All Records</title>
</head>
<body>
<div class="container">
    <div class="non-printable">
        <button class="print-button" onclick="window.print();">Print</button>
    </div>
    <div class="half">
        <?php while ($row = $result->fetch_assoc()): ?>
        <div class="record">
            <h3>Patient Record</h3>
            <div><label>Registered Date:</label> <?php echo $row['registry_date']; ?></div>
            <div><label>Name:</label> <?php echo $row['last_name'] . ', ' . $row['first_name'] . ' ' . $row['middle_name']; ?></div>
            <div><label>Date of Birth:</label> <?php echo $row['dob']; ?></div>
            <div><label>Age:</label> <?php echo $row['age']; ?></div>
            <div><label>Address:</label> <?php echo $row['address']; ?></div>
            <div><label>Email:</label> <?php echo $row['email']; ?></div>
            <div><label>Mobile:</label> <?php echo $row['mobile']; ?></div>
            <div><label>Appointment Date:</label> <?php echo $row['appointment_date']; ?></div>
            <div><label>Additional Info:</label> <?php echo $row['add_info']; ?></div>
            <h4>Services</h4>
            <ul>
                <?php
                $services = getServices($conn, $row['id']);
                foreach ($services as $service) {
                    echo "<li>" . $service . "</li>";
                }
                ?>
            </ul>
        </div>
        <?php endwhile; ?>
    </div>
    <div class="bruh-button">
        <a href="patient_records.php">Go back to Records</a>
    </div>
</div>
</body>
</html>

