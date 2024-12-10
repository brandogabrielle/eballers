<?php
include('db_connection.php');

// Check if the patient ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch existing patient data
    $query = "SELECT * FROM patient_registry WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $patient = $result->fetch_assoc();

    if (!$patient) {
        die("Patient not found.");
    }
} else {
    die("No patient ID provided.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="patient_registry.css">
    <title>Edit Patient</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Edit Patient</h1>
            <div class="bruh-button">
                <a href="patient_records.php">Back to Records</a>
            </div>
        </div>
        <form action="edit_record.php" method="POST">
            <!-- Hidden ID -->
            <input type="hidden" name="id" value="<?php echo $patient['id']; ?>">

            <!-- Registry Date -->
            <div class="form-group">
                <label for="registryDate">Date of Registry:</label>
                <input type="date" id="registryDate" name="registryDate" value="<?php echo $patient['registry_date']; ?>" required>
            </div>

            <div class="name-section">
                <div class="form-group">
                    <label for="lastName">Last Name:</label>
                    <input type="text" id="lastName" name="lastName" value="<?php echo $patient['last_name']; ?>" required>
                </div>

                <!-- First Name -->
                <div class="form-group">
                    <label for="firstName">First Name:</label>
                    <input type="text" id="firstName" name="firstName" value="<?php echo $patient['first_name']; ?>" required>
                </div>

                <!-- Middle Name -->
                <div class="form-group">
                    <label for="middleName">Middle Name:</label>
                    <input type="text" id="middleName" name="middleName" value="<?php echo $patient['middle_name']; ?>">
                </div>
            </div>

            <!-- Date of Birth -->
            <div class="form-group">
                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" value="<?php echo $patient['dob']; ?>" required>
            </div>

            <!-- Age -->
            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" value="<?php echo $patient['age']; ?>" required>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" id="email" name="email" value="<?php echo $patient['email']; ?>">
            </div>

            <!-- Address -->
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo $patient['address']; ?>" required>
            </div>

            <!-- Mobile Number -->
            <div class="form-group">
                <label for="mobile">Mobile Number:</label>
                <input type="tel" id="mobile" name="mobile" value="<?php echo $patient['mobile']; ?>" required>
            </div>

            <!-- Appointment Schedule -->
            <div class="form-group">
                <label for="appointmentDate">Appointment Schedule:</label>
                <input type="date" id="appointmentDate" name="appointmentDate" value="<?php echo $patient['appointment_date']; ?>" required>
            </div>

            <h2>Services:</h2>
            <div class="services-section">
                <?php
                // Fetch services and options
                $services_query = "SELECT * FROM services";
                $services_result = $conn->query($services_query);

                while ($service = $services_result->fetch_assoc()) {
                    echo "<div class='service-group'>";
                    echo "<h3>{$service['service_name']}</h3>";

                    $options_query = "SELECT * FROM service_options WHERE service_id = {$service['service_id']}";
                    $options_result = $conn->query($options_query);

                    echo "<ul>";
                    while ($option = $options_result->fetch_assoc()) {
                        // Check if the option is already selected for this patient
                        $selected_query = "SELECT * FROM patient_services WHERE patient_id = ? AND service_id = ? AND option_id = ?";
                        $selected_stmt = $conn->prepare($selected_query);
                        $selected_stmt->bind_param("iii", $patient['id'], $service['service_id'], $option['option_id']);
                        $selected_stmt->execute();
                        $selected_result = $selected_stmt->get_result();

                        $checked = $selected_result->num_rows > 0 ? "checked" : "";

                        echo "<li><label><input type='checkbox' name='services[{$service['service_id']}][]' value='{$option['option_id']}' $checked> {$option['option_name']}</label></li>";
                    }
                    echo "</ul>";

                    echo "</div>"; // Closing service-group
                }
                ?>
            </div>

            <!-- Additional Info -->
            <div class="form-group">
                <label for="addInfo">Additional Info:</label>
                <textarea id="addInfo" name="addInfo"><?php echo $patient['add_info']; ?></textarea>
            </div>

            <!-- Submit Button -->
            <div class="button-container">
                <button type="submit">Update</button>
            </div>
        </form>
    </div>
</body>
</html>
